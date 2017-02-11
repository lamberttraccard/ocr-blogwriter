<?php

namespace BlogWriter\Domain;

class Comment {

    /**
     * Comment id
     * @var integer
     */
    private $id;

    /**
     * Comment content
     * @var string
     */
    private $content;

    /**
     * Comment author
     * @var User
     */
    private $author;

    /**
     * Associated Episode
     * @var Episode
     */
    private $episode;

    /**
     * Comment created at.
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

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;

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

    public function getEpisode()
    {
        return $this->episode;
    }

    public function setEpisode(Episode $episode)
    {
        $this->episode = $episode;

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