<?php

namespace BlogWriter\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use BlogWriter\Domain\User;

class UserDAO extends DAO implements UserProviderInterface {

    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id The user id.
     * @throws \Exception if no matching user is found
     * @return \BlogWriter\Domain\User
     *
     */
    public function find($id)
    {
        $sql = "select * from users where id=?";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);

        if ($row) return $this->buildDomainObject($row);

        throw new \Exception("No user matching id " . $id);
    }

    /**
     * @inheritdoc
     */
    public function findOneBy(array $array)
    {
        $sql = "select * from users where $array[0]=?";
        $row = $this->getDb()->fetchAssoc($sql, [$array[1]]);

        if ($row) return $this->buildDomainObject($row);

        return false;
    }

    /**
     * Returns a list of all users, sorted by role and name.
     *
     * @return array A list of all users.
     */
    public function findAll()
    {
        $sql = "select * from users order by role, username";
        $result = $this->getDb()->fetchAll($sql);

        $entities = [];
        foreach ($result as $row)
        {
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }

        return $entities;
    }

    /**
     * Saves a user into the database.
     *
     * @param \BlogWriter\Domain\User $user The user to save
     */
    public function save(User $user)
    {
        $userData = array(
            'username' => $user->getUsername(),
            'display_name' => $user->getDisplayName(),
            'email' => $user->getEmail(),
            'salt'     => $user->getSalt(),
            'password' => $user->getPassword(),
            'role'     => $user->getRole()
        );

        if ($user->getId())
        {
            // The user has already been saved : update it
            $this->getDb()->update('users', $userData, ['id' => $user->getId()]);
        } else
        {
            // The user has never been saved : insert it
            $this->getDb()->insert('users', $userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Removes a user from the database.
     *
     * @param integer $id The user id.
     */
    public function delete($id)
    {
        $this->getDb()->delete('user_episode', ['user_id' => $id]);
        $this->getDb()->delete('users', ['id' => $id]);
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from users where username=?";
        $row = $this->getDb()->fetchAssoc($sql, [$username]);

        if ($row) return $this->buildDomainObject($row);

        throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class))
        {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'BlogWriter\Domain\User' === $class;
    }

    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \BlogWriter\Domain\User
     */
    protected function buildDomainObject(array $row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setUsername($row['username']);
        $user->setDisplayName($row['display_name']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);
        $user->setSalt($row['salt']);
        $user->setRole($row['role']);

        return $user;
    }
}