<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Comment;

class CommentDAO extends DAO
{
    /**
     * @var \BlogWriter\DAO\EpisodeDAO
     */
    private $episodeDAO;

    /**
     * @var \BlogWriter\DAO\UserDAO
     */
    private $userDAO;

    public function setEpisodeDAO(EpisodeDAO $episodeDAO) {
        $this->episodeDAO = $episodeDAO;
    }

    public function setUserDAO($userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id The comment id
     * @throws \Exception if no matching comment is found
     * @return \BlogWriter\Domain\Comment
     */
    public function find($id) {
        $sql = "select * from comments where id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

    /**
     * Returns a list of all comments, sorted by date (most recent first).
     *
     * @return array A list of all comments.
     */
    public function findAll() {
        $sql = "select * from comments order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Removes a comment from the database.
     *
     * @param integer $id The comment id
     */
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('comments', array('id' => $id));
    }


    /**
     * Return a list of all comments for an episode, sorted by date (most recent last).
     *
     * @param integer $episodeId The episode id.
     *
     * @return array A list of all comments for the episode.
     */
    public function findAllByEpisode($episodeId) {
        // The associated episode is retrieved only once
        $episode = $this->episodeDAO->find($episodeId);

        // The episode won't be retrieved during domain objet construction
        $sql = "select id, content, user_id, created_at from comments where episode_id=? order by created_at";
        $result = $this->getDb()->fetchAll($sql, array($episodeId));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $id = $row['id'];
            $comment = $this->buildDomainObject($row);
            // The associated episode is defined for the constructed comment
            $comment->setEpisode($episode);
            $comments[$id] = $comment;
        }
        return $comments;
    }

    /**
     * Saves a comment into the database.
     *
     * @param \BlogWriter\Domain\Comment $comment The comment to save
     */
    public function save(Comment $comment) {
        $commentData = array(
            'episode_id' => $comment->getEpisode()->getId(),
            'user_id' => $comment->getAuthor()->getId(),
            'content' => $comment->getContent(),
        );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('comments', $commentData, array('id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('comments', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

    /**
     * Removes all comments for an episode
     *
     * @param integer $episodeId The id of the episode
     */
    public function deleteAllByEpisode($episodeId) {
        $this->getDb()->delete('comments', array('episode_id' => $episodeId));
    }

    /**
     * Removes all comments for a user
     *
     * @param integer $userId The id of the user
     */
    public function deleteAllByUser($userId) {
        $this->getDb()->delete('comments', array('user_id' => $userId));
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \BlogWriter\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['id']);
        $comment->setContent($row['content']);
        $comment->setCreatedAt($row['created_at']);

        if (array_key_exists('episode_id', $row)) {
            // Find and set the associated episode
            $episodeId = $row['episode_id'];
            $episode = $this->episodeDAO->find($episodeId);
            $comment->setEpisode($episode);
        }
        if (array_key_exists('user_id', $row)) {
            // Find and set the associated author
            $userId = $row['user_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }

        return $comment;
    }
}