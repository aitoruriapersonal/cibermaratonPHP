<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Resultado.php

class Resultado
{
    // Constante para formato de fecha
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    
    // Propiedades básicas
    public ?int $id;
    public int $participanteId;
    
    // Grupo: Datos del usuario propio
    public string $username;
    public string $color;
    public int $elo;
    
    // Grupo: Datos del rival
    public string $rival;
    public string $colorRival;
    public int $eloRival;
    
    // Grupo: Datos del resultado de la partida
    public string $resultado;
    public string $resultadoDesc;
    public string $fechaPartida;
    public string $urlPartida;
    public int $numeroPartida;
    
    // Fechas del sistema
    public string $fechaAlta;
    public ?string $fechaModificacion;

    /**
     * Constructor sin argumentos
     */
    public function __construct()
    {
        $this->id = null;
        $this->participanteId = 0;
        $this->username = '';
        $this->color = '';
        $this->elo = 0;
        $this->rival = '';
        $this->colorRival = '';
        $this->eloRival = 0;
        $this->resultado = '';
        $this->resultadoDesc = '';
        $this->fechaAlta = date(self::DATE_FORMAT);
        $this->urlPartida = '';
        $this->numeroPartida = 0;
        $this->fechaAlta = date('Y-m-d H:i:s');
        $this->fechaModificacion = null;
    }
    
    /**
     * Establecer datos del usuario propio
     */
    public function setDatosUsuario(string $username, string $color, int $elo): self
    {
        $this->username = $username;
        $this->color = $color;
        $this->elo = $elo;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer datos del rival
     */
    public function setDatosRival(string $rival, string $colorRival, int $eloRival): self
    {
        $this->rival = $rival;
        $this->colorRival = $colorRival;
        $this->eloRival = $eloRival;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer datos del resultado de la partida
     */
    public function setDatosPartida(
        string $resultado,
        string $resultadoDesc,
        string $fechaPartida,
        string $urlPartida,
        int $numeroPartida
    ): self {
        $this->resultado = $resultado;
        $this->resultadoDesc = $resultadoDesc;
        $this->fechaPartida = $fechaPartida;
        $this->urlPartida = $urlPartida;
        $this->numeroPartida = $numeroPartida;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer ID y participante
     */
    public function setDatosBasicos(?int $id, int $participanteId): self
    {
        $this->id = $id;
        $this->participanteId = $participanteId;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Métodos de utilidad para establecer campos individuales
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setParticipanteId(int $participanteId): self
    {
        $this->participanteId = $participanteId;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setUrlPartida(string $urlPartida): self
    {
        $this->urlPartida = $urlPartida;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setFechaPartida(string $fechaPartida): self
    {
        $this->fechaPartida = $fechaPartida;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer número de partida
     */
    public function setNumeroPartida(int $numeroPartida): self
    {
        $this->numeroPartida = $numeroPartida;
        $this->actualizarFechaModificacion();
        return $this;
    }

    /**
     * Actualizar la fecha de modificación al momento actual
     */    private function actualizarFechaModificacion(): void
    {
        $this->fechaModificacion = date(self::DATE_FORMAT);
    }
    
    /**
     * Método para validar que todos los campos obligatorios están completos
     */
    public function validar(): array
    {
        $errores = [];
        
        if ($this->participanteId <= 0) {
            $errores[] = 'participanteId debe ser mayor que 0';
        }
        
        if (empty($this->username)) {
            $errores[] = 'username es obligatorio';
        }
        
        if (empty($this->color)) {
            $errores[] = 'color es obligatorio';
        }
        
        if (empty($this->rival)) {
            $errores[] = 'rival es obligatorio';
        }
        
        if (empty($this->colorRival)) {
            $errores[] = 'colorRival es obligatorio';
        }
        
        if (empty($this->resultado)) {
            $errores[] = 'resultado es obligatorio';
        }
        
        if (empty($this->urlPartida)) {
            $errores[] = 'urlPartida es obligatorio';
        }
        
        if (empty($this->fechaPartida)) {
            $errores[] = 'fechaPartida es obligatorio';
        }
        
        return $errores;
    }
    
    /**
     * Método para verificar si el objeto está completo
     */
    public function estaCompleto(): bool
    {
        return empty($this->validar());
    }
    
    /**
     * Método toString para debug
     */
    public function __toString(): string
    {
        return sprintf(
            "Resultado[id=%s, participante=%d, %s(%s,%d) vs %s(%s,%d) = %s, partida=%s]",
            $this->id ?? 'null',
            $this->participanteId,
            $this->username,
            $this->color,
            $this->elo,
            $this->rival,
            $this->colorRival,
            $this->eloRival,
            $this->resultado,
            $this->urlPartida
        );
    }
    
    /**
     * Método estático para crear un resultado desde un array
     */
    public static function fromArray(array $data): self
    {
        $resultado = new self();
        
        if (isset($data['id'])){ $resultado->setId($data['id']);}
        if (isset($data['participanteId'])){ $resultado->setParticipanteId($data['participanteId']);}
        
        if (isset($data['username'], $data['color'], $data['elo'])) {
            $resultado->setDatosUsuario($data['username'], $data['color'], $data['elo']);
        }
        
        if (isset($data['rival'], $data['colorRival'], $data['eloRival'])) {
            $resultado->setDatosRival($data['rival'], $data['colorRival'], $data['eloRival']);
        }
        
        if (isset($data['resultado'], $data['resultadoDesc'], $data['fechaPartida'], $data['urlPartida'], $data['numeroPartida'])) {
            $resultado->setDatosPartida(
                $data['resultado'],
                $data['resultadoDesc'],
                $data['fechaPartida'],
                $data['urlPartida'],
                $data['numeroPartida']
            );
        }
        
        return $resultado;
    }
}
