<?php
namespace Models;

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

    public function update(array $data)
    {
        $this->title = $data['title'] ?? $this->title;
        $this->content = $data['content'] ?? $this->content;
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

    public function setId($id)
    {
        $this->id = $id;
    }
}
