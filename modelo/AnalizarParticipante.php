<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\AnalizarParticipante.php

class AnalizarParticipante
{
    public ?int $id;
    public int $participante_id;
    public int $estado;
    public ?string $comentario;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $participante_id,
        int $estado = 0,
        ?string $comentario = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->participante_id = $participante_id;
        $this->estado = $estado;
        $this->comentario = $comentario;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}