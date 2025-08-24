<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Campeonato.php

class Campeonato
{
    public ?int $id;
    public string $tipo_campeonato;
    public string $nombre;
    public string $estado;
    public string $fecha_inicio;
    public string $fecha_fin;
    public ?string $descripcion;
    public ?string $email_1;
    public ?string $email_2;
    public ?string $telefono_1;
    public ?string $telefono_2;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        string $tipo_campeonato,
        string $nombre,
        string $estado,
        string $fecha_inicio,
        string $fecha_fin,
        ?string $descripcion = null,
        ?string $email_1 = null,
        ?string $email_2 = null,
        ?string $telefono_1 = null,
        ?string $telefono_2 = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->tipo_campeonato = $tipo_campeonato;
        $this->nombre = $nombre;
        $this->estado = $estado;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->descripcion = $descripcion;
        $this->email_1 = $email_1;
        $this->email_2 = $email_2;
        $this->telefono_1 = $telefono_1;
        $this->telefono_2 = $telefono_2;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}