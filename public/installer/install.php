<?php declare(strict_types = 0);
    // getting env file
    $location = str_replace('\\', '/', getcwd());
    $currentLocation = explode("/", $location);
    array_pop($currentLocation);
    $desiredLocation = implode("/", $currentLocation);
    $envFile = $desiredLocation . '/' . '.env';

    $installed = false;

    if (file_exists($envFile)) {

        // reading env content
        $data = file($envFile);
        $databaseArray = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CONNECTION','DB_PORT'];
        $key = $value = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    if (in_array($rowValues[0], $databaseArray)) {
                        $key[] = $rowValues[0];
                        $value[] = $rowValues[1];
                    }
                }
            }
        }

        $databaseData = array_combine($key, $value);

        if (isset($databaseData['DB_HOST'])) {
            $servername = $databaseData['DB_HOST'];
        }
        if (isset($databaseData['DB_USERNAME'])) {
            $username   = $databaseData['DB_USERNAME'];
        }
        if (isset($databaseData['DB_PASSWORD'])) {
            $password = $databaseData['DB_PASSWORD'];
        }
        if (isset($databaseData['DB_DATABASE'])) {
            $dbname = $databaseData['DB_DATABASE'];
        }
        if (isset($databaseData['DB_CONNECTION'])) {
            $connection = $databaseData['DB_CONNECTION'];
        }
        if (isset($databaseData['DB_PORT'])) {
            $port = $databaseData['DB_PORT'];
        }

        if (isset($connection) && $connection == 'mysql') {
            @$conn = new mysqli($servername, $username, $password, $dbname, (int)$port);

            if (! $conn->connect_error) {
                // retrieving admin entry
                $sql = "SELECT id, name FROM admins";
                $result = $conn->query($sql);

                if ($result) {
                   $installed = true;
                }
            }

            $conn->close();
        } else {
            $installed = true;
        }
    }

    if (! $installed) {
        // getting url
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $url = explode("/", $actual_link);
        array_pop($url);
        $url = implode("/", $url);
        $url = $url . '/installer';

        return $url;
    } else {
        return null;
    }
?>



