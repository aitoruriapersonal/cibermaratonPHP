<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Participante.php

class Participante
{
    public ?int $id;
    public int $campeonato_id;
    public string $nombre;
    public string $apellidos;
    public string $dni;
    public string $telefono;
    public string $email;
    public string $fecha_nacimiento;
    public string $nick;
    public int $puntos;
    public ?int $estudio_id;
    public ?int $club_id;
    public string $estado;
    public int $terminado;
    public int $creditos_totales;
    public ?string $fecha_finalizado;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $campeonato_id,
        string $nombre,
        string $apellidos,
        string $dni,
        string $telefono,
        string $email,
        string $fecha_nacimiento,
        string $nick,
        int $puntos = 0,
        ?int $estudio_id = null,
        ?int $club_id = null,
        string $estado = '4-Sin registro chesscom',
        int $terminado = 0,
        int $creditos_totales = 0,
        ?string $fecha_finalizado = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->campeonato_id = $campeonato_id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->dni = $dni;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->nick = $nick;
        $this->puntos = $puntos;
        $this->estudio_id = $estudio_id;
        $this->club_id = $club_id;
        $this->estado = $estado;
        $this->terminado = $terminado;
        $this->creditos_totales = $creditos_totales;
        $this->fecha_finalizado = $fecha_finalizado;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}