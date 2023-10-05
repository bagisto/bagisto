<?php
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);

    $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Bagisto Installer</title>

        <link rel="icon" sizes="16x16" href="Images/favicon.ico">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo $host . '/' . 'CSS/style.css'; ?>">
    </head>

    <body>
        <div class="container">
            <div class="initial-display">
                <img class="logo" src="<?php echo $host . '/' . 'Images/logo.svg'; ?>">
            </div>
        </div>

        <?php
            $envFile = '../../.env';

            $installed = false;

            if (file_exists($envFile)) {
                // reading env content
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
                // including classes
                include __DIR__ . '/Classes/Requirement.php';

                // including php files
                include __DIR__ . '/Views/environment.blade.php';
                include __DIR__ . '/Views/migration.blade.php';
                include __DIR__ . '/Views/admin.blade.php';
                include __DIR__ . '/Views/email.blade.php';
                include __DIR__ . '/Views/finish.blade.php';

                // object creation
                $requirement = new Requirement();
                
                echo $requirement->render();
            } else {
                $url = explode("/", $host);

                array_pop($url);

                array_pop($url);

                $url = implode("/", $url) . '/404';

                // redirecting to 404 error page
                header("Location: $url");
            }
        ?>

        <div style="margin-bottom: 5px; margin-top: 30px; text-align: center;">
            <a href="https://bagisto.com/" target="_blank">Bagisto</a> a community project by <a href="https://webkul.com/" target="_blank">Webkul</a>
        </div>

        <script src="<?php echo $host . '/' . 'js/script.js'; ?>"></script>
    </body>
</html>