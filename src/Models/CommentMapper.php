<?php
namespace Models;
use FSQL\Environment;

class CommentMapper
{
    protected $db;

    public function __construct(Environment $db)
    {
        $this->db = $db;
    }

    /**
     * Fetch all comments for a post7
     *
     * @return [Comment]
     */
    public function fetchAllByPost($postId)
    {
        $result = $this->db->query("SELECT * FROM comments WHERE postId=? ORDER BY createdTime DESC");
        $comments = [];
        while($row = $result->fetchAssoc($result))
        {
            $comments[] = new Comment($row['id'], $row['postId'], $row['title'], $row['createdTime'], $row['content']);
        }
        return $comments;
    }

    /**
     * Load a single comment
     *
     * @return Comment|false
     */
    public function loadById($postId, $id)
    {
        $select = $this->db->prepare("SELECT * FROM comments WHERE id=? AND postId=");
        $select->bind_param("ii", $id, $postId);
        $select->execute();
        $result = $select->get_result();
        $row = $result->fetchAssoc($result);
        if ($row) {
            return new Comment($row['id'], $row['postId'], $row['title'], $row['createdTime'], $row['content']);
        }
        return false;
    }

    /**
     * Create a comment
     *
     * @return Comment
     */
    public function insert(Comment $comment)
    {
        $postId = $comment->getPostId();
        $title = $comment->getTitle();
        $createdTime = $comment->getCreatedTime();
        $content = $comment->getContent();
        $insert = $this->db->prepare("INSERT INTO posts(title,createdTime,content) values(?, ?, ?);");
        $insert->bind_param("sss", $title, $createdTime, $content);
        $insert->execute();
        $id = $this->db->insert_id();
        return new Comment($id, $postId, $title, $createdTime, $content);
    }

    /**
     * Delete a comment
     *
     * @param $id       Id of post to delete
     * @return boolean  True if there was a post to delete
     */
    public function delete($id)
    {
        $delete = $this->db->prepare("DELETE FROM comments WHERE id=?;");
        $delete->bind_param("i", $id);
        $delete->execute();
        return (bool)$this->db->affected_rows();
    }
}
