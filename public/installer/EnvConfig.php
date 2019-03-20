<?php

// array to hold validation errors
$errors  = array();

// array to pass back data
$data    = array();

// validate the variables
// if any of these variables don't exist, add an error to our $errors array

    if (empty($_POST['app_url']))
        $errors['app_url'] = 'App Url is required.';

    if (empty($_POST['app_name']))
        $errors['app_name'] = 'App Name is required.';

    if (empty($_POST['host_name']))
        $errors['host_name'] = 'Host Name is required.';

    if (empty($_POST['database_name']))
        $errors['database_name'] = 'Database Name is required.';

    if (empty($_POST['user_name']))
        $errors['user_name'] = 'User Name is required.';

    if (empty($_POST['user_password']))
        $errors['user_password'] = 'User Password is required.';

    if (empty($_POST['port_name']))
        $errors['port_name'] = 'Port Name is required.';

    if (preg_match('/\s/', $_POST['app_url']))
        $errors['app_url_space'] = 'There should be no space in App URL ';

    if (preg_match('/\s/', $_POST['app_name']))
        $errors['app_name_space'] = 'There should be no space in App Name.';

    if (preg_match('/\s/', $_POST['host_name']))
        $errors['host_name_space'] = 'There should be no space in Host Name.';

    if (preg_match('/\s/', $_POST['database_name']))
        $errors['database_name_space'] = 'There should be no space in Database Name.';

    if (preg_match('/\s/', $_POST['user_name']))
        $errors['user_name_space'] = 'There should be no space in User Name.';

    if (preg_match('/\s/', $_POST['user_password']))
        $errors['user_password_space'] = 'There should be no space in User Password.';

    if (preg_match('/\s/', $_POST['port_name']))
        $errors['port_name_space'] = 'There should be no space in Port Name.';

//return a response
// if there are any errors in our errors array, return a success boolean of false

    if ( ! empty($errors)) {

        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;

    } else {

        // if there are no errors process our form, then return a message

        // getting env file location
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $envFile = $desiredLocation . '/' . '.env';

        $envExampleFile = $desiredLocation . '/' . '.env.example';

        if (!file_exists($envFile)) {
            if (file_exists($envExampleFile)) {
                copy($envExampleFile, $envFile);
            } else {
                touch($envFile);
            }
        }

        // inserting form data to empty array
        $keyValueData['DB_HOST'] = $_POST["host_name"];
        $keyValueData['DB_DATABASE'] = $_POST["database_name"];
        $keyValueData['DB_USERNAME'] = $_POST["user_name"];
        $keyValueData['DB_PASSWORD'] = $_POST["user_password"];
        $keyValueData['APP_NAME'] = $_POST["app_name"];
        $keyValueData['APP_URL'] = $_POST["app_url"];
        $keyValueData['DB_CONNECTION'] = $_POST["database_connection"];
        $keyValueData['DB_PORT'] = $_POST["port_name"];

        $keyValueData['APP_ENV'] = $_POST["app_env"];
        $keyValueData['APP_KEY'] = $_POST["app_key"];
        $keyValueData['APP_DEBUG'] = $_POST["app_debug"];
        $keyValueData['LOG_CHANNEL'] = $_POST["log_channel"];

        $keyValueData['BROADCAST_DRIVER'] = $_POST["broadcast_driver"];
        $keyValueData['CACHE_DRIVER'] = $_POST["cache_driver"];
        $keyValueData['SESSION_DRIVER'] = $_POST["session_driver"];
        $keyValueData['SESSION_LIFETIME'] = $_POST["session_lifetime"];
        $keyValueData['QUEUE_DRIVER'] = $_POST["queue_driver"];

        $keyValueData['REDIS_HOST'] = $_POST["redis_host"];
        $keyValueData['REDIS_PASSWORD'] = $_POST["redis_password"];
        $keyValueData['REDIS_PORT'] = $_POST["redis_port"];

        $keyValueData['MAIL_DRIVER'] = $_POST["mail_driver"];
        $keyValueData['MAIL_HOST'] = $_POST["mail_host"];
        $keyValueData['MAIL_PORT'] = $_POST["mail_port"];
        $keyValueData['MAIL_USERNAME'] = $_POST["mail_username"];
        $keyValueData['MAIL_PASSWORD'] = $_POST["mail_password"];
        $keyValueData['MAIL_ENCRYPTION'] = $_POST["mail_encryption"];

        $keyValueData['PUSHER_APP_ID'] = $_POST["pusher_app_id"];
        $keyValueData['PUSHER_APP_KEY'] = $_POST["pusher_app_key"];
        $keyValueData['PUSHER_APP_SECRET'] = $_POST["pusher_app_secret"];
        $keyValueData['PUSHER_APP_CLUSTER'] = $_POST["pusher_app_cluster"];

        // making key/value pair with form-data for env
        $changedData = [];
        foreach ($keyValueData as $key => $value) {
            $changedData[] = $key . '=' . $value;
        }

        // inserting new form-data to env
        $changedData = implode(PHP_EOL, $changedData);
        file_put_contents($envFile, $changedData);

        // checking database connection(mysql only)
        if ($_POST["database_connection"] == 'mysql') {
            // Create connection
            @$conn = new mysqli($_POST["host_name"], $_POST["user_name"], $_POST["user_password"], $_POST["database_name"]);

            // check connection
            if ($conn->connect_error) {
                $errors['database_error'] = $conn->connect_error;
                $data['errors'] = $errors;
                $data['success'] = false;
            } else {
                $data['success'] = true;
                $data['message'] = 'Success!';
            }
        } else {
            $data['success'] = true;
            $data['message'] = 'Success!';
        }

        // show a message of success and provide a true success variable
    }

    // return all our data to an AJAX call
    echo json_encode($data);