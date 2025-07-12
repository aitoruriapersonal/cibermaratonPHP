<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\Logger.php

class Logger
{
    private string $logFile;

    public function __construct(string $logFile = __DIR__ . '/../logs/app.log')
    {
        $this->logFile = $logFile;
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    public function info(string $message): void
    {
        $this->writeLog('INFO', $message);
    }

    public function warn(string $message): void
    {
        $this->writeLog('WARN', $message);
    }

    public function error(string $message): void
    {
        $this->writeLog('ERROR', $message);
    }

    private function writeLog(string $level, string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date][$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }
}