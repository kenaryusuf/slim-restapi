<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Psr7\Stream;
use App\Databse\Db;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/posts', function (Request $request, Response $response) {
        $db = new Db();

        try {
            $db = $db->connect();
            $posts = $db->query("SELECT * FROM posts")->fetchAll(PDO::FETCH_OBJ);

            $jsonString = json_encode($posts, JSON_PRETTY_PRINT);
            $response->getBody()->write($jsonString);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (PDOException $exception) {
            $responseData = json_encode([
                "error" => [
                    "text" => $exception->getMessage(),
                    "code" => $exception->getCode()
                ]
            ]);

            $responseBody = new Stream(fopen('php://memory', 'r+'));
            $responseBody->write($responseData);
            $responseBody->rewind();

            $response = $response->withStatus(500)
                ->withHeader("Content-Type", "application/json")
                ->withBody($responseBody);

            return $response;
        }
    });

    $app->get('/comments', function (Request $request, Response $response) {
        try {
            $db = new \App\Databse\Db();
            $db = $db->connect();
            $comments = $db->query("SELECT * FROM comments")->fetchAll(PDO::FETCH_OBJ);

            $jsonString = json_encode($comments, JSON_PRETTY_PRINT);
            $response->getBody()->write($jsonString);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (PDOException $exception) {
            $responseData = json_encode([
                "error" => [
                    "text" => $exception->getMessage(),
                    "code" => $exception->getCode()
                ]
            ]);

            $responseBody = new Stream(fopen('php://memory', 'r+'));
            $responseBody->write($responseData);
            $responseBody->rewind();

            $response = $response->withStatus(500)
                ->withHeader("Content-Type", "application/json")
                ->withBody($responseBody);

            return $response;
        }
    });

    $app->get('/posts/{id}/comments', function (Request $request, Response $response) {
        $db = new \App\Databse\Db();
        $db = $db->connect();
        try {
            $id = $request->getAttribute("id");
            $comments = $db->query("SELECT * FROM comments WHERE postId = $id")->fetchAll(PDO::FETCH_OBJ);

            $jsonString = json_encode($comments, JSON_PRETTY_PRINT);
            $response->getBody()->write($jsonString);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (PDOException $exception) {
            $responseData = json_encode([
                "error" => [
                    "text" => $exception->getMessage(),
                    "code" => $exception->getCode()
                ]
            ]);

            $responseBody = new Stream(fopen('php://memory', 'r+'));
            $responseBody->write($responseData);
            $responseBody->rewind();

            $response = $response->withStatus(500)
                ->withHeader("Content-Type", "application/json")
                ->withBody($responseBody);

            return $response;
        }
    });
};
