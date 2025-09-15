<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\LogGestor.php

class LogGestor
{
    private string $logDir;
    private string $logFile;

    public function __construct(?string $logDir = null)
    {
        $this->logDir = $logDir ?? __DIR__ . '/../logs';
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
        $this->logFile = $this->logDir . '/busquedaAnalisi_' . date('Y_m_d') . '.log';
    }

    private function write(string $level, string $descripcion): void
    {
        $date = date('Y-m-d H:i:s');
        $line = "[$date] [$level] $descripcion" . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }

    public function info(string $descripcion): void
    {
        $this->write('INFO', $descripcion);
    }

    public function error(string $descripcion): void
    {
        $this->write('ERROR', $descripcion);
    }

    public function warn(string $descripcion): void
    {
        $this->write('WARN', $descripcion);
    }
}