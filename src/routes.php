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

$app->get('/posts/{postId}/comments', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Get comments");
    $postId = (int) $args['postId'];
    $comments = $this->commentMapper->fetchAllByPost($postId);
    return $response->withJson($comments);
});

$app->get('/posts/{postId}/comments/{id}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Get comment");
    $id = (int) $args['id'];
    $postId = (int) $args['postId'];
    $comment = $this->commentMapper->loadById($postId, $id);
    if($comment !== false) {
        return $response->withJson($comment);
    } else {
        return $response->withJson("Could not find comment", 404);
    }
});

$app->post('/posts/{postId}/comments', function (Request $request, Response $response, $args) {
    t$this->logger->addInfo("Insert comment");
    $postVars = $request->getParsedBody();
    $postId = (int) $args['postId'];
    $title = $postVars['title'];
    $createdTime = (new \DateTime())->format('Y-m-d H:i:s');
    $content = $postVars['content'];
    $comment = new Comment(null, $postId, $title, $createdTime, $content);
    $this->commentMapper->insert($comment);
    $id = $this->db->insert_id();
    $comment->setId($postId);
    return $response->withJson($comment, 201);
});

$app->delete('/posts/{postId}/comments/{id}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Delete post");
    $id = (int) $args['id'];
    $postId = (int) $args['postId'];
    $post = $this->commentMapper->loadById($id);
    if($post !== false) {
        $post = $this->commentMapper->delete($id);
        return $response->withStatus(204);
    }
    else {
        return $response->withJson("Could not find comment", 404);
    }
});
