<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Episode;

class EpisodeDAO extends DAO {

    /**
     * Return a list of all episodes, sorted by date (oldest first).
     *
     * @return array A list of all episodes.
     */
    public function findAll()
    {
        $sql = "select * from episodes order by created_at asc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $episodes = array();
        foreach ($result as $row)
        {
            $id = $row['id'];
            $episodes[$id] = $this->buildDomainObject($row);
        }

        return $episodes;
    }

    /**
     * Returns an episode matching the supplied id.
     *
     * @param integer $id The episode id.
     * @throws \Exception if no matching episode is found
     *
     * @return \BlogWriter\Domain\Episode
     */
    public function find($id)
    {
        $sql = "select * from episodes where id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No episode matching id " . $id);
    }

    /**
     * Saves an episode into the database.
     *
     * @param \BlogWriter\Domain\Episode $episode The episode to save
     */
    public function save(Episode $episode) {
        $episodeData = array(
            'title' => $episode->getTitle(),
            'subtitle' => $episode->getSubtitle(),
            'content' => $episode->getContent(),
        );

        if ($episode->getId()) {
            // The episode has already been saved : update it
            $this->getDb()->update('episodes', $episodeData, array('id' => $episode->getId()));
        } else {
            // The episode has never been saved : insert it
            $this->getDb()->insert('episodes', $episodeData);
            // Get the id of the newly created episode and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $episode->setId($id);
        }
    }

    /**
     * Removes an episode from the database.
     *
     * @param integer $id The episode id.
     */
    public function delete($id) {
        // Delete the episode
        $this->getDb()->delete('episodes', array('id' => $id));
    }

    /**
     * Creates an Episode object based on a DB row.
     *
     * @param array $row The DB row containing Episode data.
     * @return \BlogWriter\Domain\Episode
     */
    protected function buildDomainObject(array $row)
    {
        $episode = new Episode();
        $episode->setId($row['id']);
        $episode->setTitle($row['title']);
        $episode->setSubtitle($row['subtitle']);
        $episode->setContent($row['content']);
        $episode->setCreatedAt($row['created_at']);

        return $episode;
    }
}