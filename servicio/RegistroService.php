<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/UniversidadesService.php';
require_once __DIR__ . '/../utilidades/Utils.php';

class RegistroService
{
    private ParticipantesService $participanteService;
    private UniversidadesService $universidadesService;

    public function __construct(PDO $pdo)
    {
        $this->participanteService = new ParticipantesService($pdo);
        $this->universidadesService = new UniversidadesService($pdo);
    }

    public function registrarParticipante(array $data): array
    {
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
            if($universidad['siglas_eus']=='EHU'){
                $data['tipo'] = 'UPV';
            }else{
                $data['tipo'] = 'UNI';
            }
        }elseif($tipoInscripcion == 'federado'){
            $data['tipo'] = 'FED';
        }
        $dniDuplicado = $this->participanteService->searchDniDuplicadoByCampeonato($data['campeonato'], $data['dni']);
        if($dniDuplicado){
            throw new InvalidArgumentException('El DNI ya está registrado en el campeonato.');
        }
        $nickGenerado = $this->generarNick($data['tipo'], $data['dni'], $data['nombre']);
        $data['nick'] = $nickGenerado;
        $participanteInsertadoId = $this->participanteService->createParticipante($data);
        $respuesta['participante'] = $this->participanteService->getParticipanteById($participanteInsertadoId['id']);
        $respuesta['resultado'] = 'OK';
        $respuesta['mensajeES'] = 'Se ha registrado correctamente en el Cibermaraton. Su codigo de participante (nick) es ' . $data['nick']
            .'. <br>Debe registrarse en <a href="https://www.chess.com/" target="_blank">Chess.com</a> con dicho nick. De no hacerlo así no se le podrá hacer el seguimiento correspondiente.'
            .'<br>En caso de tener algún problema en el registro en Chess.com háganoslo saber en info.ajedrez@deporteuniversitario.com o ajedrez@deporte-universitario.com';
        $respuesta['mensajeEU'] = 'Zibermaratoian erregistratu egin da. Zure parte-hartzaile kodea (nick) ' . $data['nick'] . ' da.'
            .' <br>Mesedez, <a href="https://www.chess.com/" target="_blank">Chess.com</a> webgunean erregistratu zaitez emandako nick-arekin. Horrela, jarraipena egin ahal izango dizuegu.'
            .'<br>Edozein arazo izanez gero, idatzi  info.ajedrez@deporteuniversitario.com edo ajedrez@deporte-universitario.com helbideetara.';
        return $respuesta;
    }

    /**
     * Genera un nick formateado según las reglas:
     * - 23UPV para universitarios UPV
     * - 23UNI para otros universitarios
     * - 23FED para federados
     * Luego los primeros 5 dígitos del DNI, el nombre (sin espacios, en CamelCase, sin tildes ni ñ/Ñ), y un número correlativo.
     *
     * @param string $tipo ('UPV', 'UNI', 'FED')
     * @param string $dni
     * @param string $nombre
     * @return string
     */
    private function generarNick(string $tipo, string $dni, string $nombre): string
    {
        $prefijo =Utils::getNickPrefijo($tipo);

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