<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Universidad.php

class Universidad
{
    public ?int $id;
    public string $nombre_eus;
    public ?string $siglas_eus;
    public string $nombre_esp;
    public ?string $siglas_esp;
    public int $estado;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        string $nombre_eus,
        ?string $siglas_eus,
        string $nombre_esp,
        ?string $siglas_esp,
        int $estado = 1,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->nombre_eus = $nombre_eus;
        $this->siglas_eus = $siglas_eus;
        $this->nombre_esp = $nombre_esp;
        $this->siglas_esp = $siglas_esp;
        $this->estado = $estado;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}