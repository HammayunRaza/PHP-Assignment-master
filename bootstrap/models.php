<?php

use DI\Container;
use Model\PostModel;
use Model\MovieModel;
use repository\PostRepository;
use repository\MovieRepository;

$appContainer = $app;

// Include all PHP files in the model and repository directories
foreach (glob(__DIR__ . '/../src/model/*.php') as $filename) {
    require_once $filename;
}

require __DIR__ . '/repositories.php';

// Define the 'model.post'
$appContainer->set('model.post', function ($appContainer) {
    return new PostModel($appContainer->get('repository.post'));
});

// Define the 'model.movie'
$appContainer->set('model.movie', function ($appContainer) {
    return new MovieModel($appContainer->get('repository.movie'));
});

// Return the DI container
return $appContainer;
