<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\LogAnalisis.php

class LogAnalisis
{
    public ?int $id;
    public int $analisis_id;
    public string $paso;
    public string $comentario;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $analisis_id,
        string $paso,
        string $comentario,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->analisis_id = $analisis_id;
        $this->paso = $paso;
        $this->comentario = $comentario;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}