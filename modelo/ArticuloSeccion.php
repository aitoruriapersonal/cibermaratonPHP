<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ArticuloSeccion.php

class ArticuloSeccion
{
    public ?int $id;
    public int $seccion_id;
    public string $titulo;
    public string $descripcion;
    public int $orden;
    public string $fecha_alta;
    public string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $seccion_id,
        string $titulo,
        string $descripcion,
        int $orden = 0,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->seccion_id = $seccion_id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->orden = $orden;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion ?? date('Y-m-d H:i:s');
    }
}