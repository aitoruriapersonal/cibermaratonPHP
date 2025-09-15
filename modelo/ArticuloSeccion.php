<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ArticuloSeccion.php

class ArticuloSeccion
{
    public ?int $id;
    public int $seccion_id;
    public string $tituloES;
    public string $tituloEU;
    public string $descripcionES;
    public string $descripcionEU;
    public int $orden;
    public string $fecha_alta;
    public string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $seccion_id,
        string $tituloES,
        string $tituloEU,
        string $descripcionES,
        string $descripcionEU,
        int $orden = 0,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->seccion_id = $seccion_id;
        $this->tituloES = $tituloES;
        $this->tituloEU = $tituloEU;
        $this->descripcionES = $descripcionES;
        $this->descripcionEU = $descripcionEU;
        $this->orden = $orden;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion ?? date('Y-m-d H:i:s');
    }
}