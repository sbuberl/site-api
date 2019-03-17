<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/posts', function (Request $request, Response $response) {
    $this->logger->addInfo("Post list");
    $result = $this->db->query("SELECT * FROM posts ORDER BY id");
    $posts = [];
    while($row = $result->fetchAssoc($result))
    {
        $posts[] = new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
    }
    return $response->withJson($posts, 200);
});

$app->get('/posts/:id', function ($id) use($app){
    $this->logger->addInfo("Get post");
    $select = $this->db->prepare("SELECT * FROM posts WHERE id=?");
    $select->bind_param("i", id);
    $select->execute();
    $result = $select->get_result();
    $row = $result->fetchAssoc($result);
    $post = new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
    return $response->withJson($post);
});

$app->post('/posts', function ($id) use($app){
    $this->logger->addInfo("Insert post");
    $postVars = $request->getParsedBody();
    $title = $postVars['title'];
    $createdTime = date('c', time());
    $content = $postVars['content'];
    $insert = $fsql->prepare("INSERT INTO posts(title,createdTime,content) values(?, ?, ?);");
    $insert->bind_param("sss", $title, $createdTime, $content);
    $insert->execute();
    $postId = $this->db->insert_id();
    $post = new Post($postId, $title, $createdTime, $content);
    return $response->withJson($post);
});

$app->put('/posts/:id', function ($id) use($app){
    $this->logger->addInfo("Update post");
    $postVars = $request->getParsedBody();
    $title = $postVars['title'];
    $content = $postVars['content'];
    $update = $fsql->prepare("UPDATE post SET title = ?, content = ? WHERE id=?;");
    $update->bind_param("ssi", $title, $content, $id);
    $update->execute();

    $select = $this->db->prepare("SELECT createdTime FROM posts WHERE id=?");
    $select->bind_param("i", id);
    $select->execute();
    $result = $select->get_result();
    $row = $result->fetchAssoc($result);
    $post = new Post($id, $title, $row['createdTime'], $content);
    return $response->withJson($post);
});

$app->delete('/posts/:id', function ($id) use($app){
    $this->logger->addInfo("Delete post");
    $delete = $fsql->prepare("DELETE FROM posts WHERE id=?;");
    $delete->bind_param("i", $id);
    $delete->execute();

    return $response->withStatus(204);
});
