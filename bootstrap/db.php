<?php

use DI\Container;

$appContainer = $app;

$appContainer->set('database', function (Container $container) {
    // MySQL connection parameters
    $host = 'localhost'; // Hostname
    $username = 'root'; // MySQL username
    $password = ''; // MySQL password
    $dbname = 'php_assignment'; // Database name

    // Create a new mysqli connection
    $mysqli = new mysqli($host, $username, $password, $dbname);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Set charset to UTF-8
    $mysqli->set_charset("utf8mb4");

    // Return the mysqli connection
    return $mysqli;
});

// Return the DI container
return $appContainer;
