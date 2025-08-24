<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\BaseCampeonato.php

class BaseCampeonato
{
    public ?int $id;
    public int $campeonato_id;
    public string $titulo;
    public string $fecha_alta;
    public string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $campeonato_id,
        string $titulo,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->campeonato_id = $campeonato_id;
        $this->titulo = $titulo;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion ?? date('Y-m-d H:i:s');
    }
}