<?php
namespace Models;
use FSQL\Environment;

class PostMapper
{
    protected $db;

    public function __construct(Environment $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all posts
     *
     * @return [Post]
     */
    public function fetchAll()
    {
        $result = $this->db->query("SELECT * FROM posts ORDER BY createdTime DESC");
        $posts = [];
        while($row = $result->fetchAssoc($result))
        {
            $posts[] = new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
        }
        return $posts;
    }

    /**
     * Load a single post
     *
     * @return Post|false
     */
    public function loadById($id)
    {
        $select = $this->db->prepare("SELECT * FROM posts WHERE id=?");
        $select->bind_param("i", $id);
        $select->execute();
        $result = $select->get_result();
        $row = $result->fetchAssoc($result);
        if ($row) {
            return new Post($row['id'], $row['title'], $row['createdTime'], $row['content']);
        }
        return false;
    }
    /**
     * Create a post
     *
     * @return Post
     */
    public function insert(Post $post)
    {
        $title = $post->getTitle();
        $createdTime = $post->getCreatedTime();
        $content = $post->getContent();
        $insert = $this->db->prepare("INSERT INTO posts(title,createdTime,content) values(?, ?, ?);");
        $insert->bind_param("sss", $title, $createdTime, $content);
        $insert->execute();
        $postId = $this->db->insert_id();
        return new Post($postId, $title, $createdTime, $content);
    }
    /**
     * Update a post
     *
     * @return Post
     */
    public function update(Post $post)
    {
        $title = $post->getTitle();
        $content = $post->getContent();
        $update = $this->db->prepare("UPDATE post SET title = ?, content = ? WHERE id=?;");
        $update->bind_param("ssi", $title, $content, $id);
        $update->execute();
        return $post;
    }
    /**
     * Delete a post
     *
     * @param $id       Id of post to delete
     * @return boolean  True if there was a post to delete
     */
    public function delete($id)
    {
        $delete = $this->db->prepare("DELETE FROM posts WHERE id=?;");
        $delete->bind_param("i", $id);
        $delete->execute();
        return (bool)$this->db->affected_rows();
    }
}
