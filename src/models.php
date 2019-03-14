<?php

class Post implements \JsonSerializable
{
    private $id;
    private $title;
    private $createdTime;
    private $content;

    public function __construct($id, $title, $createdTime, $content)
    {
        $this->id = $id;
        $this->title = $title;
        $this->createdTime = $createdTime;
        $this->content = $content;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    public function getContent()
    {
        return $this->content;
    }
}

class Comment implements \JsonSerializable
{
    private $id;
    private $postId;
    private $title;
    private $createdTime;
    private $content;

    public function __construct($id, $postId, $title, $createdTime, $content)
    {
        $this->id = $id;
        $this->postid = $postId;
        $this->title = $title;
        $this->createdTime = $createdTime;
        $this->content = $content;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    public function getContent()
    {
        return $this->content;
    }
}
