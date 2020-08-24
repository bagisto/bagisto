<?php
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $cssUrl = $actual_link .'/'. 'CSS/style.css';
    $logo =  $actual_link .'/'. 'Images/logo.svg';
    $jsURL = $actual_link .'/'. 'js/script.js';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Bagisto Installer</title>

        <link rel="icon" sizes="16x16" href="Images/favicon.ico">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,500">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

        <link rel="stylesheet" type="text/css" href= "<?php echo $cssUrl; ?> ">
    </head>

    <body>
        <div class="container">
            <div class="initial-display">
                <img class="logo" src= "<?php echo $logo; ?>" >
            </div>
        </div>

        <?php
            // getting env file
            $location = str_replace('\\', '/', getcwd());
            $currentLocation = explode("/", $location);
            array_pop($currentLocation);
            array_pop($currentLocation);
            $desiredLocation = implode("/", $currentLocation);
            $envFile = $desiredLocation . '/' . '.env';

            $installed = false;

            if (file_exists($envFile)) {

                // reading env content
                $data = file($envFile);
                $databaseArray = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CONNECTION','DB_PORT', 'DB_PREFIX'];
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

                    if (!$conn->connect_error) {
                        // retrieving admin entry
                        $prefix = $databaseData['DB_PREFIX'].'admins';
                        $sql = "SELECT id, name FROM $prefix";
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
                // getting url
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $url = explode("/", $actual_link);
                array_pop($url);
                array_pop($url);
                $url = implode("/", $url);
                $url = $url . '/404';

                // redirecting to 404 error page
                header("Location: $url");
            }
        ?>

        <div style="margin-bottom: 5px; margin-top: 30px; text-align: center;">
            <a href="https://bagisto.com/" target="_blank">Bagisto</a> a community project by <a href="https://webkul.com/" target="_blank">Webkul</a>
        </div>

        <script src="<?php echo $jsURL; ?>" ></script>
        
    </body>
</html>
