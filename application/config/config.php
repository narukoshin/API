<?php
    # Setting headers
    header('Content-Type: application/json');
    header('Accept-Encoding: gzip');
    header('Content-Encoding: gzip');

    # Loading classes
    require_once getcwd() . '/application/classes/database.php';
    require_once getcwd() . '/application/classes/router.php';
    require_once getcwd() . '/application/classes/user.php';

    # Connecting to the database
    $database = new database;
    $db = $database->connect([
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'base' => 'apiv1'
    ]);

    // Setting route
    $route = new router($db);