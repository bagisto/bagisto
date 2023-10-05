<?php

$errors = [];

$data = [];

/**
 * Validate the variables
 * 
 * If any of these variables don't exist, add an error to our $errors array
 */
if (empty($_POST['mail_driver']))
    $errors['mail_driver'] = 'Please select the mail driver.';

if (empty($_POST['mail_host']))
    $errors['mail_host'] = 'Please enter the hostname for this outgoing mail server.';

if (empty($_POST['mail_port']))
    $errors['mail_port'] = 'Please enter the port for this outgoing mail server.';

// if (empty($_POST['mail_encryption']))
//     $errors['mail_encryption'] = 'Please select the encryption method for this outgoing mail server.';

if (empty($_POST['mail_from']))
    $errors['mail_from'] = 'Please enter the email address for this store.';

if (empty($_POST['mail_username']))
    $errors['mail_username'] = 'Please enter your email address or username.';

if (preg_match('/\s/', $_POST['mail_username']))
    $errors['mail_username'] = 'There should be no space in Username.';

if (empty($_POST['mail_password']))
    $errors['mail_password'] = 'Please enter the password for this email address.';

/**
 * Return a response if there are any errors in our errors array, return a success boolean of false
 */
if(! empty($errors)) {
    $data['success'] = false;

    $data['errors']  = $errors;
} else {
    $envFile = '../../.env';

    $data = file($envFile);

    $envEmailParams = [];

    if ($data) {
        foreach ($data as $line) {
            $line = preg_replace('/\s+/', '', $line);

            $rowValues = explode('=', $line);

            if (! strlen($line)) {
                continue;
            }

            $envEmailParams[$rowValues[0]] = $rowValues[1];
        }
    }

    /**
     * Inserting form data to empty array
     */
    $envEmailParams['MAIL_DRIVER'] = $_POST["mail_driver"];
    $envEmailParams['MAIL_HOST'] = $_POST["mail_host"];
    $envEmailParams['MAIL_PORT'] = $_POST["mail_port"];
    $envEmailParams['MAIL_ENCRYPTION'] = $_POST["mail_encryption"];

    $envEmailParams['MAIL_USERNAME'] = $_POST["mail_username"];
    $envEmailParams['MAIL_PASSWORD'] = $_POST["mail_password"];
    $envEmailParams['SHOP_MAIL_FROM'] = $_POST["mail_from"];

    /**
     * Making key/value pair with form-data for env
     */
    $updatedEnvEmailParams = [];

    foreach ($envEmailParams as $key => $value) {
        $updatedEnvEmailParams[] = $key . '=' . $value;
    }

    /**
     * Inserting new form-data to env
     */
    $updatedEnvEmailParams = implode(PHP_EOL, $updatedEnvEmailParams);

    file_put_contents($envFile, $updatedEnvEmailParams);

    $data['success'] = true;

    $data['message'] = 'Success!';
}

/**
 * Return all our data to an AJAX call
 */
echo json_encode($data);