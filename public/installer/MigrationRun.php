<?php

$data    = array();      // array to pass back data

// run command on terminal ===========================================================

    $command = 'cd ../.. && php artisan migrate';

    $last_line = exec($command, $data['migrate'], $data['results']);

// return a response ===========================================================

//return all our data to an AJAX call
echo json_encode($data);