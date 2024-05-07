<?php

namespace Model;

use repository\MovieRepository;

class MovieModel
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getMovie($id)
    {
        $movie = [];
        $result = $this->movieRepository->getMovie($id);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $movie[] = $row;
            }
        }

        return [
            'data' => $movie
        ];
    }


    public function getAllMovies()
    {
        $movie = [];
        $result = $this->movieRepository->getAllMovies();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $movie[] = $row;
            }
        }

        return [
            'data' => $movie
        ];
    }

    public function validateParams($requestBody)
    {
        if (!isset($requestBody['title']) || !isset($requestBody['category']) || !isset($requestBody['author'])) {
            return false;
        }

        return true;
    }

    public function createMovie()
    {
        $requestBody = file_get_contents("php://input");
        $requestBody = json_decode($requestBody, true);

        if (!$this->validateParams($requestBody)) {
            http_response_code(400);

            // Return JSON response with error message
            $response = [
                'error' => 'Bad Request',
                'message' => 'Missing required parameters. Please provide title, category and author value'
            ];

            // Set Content-Type header to indicate JSON response
            header('Content-Type: application/json');

            // Encode the response data into JSON format and echo it
            return $response;
        }

        $result = $this->movieRepository->createMovie($requestBody);

        return [
            'data' => $result ? 'Movie Created Successfully' : "There was an error while creating Movie"
        ];
    }
}