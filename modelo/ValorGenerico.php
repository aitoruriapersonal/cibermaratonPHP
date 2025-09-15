<?php
class ValorGenerico
{
    public ?int $id;
    public string $tipo;
    public int $valor;
    public string $nombre_esp;
    public string $nombre_eus;
    public string $descripcion_esp;
    public string $descripcion_eus;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        string $tipo,
        int $valor,
        string $nombre_esp,
        string $nombre_eus,
        string $descripcion_esp,
        string $descripcion_eus,
        string $fecha_alta,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->valor = $valor;
        $this->nombre_esp = $nombre_esp;
        $this->nombre_eus = $nombre_eus;
        $this->descripcion_esp = $descripcion_esp;
        $this->descripcion_eus = $descripcion_eus;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}