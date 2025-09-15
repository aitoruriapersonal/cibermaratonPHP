<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Resultado.php

class Resultado
{
    public ?int $id;
    public int $participante_id;
    public string $username;
    public string $color;
    public int $elo;
    public string $rival;
    public string $color_rival;
    public int $elo_rival;
    public string $resultado;
    public string $resultado_desc;
    public string $fecha_partida;
    public string $url_partida;
    public int $numero_partida;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $participante_id,
        string $username,
        string $color,
        int $elo,
        string $rival,
        string $color_rival,
        int $elo_rival,
        string $resultado,
        string $resultado_desc,
        string $fecha_partida,
        string $url_partida,
        int $numero_partida,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->participante_id = $participante_id;
        $this->username = $username;
        $this->color = $color;
        $this->elo = $elo;
        $this->rival = $rival;
        $this->color_rival = $color_rival;
        $this->elo_rival = $elo_rival;
        $this->resultado = $resultado;
        $this->resultado_desc = $resultado_desc;
        $this->fecha_partida = $fecha_partida;
        $this->url_partida = $url_partida;
        $this->numero_partida = $numero_partida;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}