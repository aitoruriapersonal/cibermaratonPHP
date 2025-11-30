<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\ChesscomArchives.php

class ChesscomPlayerGamesArchive
{
    /** @var string[] */
    public array $archives;

    public function __construct(array $archives)
    {
        $this->archives = $archives;
    }

    public static function fromArray(array $data): ChesscomPlayerGamesArchive
    {
        return new ChesscomPlayerGamesArchive(
            isset($data['archives']) && is_array($data['archives']) ? $data['archives'] : []
        );
    }
    
    /**
     * Convierte el objeto a un array asociativo.
     */
    public function toArray(): array
    {
        return [
            'archives' => $this->archives,
        ];
    }
}