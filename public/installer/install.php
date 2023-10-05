<?php
    declare(strict_types = 0);

    $envFile = '../../.env';

    $installed = false;

    if (file_exists($envFile)) {
        $data = file($envFile);

        $envDBParams = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);

                $rowValues = explode('=', $line);

                if (! strlen($line)) {
                    continue;
                }

                if (! in_array($rowValues[0], [
                    'DB_HOST',
                    'DB_DATABASE',
                    'DB_USERNAME',
                    'DB_PASSWORD',
                    'DB_CONNECTION',
                    'DB_PORT',
                    'DB_PREFIX'
                ])) {
                    continue;
                }

                $envDBParams[$rowValues[0]] = $rowValues[1];
            }
        }

        $connection = $envDBParams['DB_CONNECTION'] ?? '';

        if (isset($connection) && $connection == 'mysql') {
            try {
                @$conn = new mysqli(
                    $envDBParams['DB_HOST'] ?? '',
                    $envDBParams['DB_USERNAME'] ?? '',
                    $envDBParams['DB_PASSWORD'],
                    $envDBParams['DB_DATABASE'],
                    (int) $envDBParams['DB_PORT']
                );

                if (! $conn->connect_error) {
                    $prefix = $dbPrefix . 'admins';

                    $result = $conn->query("SELECT id, name FROM $prefix");

                    if ($result) {
                        $installed = true;
                    }
                }

                $conn->close();
            } catch (\Exception $e) {
                $installed = false;
            }
        } else {
            $installed = true;
        }
    }

    if (! $installed) {
        $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $url = explode("/", $host);

        array_pop($url);

        return implode("/", $url) . '/installer';
    } else {
        return null;
    }
?>



