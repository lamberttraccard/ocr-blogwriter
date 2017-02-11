<?php

namespace BlogWriter\Domain;

class Episode {

    /**
     * Episode id.
     * @var integer
     */
    private $id;

    /**
     * Episode title.
     * @var string
     */
    private $title;

    /**
     * Episode subtitle.
     * @var string
     */
    private $subtitle;

    /**
     * Episode content.
     * @var string
     */
    private $content;

    /**
     * Episode created at.
     * @var string
     */
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}