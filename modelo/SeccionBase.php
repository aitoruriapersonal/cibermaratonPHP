<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\SeccionBase.php

class SeccionBase
{
    public ?int $id;
    public int $base_id;
    public string $tituloES;
    public string $tituloEU;
    public int $orden;
    public string $fecha_alta;
    public string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $base_id,
        string $tituloES,
        string $tituloEU,
        int $orden = 0,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->base_id = $base_id;
        $this->tituloES = $tituloES;
        $this->tituloEU = $tituloEU;
        $this->orden = $orden;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion ?? date('Y-m-d H:i:s');
    }
}