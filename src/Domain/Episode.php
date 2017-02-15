<?php

namespace BlogWriter\Domain;

use BlogWriter\Constraint\Unique;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Episode style.
     * @var string
     */
    private $style;

    /**
     * Episode created at.
     * @var string
     */
    private $createdAt;

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\Length(array('min' => 4, 'max' => 150)));
        $metadata->addPropertyConstraint('subtitle', new Assert\NotBlank());
        $metadata->addPropertyConstraint('subtitle', new Assert\Length(array('min' => 4, 'max' => 150)));
        /*$metadata->addPropertyConstraint('title', new Unique(array(
            'field' => 'title',
            'entity' => 'episode'
        )));
        $metadata->addPropertyConstraint('subtitle', new Unique(array(
            'field' => 'subtitle',
            'entity' => 'episode'
        )));*/
    }

    /**
     * Episode updated at.
     * @var string
     */
    private $updatedAt;

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

    public function getStyle()
    {
        return $this->style;
    }

    public function setStyle($style)
    {
        $this->style = $style;

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

    public function getCreatedAtAgo()
    {
        return preg_replace('/(depuis) ()/i', 'il y a $2', timeAgoInWords($this->createdAt, 'Europe/Paris', 'fr'));
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReadingTime()
    {
        $wordPerMinute = 300;
        $nbWords = str_word_count($this->content);

        return ceil($nbWords / $wordPerMinute);
    }
}