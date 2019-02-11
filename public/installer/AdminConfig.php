<?php

// array to hold validation errors
$errors  = array();

// array to pass back data
$data    = array();

// validate the variables
// if any of these variables don't exist, add an error to our $errors array

    if (empty($_POST['admin_email']))
        $errors['admin_email'] = 'Email is required.';

    if (empty($_POST['admin_name']))
        $errors['admin_name'] = 'Name is required.';

    if (empty($_POST['admin_password']))
        $errors['admin_password'] = 'Password is required.';

    if (empty($_POST['admin_re_password']))
        $errors['admin_re_password'] = 'Re-Password is required.';

    if ($_POST['admin_re_password'] !== $_POST['admin_password'])
        $errors['password_match'] = 'Password & Re-Password did not match';

// return a response
// if there are any errors in our errors array, return a success boolean of false

    if ( ! empty($errors)) {
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;

    } else {
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $envFile = $desiredLocation . '/' . '.env';

        // reading env content
        $str= file_get_contents($envFile);

        // converting env content to key/value pair
        $data = explode(PHP_EOL,$str);
        $databaseArray = ['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_CONNECTION'];

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

        if ($connection == 'mysql' ) {
            // Create connection
            @$conn = new mysqli($servername, $username, $password, $dbname);

            // check connection
            if ($conn->connect_error) {
                $data['connection'] = $conn->connect_error;
            }

            $email = $_POST['admin_email'];
            $name = $_POST['admin_name'];
            $password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT, ['cost' => 10]);

            // Deleting migrated admin
            $deleteAdmin = "DELETE FROM admins WHERE id=1";
            $conn->query($deleteAdmin);

            // query for insertion
            $sql = "INSERT INTO admins (name, email, password, role_id, status)
            VALUES ('".$name."', '".$email."', '".$password."', '1', '1')";

            if ($conn->query($sql) === TRUE) {
                $data['insert_success'] = 'Data Successfully inserted into database';
            } else {
                $data['insert_fail'] = "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            $data['support_error'] = 'Bagisto currently support MySQL only. Press OK to still continue or change you DB connection to MySQL';
        }

        // if there are no errors process our form, then return a message
        // show a message of success and provide a true success variable
        $data['success'] = true;
        $data['message'] = 'Success!';
    }

    // return all our data to an AJAX call
    echo json_encode($data);