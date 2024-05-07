<?php

use FastRoute\RouteCollector;

return function(RouteCollector $router) {
    // Define routes
    $router->addRoute('POST', '/PHP-Assignment-master/index.php/api/post', ['controller\PostController', 'createPostAction']);
    $router->addRoute('GET', '/PHP-Assignment-master/index.php/api/post/{id:\d+}', ['controller\PostController', 'getPostAction']);
    
    // routes for movies
    $router->addRoute('POST', '/PHP-Assignment-master/index.php/api/v1/movies', ['controller\MovieController', 'createMovieAction']);
    $router->addRoute('GET', '/PHP-Assignment-master/index.php/api/v1/movies/{id:\d+}', ['controller\MovieController', 'getMovieAction']);
    $router->addRoute('GET', '/PHP-Assignment-master/index.php/api/v1/movies', ['controller\MovieController', 'getAllMoviesAction']);
};