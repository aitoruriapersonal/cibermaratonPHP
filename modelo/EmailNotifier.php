<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\EmailNotifier.php

class EmailNotifier
{
    private string $from;
    private string $fromName;

    public function __construct(string $from, string $fromName = '')
    {
        $this->from = $from;
        $this->fromName = $fromName;
    }

    public function send(string $to, string $subject, string $message, array $headers = []): bool
    {
        $defaultHeaders = [
            'From' => $this->fromName ? "{$this->fromName} <{$this->from}>" : $this->from,
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8'
        ];

        foreach ($headers as $key => $value) {
            $defaultHeaders[$key] = $value;
        }

        $headersString = '';
        foreach ($defaultHeaders as $key => $value) {
            $headersString .= "$key: $value\r\n";
        }

        return mail($to, $subject, $message, $headersString);
    }
}