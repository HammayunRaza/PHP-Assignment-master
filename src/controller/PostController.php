<?php

namespace controller;

use DI\Container;
use Model\PostModel;

class PostController
{
    /** @var PostModel */
    private $postModel;

    protected $redis;

    public function __construct(Container $container)
    {
        $this->postModel = $container->get('model.post');

        $this->redis = $container->get('redis');
    }

    public function createPostAction()
    {
        return $this->postModel->createPost();
    }

    public function getPostAction($id)
    {
        // echo $id;
        // exit;

        $postKey = 'post:' . $id;
        $cachedPost = $this->redis->get($postKey);
        if ($cachedPost !== null) {
            // Post found in Redis cache, return it
            header('X-Cache-Status: ' . ($cachedPost !== null ? 'HIT' : 'MISS'));
            return unserialize($cachedPost);
        } 
        else {
            // Post not found in Redis, fetch it from the database
            $postData = $this->postModel->getPost($id);
            
            // Store the fetched post data in Redis with a TTL of 1 minute
            $this->redis->setex($postKey, 60, serialize($postData));
            header('X-Cache-Status: ' . 'MISS');
            // Return the fetched post data
            return $postData;
        }
    }
}