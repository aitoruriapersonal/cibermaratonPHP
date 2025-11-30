<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/ParticipantesDAO.php';

class ParticipantesService
{
    private ParticipantesDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ParticipantesDAO($pdo);
    }

    public function getParticipanteById(int $id): ?Participante
    {
        return $this->dao->getParticipanteById($id);
    }

    public function getParticipantesByNick(string $nick): array
    {
        return $this->dao->getParticipantesByNick($nick);
    }
    // Participantes
    public function getParticipantesByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesByCampeonato($campeonatoId);
    }

    public function getParticipantesUniversitariosByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesUniversitariosByCampeonato($campeonatoId);
    }

    public function getParticipantesFederadosByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesFederadosByCampeonato($campeonatoId);
    }

    public function createParticipante(array $data): ?Participante
    {
        $nickGenerado = $this->generarNick($data['tipo'], $data['dni'], $data['nombre']);
        $data['nick'] = $nickGenerado;
        $participanteInsertadoId = $this->dao->createParticipante($data);
        //$participanteInsertadoId = $this->dao->create($data);
        return $this->dao->getParticipanteById($participanteInsertadoId);
    }

    public function searchParticipanteNick(string $nick): string
    {
        return $this->dao->searchParticipanteNick($nick);
    }

    public function searchDniDuplicadoByCampeonato(int $campeonatoId, string $dni): array
    {
        return $this->dao->searchDniDuplicadoByCampeonato($campeonatoId, $dni);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

        public function actualizarParticipante(Participante $p): bool
    {
        return $this->dao->update($p);
    }

    public function eliminarParticipante(int $id): bool
    {
        return $this->dao->deleteParticipante($id);
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
        $lastNick = $this->dao->searchParticipanteNick($like);

        if ($lastNick && preg_match('/(\d+)$/', $lastNick, $matches)) {
            $correlativo = (int)$matches[1] + 1;
        } else {
            $correlativo = 1;
        }

        return $prefijo . $dni5 . $nombreCamel . $correlativo;
    }

     public function getParticipantesNoTerminadosSinFinalizar(): array
    {
        return $this->dao->getParticipantesNoTerminadosSinFinalizar();
    }

    public function getParticipantesActivosCibermaratonActivo(): array
    {
        return $this->dao->getParticipantesActivosCibermaratonActivo();
    }

     public function actualizarEstado($participanteId, $nuevoEstado) {
        $this->dao->actualizarEstado($participanteId, $nuevoEstado);
        return $this->dao->getParticipanteById($participanteId);
    }

    public function actualizarEstadoTerminado($participante) {
        $this->dao->actualizarEstadoTerminado($participante);
        return $this->dao->getParticipanteById($participante->id);
    }

    public function actualizarPuntos($participanteId, $nuevosPuntos) {
        $this->dao->actualizarPuntos($participanteId, $nuevosPuntos);
        return $this->dao->getParticipanteById($participanteId);
    }

}