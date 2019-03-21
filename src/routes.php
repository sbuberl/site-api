<?php
use Slim\Http\Request;
use Slim\Http\Response;

use Models\Post;

// Routes

$app->get('/posts', function (Request $request, Response $response) {
    $this->logger->addInfo("Post list");
    $posts = $this->postMapper->fetchAll();
    return $response->withJson($posts);
});

$app->get('/posts/{id}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Get post");
    $id = (int) $args['id'];
    $post = $this->postMapper->loadById($id);
    if($post !== false) {
        return $response->withJson($post);
    } else {
        return $response->withJson("Could not find post", 404);
    }
});

$app->post('/posts', function (Request $request, Response $response) {
    $this->logger->addInfo("Insert post");
    $postVars = $request->getParsedBody();
    $title = $postVars['title'];
    $createdTime = (new \DateTime())->format('Y-m-d H:i:s');
    $content = $postVars['content'];
    $post = new Post(null, $title, $createdTime, $content);
    $this->postMapper->insert($post);
    $postId = $this->db->insert_id();
    $post->setId($postId);
    return $response->withJson($post, 201);
});

$app->put('/posts/{id}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Update post");
    $id = (int) $args['id'];
    $postVars = $request->getParsedBody();
    $post = $this->postMapper->loadById($id);
    if($post !== false) {
        $post->update($postVars);
        $this->postMapper->update($post);
        return $response->withJson($post);
    }
    else {
        return $response->withJson("Could not find post", 404);
    }
});

$app->delete('/posts/{id}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Delete post");
    $id = (int) $args['id'];
    $post = $this->postMapper->loadById($id);
    if($post !== false) {
        $post = $this->postMapper->delete($id);
        return $response->withStatus(204);
    }
    else {
        return $response->withJson("Could not find post", 404);
    }
});
