<?php
    // getting env file
    $location = str_replace('\\', '/', getcwd());
    $currentLocation = explode("/", $location);
    array_pop($currentLocation);
    $desiredLocation = implode("/", $currentLocation);
    $envFile = $desiredLocation . '/' . '.env';

    $installed = false;

    if (file_exists($envFile)) {

        // reading env content
        $str = file_get_contents($envFile);

        // converting env content to key/value pair
        $data = explode(PHP_EOL,$str);
        $databaseArray = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CONNECTION'];

        $key = $value = [];
        if ($data) {
            foreach ($data as $line) {
                $rowValues = explode('=', $line);
                if (count($rowValues) === 2) {
                    if (in_array($rowValues[0], $databaseArray)) {
                        $key[] = $rowValues[0];
                        $value[] = $rowValues[1];
                    }
                }
            }
        }

        $databaseData = array_combine($key, $value);

        // getting database info
        $servername = $databaseData['DB_HOST'];
        $username   = $databaseData['DB_USERNAME'];
        $password   = $databaseData['DB_PASSWORD'];
        $dbname     = $databaseData['DB_DATABASE'];
        $connection = $databaseData['DB_CONNECTION'];

        if ($connection == 'mysql') {
            @$conn = new mysqli($servername, $username, $password, $dbname);

            if (!$conn->connect_error) {
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

    if (!$installed) {
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



