<?php

require_once '../vendor/autoload.php';

// Define the routes table
$routes = array(
    '/\//' => array('App\Controllers\HomeController', 'homeAction')
);

// Decide which route to run
foreach ($routes as $url => $action) {
    // See if the route matches the current request
    $matches = preg_match($url, $_SERVER['REQUEST_URI'], $params);

    // If it matches...
    if ($matches > 0) {
        // Run this action, passing the parameters.
        $controller = new $action[0]();
        $controller->{$action[1]}($params);

        break;
    }
}
