<?php
// Import the necessary classes
use Predis\Client;

$appContainer = $app;

// reids connection parameters

    // Create a new Redis client instance
    $redis = new Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ]);

return $redis;

