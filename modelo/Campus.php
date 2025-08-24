<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Campus.php

class Campus
{
    public ?int $id;
    public int $universidad_id;
    public string $nombre_eus;
    public string $nombre_esp;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $universidad_id,
        string $nombre_eus,
        string $nombre_esp,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->universidad_id = $universidad_id;
        $this->nombre_eus = $nombre_eus;
        $this->nombre_esp = $nombre_esp;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}