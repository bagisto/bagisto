<?php
// array to hold validation errors
$errors = array();

// array to pass back data
$data = array();

// validate the variables

//return a response
// if there are any errors in our errors array, return a success boolean of false

    if (!empty($errors)) {
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

        if (file_exists($envFile)) {
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
            $keyValueData['MAIL_DRIVER'] = "smtp";
            $keyValueData['MAIL_HOST'] = $_POST["mail_hostname"];
            $keyValueData['MAIL_PORT'] = $_POST["mail_port"];
            
            $keyValueData['MAIL_USERNAME'] = $_POST["mail_username"];
            $keyValueData['MAIL_PASSWORD'] = $_POST["mail_password"];
            $keyValueData['MAIL_ENCRYPTION'] = "null";
            
            $keyValueData['SHOP_MAIL_FROM'] = $_POST["mail_from"];
            $keyValueData['ADMIN_MAIL_TO'] = $_POST["mail_to"];
            
            // making key/value pair with form-data for env
            $changedData = [];
            foreach ($keyValueData as $key => $value) {
                $changedData[] = $key . '=' . $value;
            }
            
            // inserting new form-data to env
            $changedData = implode(PHP_EOL, $changedData);
            file_put_contents($envFile, $changedData);
            
            // show a message of success and provide a true success variable
            $data['success'] = true;
            $data['message'] = 'Success!';
        }
    }

    // return all our data to an AJAX call
    echo json_encode($data);