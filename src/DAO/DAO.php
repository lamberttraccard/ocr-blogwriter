<?php

namespace BlogWriter\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * Grants access to the database connection object
     *
     * @return \Doctrine\DBAL\Connection The database connection object
     */
    protected function getDb() {
        return $this->db;
    }

    /**
     * Find a record from a key => value pair
     *
     * @param array $array
     * @return mixed
     */
    abstract public function findOneBy(array $array);

    /**
     * Builds a domain object from a DB row.
     *
     * @param array
     */
    protected abstract function buildDomainObject(array $row);
}