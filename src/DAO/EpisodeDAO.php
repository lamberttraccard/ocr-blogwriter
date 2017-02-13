<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Episode;
use BlogWriter\Domain\User;

class EpisodeDAO extends DAO {

    /**
     * Returns an episode matching the supplied id.
     *
     * @param integer $id The episode id.
     * @throws \Exception if no matching episode is found
     * @return \BlogWriter\Domain\Episode
     */
    public function find($id)
    {
        $sql = "select * from episodes where id=?";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);

        if ($row) return $this->buildDomainObject($row);

        throw new \Exception("No episode matching id " . $id);
    }

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
        $episodes = [];
        foreach ($result as $row)
        {
            $id = $row['id'];
            $episodes[$id] = $this->buildDomainObject($row);
        }

        return $episodes;
    }

    /**
     * Get the next episode
     *
     * @param $id
     * @return Episode|null
     */
    public function findNext($id)
    {
        $sql = "select * from episodes where id = (select min(id) from episodes where id > ?)";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);

        return $row ? $this->buildDomainObject($row) : null;
    }

    /**
     * Get the previous episode
     *
     * @param $id
     * @return Episode|null
     */
    public function findPrevious($id)
    {
        $sql = "select * from episodes where id = (select max(id) from episodes where id < ?)";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);

        return $row ? $this->buildDomainObject($row) : null;
    }

    /**
     * Saves an episode into the database.
     *
     * @param \BlogWriter\Domain\Episode $episode The episode to save
     */
    public function save(Episode $episode)
    {
        $episodeData = [
            'title'    => $episode->getTitle(),
            'subtitle' => $episode->getSubtitle(),
            'content'  => $episode->getContent()
        ];

        if ($episode->getId())
        {
            // The episode has already been saved : update it
            $this->getDb()->update('episodes', $episodeData, ['id' => $episode->getId()]);
        } else
        {
            // The episode has never been saved : insert it
            $this->getDb()->insert('episodes', $episodeData);
            $id = $this->getDb()->lastInsertId();
            $episode->setId($id);
        }
    }

    /**
     * Mark an episode as read
     *
     * @param Episode $episode
     * @param User $user
     */
    public function markAsRead(Episode $episode, User $user)
    {
        $this->getDb()->insert('user_episode', [
            'user_id'    => $user->getId(),
            'episode_id' => $episode->getId()
        ]);
    }

    /**
     * Mark an episode as unread
     *
     * @param Episode $episode
     * @param User $user
     */
    public function markAsUnread(Episode $episode, User $user)
    {
        $this->getDb()->delete('user_episode', [
            'user_id'    => $user->getId(),
            'episode_id' => $episode->getId()
        ]);
    }

    /**
     * Get the episodes read by a user
     *
     * @param User $user
     * @return array|null
     */
    public function findRead(User $user)
    {
        $sql = "select * from user_episode inner join episodes on id=episode_id where user_id=? order by episode_id asc";
        $result = $this->getDb()->fetchAll($sql, [$user->getId()]);

        if (!$result) return null;

        $episodes = [];
        foreach ($result as $row)
        {
            $id = $row['episode_id'];
            $episodes[$id] = $this->buildDomainObject($row);
        }

        return $episodes;
    }

    /**
     * Removes an episode from the database.
     *
     * @param integer $id The episode id.
     */
    public function delete($id)
    {
        $this->getDb()->delete('episodes', ['id' => $id]);
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