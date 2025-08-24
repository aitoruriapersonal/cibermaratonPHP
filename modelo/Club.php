<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Club.php

namespace modelo;

class Club
{
    public ?int $id;
    public int $provincia_id;
    public string $nombre;
    public string $direccion;
    public string $telefono;
    public string $email;
    public string $web;
    public int $estado;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $provincia_id,
        string $nombre,
        string $direccion,
        string $telefono,
        string $email,
        string $web,
        int $estado = 1,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->provincia_id = $provincia_id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->web = $web;
        $this->estado = $estado;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}