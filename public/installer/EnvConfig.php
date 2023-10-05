<?php

// array to hold validation errors
$errors = [];

// array to pass back data
$data = [];

// validate the variables
// if any of these variables don't exist, add an error to our $errors array
if (empty($_POST['app_name']))
    $errors['app_name'] = 'App Name is required.';

if (empty($_POST['app_url']))
    $errors['app_url'] = 'App Url is required.';

if (empty($_POST['app_currency']))
    $errors['app_currency'] = 'The application currency is required.';

if (empty($_POST['app_locale']))
    $errors['app_locale'] = 'Please select a locale for the application.';

if (empty($_POST['app_timezone']))
    $errors['app_timezone'] = 'The application timezone is required.';

if (empty($_POST['host_name']))
    $errors['host_name'] = 'Host Name is required.';

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

if (preg_match('/\s/', $_POST['database_prefix']))
    $errors['database_prefix_space'] = 'There should be no space in Database Prefix.';

if (preg_match('/\s/', $_POST['user_name']))
    $errors['user_name_space'] = 'There should be no space in User Name.';

if (preg_match('/\s/', $_POST['user_password']))
    $errors['user_password_space'] = 'There should be no space in User Password.';

if (preg_match('/\s/', $_POST['port_name']))
    $errors['port_name_space'] = 'There should be no space in Port Name.';

// return a response
// if there are any errors in our errors array, return a success boolean of false

if (! empty($errors)) {
    // if there are items in our errors array, return those errors
    $data['success'] = false;
    
    $data['errors']  = $errors;
} else {
    $envFile = '../../.env';

    if (! file_exists($envFile)) {
        if (file_exists('../../.env.example')) {
            copy('../../.env.example', $envFile);
        } else {
            touch($envFile);
        }
    }

    $data = file($envFile);

    $envDBParams = [];

    if ($data) {
        foreach ($data as $line) {
            $line = preg_replace('/\s+/', '', $line);

            $rowValues = explode('=', $line);

            if (! strlen($line)) {
                continue;
            }

            $envDBParams[$rowValues[0]] = $rowValues[1];
        }
    }

    /**
     * Update params with form-data
     */
    $envDBParams['DB_HOST'] = $_POST["host_name"];
    $envDBParams['DB_DATABASE'] = $_POST["database_name"];
    $envDBParams['DB_PREFIX'] = $_POST["database_prefix"];
    $envDBParams['DB_USERNAME'] = $_POST["user_name"];
    $envDBParams['DB_PASSWORD'] = $_POST["user_password"];
    $envDBParams['APP_NAME'] = $_POST["app_name"];
    $envDBParams['APP_URL'] = $_POST["app_url"];
    $envDBParams['APP_CURRENCY'] = $_POST["app_currency"];
    $envDBParams['APP_LOCALE'] = $_POST["app_locale"];
    $envDBParams['APP_TIMEZONE'] = $_POST["app_timezone"];
    $envDBParams['DB_CONNECTION'] = $_POST["database_connection"];
    $envDBParams['DB_PORT'] = $_POST["port_name"];

    /**
     * Making key/value pair with form-data for env
     */
    $updatedEnvDBParams = [];

    foreach ($envDBParams as $key => $value) {
        $updatedEnvDBParams[] = $key . '=' . $value;
    }

    /**
     * Inserting new form-data to env
     */
    $updatedEnvDBParams = implode(PHP_EOL, $updatedEnvDBParams);

    file_put_contents($envFile, $updatedEnvDBParams);

    /**
     * Checking database connection(mysql only)
     */
    if ($envDBParams['DB_CONNECTION'] == 'mysql') {
        @$conn = new mysqli(
            $envDBParams['DB_HOST'] ?? '',
            $envDBParams['DB_USERNAME'] ?? '',
            $envDBParams['DB_PASSWORD'],
            $envDBParams['DB_DATABASE'],
            (int) $envDBParams['DB_PORT']
        );

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
}

/**
 * Return all our data to an AJAX call
 */
echo json_encode($data);
