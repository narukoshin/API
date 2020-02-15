<?php
    // Loading config
    require_once 'application/config/config.php';

    # Making routes
    $route->routes(function() use ($route){
        $route->add([
            'user/([0-9]+)' => [
                'methods' => ['get', 'put', 'delete']
            ],
            'article' => [
                'methods' => ['get', 'put', 'delete']
            ],
            'user' => [
                'methods' => ['get', 'post']
            ]
        ]);
    });