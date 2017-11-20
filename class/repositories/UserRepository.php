<?php

/**
 * Class UserRepository
 * @author Alexandre Ribes`
 */
class UserRepository extends Repository
{
    /**
     * @param User $user
     * @return mixed
     */
    public function save( User $user ){
        return empty($user->getId()) ?  $this->insert($user) : $this->update($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function insert( User $user ) {
        $query = "INSERT INTO users SET firstname=:firstname, lastname=:lastname, email=:email, password=:password";
        $prep = $this->connection->prepare($query);
        $prep->execute([
            'firstname'     =>  $user->getFirstName(),
            'lastname'      =>  $user->getLastName(),
            'email'         =>  $user->getEmail(),
            'password'      =>  $user->getPassword(),
        ]);
        return $this->connection->lastInsertId();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function update( User $user ) {
        $query = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email, password=:password WHERE id=:id";
        $prep = $this->connection->prepare($query);
        $prep->execute([
            'firstname'     =>  $user->getFirstName(),
            'lastname'      =>  $user->getLastName(),
            'email'         =>  $user->getEmail(),
            'password'      =>  $user->getPassword(),
        ]);
        return $prep->rowCount();
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email=:email AND WHERE password=:password LIMIT 1";
        $prep = $this->connection->prepare($query);
        $prep->execute([
            'email'     =>  $email,
            'password'  =>  md5($password),
        ]);
        return $prep->rowCount() > 0 ? true : false;
    }
}