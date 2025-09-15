<?php
// Autoloader para las dependencias de Guzzle
spl_autoload_register(function ($class) {
    $prefixes = [
        'GuzzleHttp\\Psr7\\'    => __DIR__ . '/tools/psr7-2.7/src/',
        'GuzzleHttp\\Promise\\' => __DIR__ . '/tools/promises-2.2/src/',
        'GuzzleHttp\\'          => __DIR__ . '/tools/guzzle-7.9/src/',
        'Psr\\Http\Message\\'   => __DIR__ . '/tools/http-message-master/src/',
        'Psr\\Http\Client\\'    => __DIR__ . '/tools/http-client-master/src/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});
?>