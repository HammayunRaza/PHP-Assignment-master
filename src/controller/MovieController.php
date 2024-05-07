<?php

namespace controller;

use DI\Container;
use Model\MovieModel;

class MovieController
{
    /** @var MovieModel */
    private $movieModel;

    protected $redis;

    public function __construct(Container $container)
    {
        $this->movieModel = $container->get('model.movie');

        $this->redis = $container->get('redis');
    }

    public function createMovieAction()
    {
        return $this->movieModel->createMovie();
    }

    public function getMovieAction($id)
    {
        // echo $id;
        // exit;

        $movieKey = 'movie:' . $id;
        $cachedMovie = $this->redis->get($movieKey);
        if ($cachedMovie !== null) {
            //Movie found in Redis cache, return it
            header('X-Cache-Status: ' . ($cachedMovie !== null ? 'HIT' : 'MISS'));
            return unserialize($cachedMovie);
        } 
        else {
            // Movie not found in Redis, fetch it from the database
            $movieData = $this->movieModel->getMovie($id);
            
            // Store the fetched movie data in Redis with a TTL of 1 minute
            $this->redis->setex($movieKey, 60, serialize($movieData));
            header('X-Cache-Status: ' . 'MISS');
            // Return the fetched movie data
            return $movieData;
        }
    }


    public function getAllMoviesAction(){
        $moviesKey = 'allMovies';
        $cachedMovies = $this->redis->get($moviesKey);
        if ($cachedMovies !== null) {
            //Movies found in Redis cache, return it
            header('X-Cache-Status: ' . ($cachedMovies !== null ? 'HIT' : 'MISS'));
            return unserialize($cachedMovies);
        } 
        else {
            // Movies not found in Redis, fetch it from the database
            $moviesData = $this->movieModel->getAllMovies();
            
            // Store the fetched movies data in Redis with a TTL of 1 minute
            $this->redis->setex($moviesKey, 60, serialize($moviesData));
            header('X-Cache-Status: ' . 'MISS');
            // Return the fetched post data
            return $moviesData;
        }

    }
}