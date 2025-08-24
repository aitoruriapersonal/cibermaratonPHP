<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Provincia.php

class Provincia
{
    public ?int $id;
    public string $nombre_esp;
    public string $nombre_eus;
    public int $cod_provincia;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        string $nombre_esp,
        string $nombre_eus,
        int $cod_provincia,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->nombre_esp = $nombre_esp;
        $this->nombre_eus = $nombre_eus;
        $this->cod_provincia = $cod_provincia;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}