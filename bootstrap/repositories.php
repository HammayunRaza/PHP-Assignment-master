<?php

use DI\Container;
use repository\PostRepository;
use repository\MovieRepository;

// Include all PHP files in the repository directory
foreach (glob(__DIR__ . '/../src/repository/*.php') as $filename) {
    require_once $filename;
}

$appContainer = $app;

// Define the 'repository.post' service
$appContainer->set('repository.post', function (Container $appContainer) {
    return new PostRepository($appContainer->get('database'));
});

// Define the 'repository.movie' service
$appContainer->set('repository.movie', function (Container $appContainer) {
    return new MovieRepository($appContainer->get('database'));
});

// Return the DI container
return $appContainer;
