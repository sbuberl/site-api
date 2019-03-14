<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/posts', function (Request $request, Response $response) {
    $this->logger->addInfo("Post list");
    $result = $this->query("SELECT * FROM posts ORDER BY id");
	$posts = [];
    while($row = $result->fetchAssoc($result))
    {
        $posts[] = new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
    }
    $response = $this->view->render($response, "tickets.phtml", ["tickets" => $tickets, "router" => $this->router]);
    return $response->withJson($posts);
});

$app->get('/posts/:id', function ($id) use($app){
	$this->logger->addInfo("Get post");
    $select = $fsql->prepare("SELECT * FROM posts WHERE id=?");
    $select->bind_param("i", id);
    $select->execute();
    $result = $select->get_result();
    $row = $result->fetchAssoc($result);
    $posts[] = new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
    return $response->withJson($posts);
});
