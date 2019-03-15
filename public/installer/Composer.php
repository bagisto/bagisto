<?php

// array to pass back data
$data    = array();

$location = str_replace('\\', '/', getcwd());
$currentLocation = explode("/", $location);
array_pop($currentLocation);
array_pop($currentLocation);
$desiredLocation = implode("/", $currentLocation);

$autoLoadFile = $desiredLocation . '/' . 'vendor' . '/' . 'autoload.php';

if (file_exists($autoLoadFile)) {
   $data['install'] = 0;
} else {
    $data['install'] = 1;
    $data['composer'] = 'Composer dependencies is not Installed.Go to root of project, run "composer install" command to install composer dependencies & refresh page again.';
}


// return a response
//return all our data to an AJAX call

echo json_encode($data);