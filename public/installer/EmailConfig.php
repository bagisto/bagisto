<?php

// array to hold validation errors
$errors = array();

// array to pass back data
$data = array();

// validate the variables
// if any of these variables don't exist, add an error to our $errors array

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

    if (empty($_POST['mail_password']))
        $errors['mail_password'] = 'Please enter the password for this email address.';

    // return a response
    // if there are any errors in our errors array, return a success boolean of false

    if(!empty($errors)) {
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;

    } else {
        // if there are no errors process our form, then return a message
        // show a message of success and provide a true success variable

        // getting env file location
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $envFile = $desiredLocation . '/' . '.env';

        // reading env content
        $data = file($envFile);
        $keyValueData = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    $keyValueData[$rowValues[0]] = $rowValues[1];
                }
            }
        }

        // inserting form data to empty array
        $keyValueData['MAIL_DRIVER']     = $_POST["mail_driver"];
        $keyValueData['MAIL_HOST']       = $_POST["mail_host"];
        $keyValueData['MAIL_ENCRYPTION'] = $_POST["mail_encryption"];

        $keyValueData['MAIL_USERNAME']   = $_POST["mail_username"];
        $keyValueData['MAIL_PASSWORD']   = $_POST["mail_password"];
        $keyValueData['SHOP_MAIL_FROM']  = $_POST["mail_from"];

        // making key/value pair with form-data for env
        $changedData = [];
        foreach ($keyValueData as $key => $value) {
            $changedData[] = $key . '=' . $value;
        }

        // inserting new form-data to env
        $changedData = implode(PHP_EOL, $changedData);
        file_put_contents($envFile, $changedData);

        $data['success'] = true;
        $data['message'] = 'Success!';
    }

    // return all our data to an AJAX call
    echo json_encode($data);