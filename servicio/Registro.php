<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/ParticipantesDAO.php';
require_once __DIR__ . '/../dao/UniversidadesDAO.php';

class RegistroService
{
    private ParticipantesDAO $participanteDao;
    private UniversidadesDAO $universidadesDao;

    public function __construct(PDO $pdo)
    {
        $this->participanteDao = new ParticipantesDAO($pdo);
        $this->universidadesDao = new UniversidadesDAO($pdo);
    }

    public function registrarParticipante(array $data): Participante
    {
        $tipoInscripcion = $data['tipoInscripcion'] ?? null;
        if($tipoInscripcion == 'universitario'){
            $universidadId = $data['universidadId'] ?? null;
            if (!$universidadId) {
                throw new InvalidArgumentException('Se debe proporcionar el ID de la universidad.');
            }
            $universidad = $this->universidadesDao->getUniversidadById($universidadId);
            if (!$universidad) {
                throw new InvalidArgumentException('La universidad no existe.');
            }
            if($universidad['siglas_eus']=='EHU'){
                $data['tipo'] = 'EHU';
            }else{
                $data['tipo'] = 'UNI';
            }
        }elseif($tipoInscripcion == 'federado'){
            $data['tipo'] = 'FED';
        }
        $nickGenerado = $this->generarNick($data['tipo'], $data['dni'], $data['nombre']);
        $data['nick'] = $nickGenerado;
        $participanteInsertadoId = $this->participanteDao->createParticipante($data);
        return $this->participanteDao->getParticipanteById($participanteInsertadoId);
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
        $prefijo =Utils::getNickPrefijo($tipo);

        $dni5 = substr($dni, 0, 5);

        // Eliminar tildes y ñ/Ñ, y convertir a CamelCase sin espacios
        $nombre = Utils::quitarTildesYN($nombre);
        $nombreCamel = Utils::nombreToCamelCase($nombre);

        // Buscar el último nick similar para calcular el correlativo
        $like = $prefijo . $dni5 . $nombreCamel . '%';
        $lastNick = $this->participanteDao->searchParticipanteNick($like);

        if ($lastNick && preg_match('/(\d+)$/', $lastNick, $matches)) {
            $correlativo = (int)$matches[1] + 1;
        } else {
            $correlativo = 1;
        }

        return $prefijo . $dni5 . $nombreCamel . $correlativo;
    }

}