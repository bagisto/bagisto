<?php

$phpBin = PHP_BINDIR . '/php';

$errors = [];

$data = [];

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

/**
 * Return a response if there are any errors in our errors array, return a success boolean of false
 */
if (! empty($errors)) {
    $data['success'] = false;

    $data['errors']  = $errors;
} else {
    $envFile = '../../.env';

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

    if ($connection == 'mysql' ) {
        try {
            @$conn = new mysqli(
                $envDBParams['DB_HOST'] ?? '',
                $envDBParams['DB_USERNAME'] ?? '',
                $envDBParams['DB_PASSWORD'],
                $envDBParams['DB_DATABASE'],
                (int) $envDBParams['DB_PORT']
            );

            if ($conn->connect_error) {
                $data['connection'] = $conn->connect_error;
            }

            $email = $_POST['admin_email'];

            $name = $_POST['admin_name'];

            $password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT, ['cost' => 10]);

            /**
             * Deleting migrated admin
             */
            $conn->query("DELETE FROM admins WHERE id=1");

            /**
             * Query for insertion
             */
            $sql = "INSERT INTO admins (name, email, password, role_id, status)
            VALUES ('".$name."', '".$email."', '".$password."', '1', '1')";

            if ($conn->query($sql) === TRUE) {
                $data['insert_success'] = 'Data Successfully inserted into database';
            } else {
                $data['insert_fail'] = "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } catch (\Exception $e) {
            $data['errors'] = [
                'database_error' => $e->getMessage(),
            ];

            $data['success'] = false;
        }
    } else {
        $data['support_error'] = 'Bagisto currently support MySQL only. Press OK to still continue or change you DB connection to MySQL';
    }

    exec('cd ../.. && '. $phpBin .' artisan storage:link 2>&1');

    /**
     * If there are no errors process our form, then return a message
     * show a message of success and provide a true success variable
     */
    $data['success'] = true;

    $data['message'] = 'Success!';
}

/**
 * Return all our data to an AJAX call
 */
echo json_encode($data);