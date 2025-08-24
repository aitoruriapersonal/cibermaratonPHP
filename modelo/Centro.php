<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Centro.php

class Centro
{
    public ?int $id;
    public int $campus_id;
    public string $nombre_eus;
    public string $nombre_esp;
    public int $estado;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $campus_id,
        string $nombre_eus,
        string $nombre_esp,
        int $estado = 1,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->campus_id = $campus_id;
        $this->nombre_eus = $nombre_eus;
        $this->nombre_esp = $nombre_esp;
        $this->estado = $estado;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}