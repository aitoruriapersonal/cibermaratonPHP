<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\Universidad.php

class Universidad
{
    public int $id;
    public string $nombreEus;
    public ?string $siglasEus;
    public string $nombreEsp;
    public ?string $siglasEsp;

    public function __construct(int $id, string $nombreEus, ?string $siglasEus, string $nombreEsp, ?string $siglasEsp)
    {
        $this->id = $id;
        $this->nombreEus = $nombreEus;
        $this->siglasEus = $siglasEus;
        $this->nombreEsp = $nombreEsp;
        $this->siglasEsp = $siglasEsp;
    }
}

class TipoEstudio
{
    public int $id;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}

class Campus
{
    public int $id;
    public int $universidadId;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, int $universidadId, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->universidadId = $universidadId;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}

class Centro
{
    public int $id;
    public int $campusId;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, int $campusId, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->campusId = $campusId;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}

class Grado
{
    public int $id;
    public int $centroId;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, int $centroId, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->centroId = $centroId;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}

class Postgrado
{
    public int $id;
    public int $universidadId;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, int $universidadId, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->universidadId = $universidadId;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}

class Doctorado
{
    public int $id;
    public int $universidadId;
    public string $nombreEus;
    public string $nombreEsp;

    public function __construct(int $id, int $universidadId, string $nombreEus, string $nombreEsp)
    {
        $this->id = $id;
        $this->universidadId = $universidadId;
        $this->nombreEus = $nombreEus;
        $this->nombreEsp = $nombreEsp;
    }
}