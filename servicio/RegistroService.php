<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\servicio\RegistroService.php

require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/UniversidadesService.php';
require_once __DIR__ . '/../servicio/CampeonatosService.php';
require_once __DIR__ . '/../utilidades/Utils.php';

class RegistroService
{
    private ParticipantesService $participanteService;
    private UniversidadesService $universidadesService;
    private CampeonatosService $campeonatosService;

    public function __construct(PDO $pdo)
    {
        $this->participanteService = new ParticipantesService($pdo);
        $this->universidadesService = new UniversidadesService($pdo);
        $this->campeonatosService = new CampeonatosService($pdo);
    }

    public function registrarParticipante(array $data): array
    {
        // NUEVO: Validar que el campeonato esté dentro del plazo de inscripción
        $campeonatoId = $data['campeonato'] ?? null;
        if (!$campeonatoId) {
            throw new InvalidArgumentException('Se debe proporcionar el ID del campeonato.');
        }

        $campeonato = $this->campeonatosService->getCampeonatoById($campeonatoId);
        if (!$campeonato) {
            throw new InvalidArgumentException('El campeonato no existe.');
        }

        // Obtener fecha actual
        $fechaActual = new DateTime();
        $fechaInicio = new DateTime($campeonato->fecha_inicio);
        $fechaFin = new DateTime($campeonato->fecha_fin);

        // Validar que estemos dentro del plazo
        if ($fechaActual < $fechaInicio) {
            // El plazo de registro no ha comenzado
            $respuesta = [];
            $respuesta['resultado'] = 'ERROR';
            $respuesta['mensajeES'] = 'El plazo de inscripción aún no ha comenzado. '
                . 'La fecha de inicio es: ' . $fechaInicio->format('d/m/Y H:i') . '. '
                . 'Por favor, inténtelo de nuevo a partir de esa fecha.';
            $respuesta['mensajeEU'] = 'Izen-emateko epea ez da hasi oraindik. '
                . 'Hasiera data: ' . $fechaInicio->format('Y/m/d H:i') . '. '
                . 'Mesedez, saiatu berriro data horretatik aurrera.';
            return $respuesta;
        }

        if ($fechaActual > $fechaFin) {
            // El plazo de registro ha finalizado
            $respuesta = [];
            $respuesta['resultado'] = 'ERROR';
            $respuesta['mensajeES'] = 'El plazo de inscripción ha finalizado. '
                . 'La fecha límite era: ' . $fechaFin->format('d/m/Y H:i') . '. '
                . 'Lo sentimos, pero ya no es posible registrarse en este campeonato.';
            $respuesta['mensajeEU'] = 'Izen-emateko epea amaitu da. '
                . 'Azken eguna: ' . $fechaFin->format('Y/m/d H:i') . ' zen. '
                . 'Sentitzen dugu, baina ez da posible txapelketa honetan izena ematea.';
            return $respuesta;
        }

        // Si llegamos aquí, estamos dentro del plazo válido
        echo '<br/>DEBUG: Campeonato válido. Fecha actual entre ' . $fechaInicio->format('d/m/Y') . ' y ' . $fechaFin->format('d/m/Y');

        // Continuar con el registro existente
        $tipoInscripcion = $data['tipoInscripcion'] ?? null;
        if($tipoInscripcion == 'universitario'){
            $universidadId = $data['universidad'] ?? null;
            if (!$universidadId) {
                throw new InvalidArgumentException('Se debe proporcionar el ID de la universidad.');
            }
            $universidad = $this->universidadesService->getUniversidadById($universidadId);
            if (!$universidad) {
                throw new InvalidArgumentException('La universidad no existe.');
            }
            if($universidad->siglas_eus == 'EHU'){
                $data['tipo'] = 'EHU';
            }else{
                $data['tipo'] = 'UNI';
            }
        }elseif($tipoInscripcion == 'federado'){
            $data['tipo'] = 'FED';
        }
        
        // Validar DNI duplicado
        $dniDuplicado = $this->participanteService->searchDniDuplicadoByCampeonato($data['campeonato'], $data['dni']);
        if($dniDuplicado){
            throw new InvalidArgumentException('DNI duplicado');
        }
        
        // Generar nick
        $nickGenerado = $this->generarNick($data['tipo'], $data['dni'], $data['nombre']);
        $data['nick'] = $nickGenerado;
        
        // Crear participante
        $participanteInsertadoId = $this->participanteService->createParticipante($data);
        $participanteInsertado = $this->participanteService->getParticipanteById($participanteInsertadoId->id);
        
        // Preparar respuesta
        $respuesta['participante'] = $participanteInsertado;
        
        // Enviar email de inscripción
        try {
            Utils::emailInscripcionChess($participanteInsertado->email, $participanteInsertado->nick, $participanteInsertado->nombre);
            echo '<br/>DEBUG: Email de inscripción enviado a ' . $participanteInsertado->email;
        } catch (Exception $e) {
            echo '<br/>WARNING: No se pudo enviar email de inscripción: ' . $e->getMessage();
        }

        $respuesta['resultado'] = 'OK';
        $respuesta['mensajeES'] = 'Se ha registrado correctamente en el Cibermaraton. Su código de participante (nick) es ' . $data['nick']
            .'. <br>Debe registrarse en <a href="https://www.chess.com/" target="_blank">Chess.com</a> con dicho nick. De no hacerlo así no se le podrá hacer el seguimiento correspondiente.'
            .'<br>En caso de tener algún problema en el registro en Chess.com háganoslo saber en info.ajedrez@deporteuniversitario.com o ajedrez@deporte-universitario.com';
        $respuesta['mensajeEU'] = 'Zibermaratoian erregistratu egin da. Zure parte-hartzaile kodea (nick) ' . $data['nick'] . ' da.'
            .' <br>Mesedez, <a href="https://www.chess.com/" target="_blank">Chess.com</a> webgunean erregistratu zaitez emandako nick-arekin. Horrela, jarraipena egin ahal izango dizuegu.'
            .'<br>Edozein arazo izanez gero, idatzi  info.ajedrez@deporteuniversitario.com edo ajedrez@deporte-universitario.com helbideetara.';
        
        return $respuesta;
    }

    /**
     * Genera un nick formateado según las reglas:
     * - 23EHU para universitarios EHU
     * - 23UNI para otros universitarios
     * - 23FED para federados
     * Luego los primeros 5 dígitos del DNI, el nombre (sin espacios, en CamelCase, sin tildes ni ñ/Ñ), y un número correlativo.
     *
     * @param string $tipo ('EHU', 'UNI', 'FED')
     * @param string $dni
     * @param string $nombre
     * @return string
     */
    private function generarNick(string $tipo, string $dni, string $nombre): string
    {
        $prefijo = Utils::getNickPrefijo($tipo);

        $dni5 = substr($dni, 0, 5);

        // Eliminar tildes y ñ/Ñ, y convertir a CamelCase sin espacios
        $nombre = Utils::quitarTildesYN($nombre);
        $nombreCamel = Utils::nombreToCamelCase($nombre);

        // Buscar el último nick similar para calcular el correlativo
        $like = $prefijo . $dni5 . $nombreCamel . '%';
        $lastNick = $this->participanteService->searchParticipanteNick($like);

        if ($lastNick && preg_match('/(\d+)$/', $lastNick, $matches)) {
            $correlativo = (int)$matches[1] + 1;
        } else {
            $correlativo = 1;
        }

        return $prefijo . $dni5 . $nombreCamel . $correlativo;
    }
}