<?php

namespace services;


use Exception;
use models\User;
use PDO;

require_once __DIR__ . '/../models/User.php';

class UsersService
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function authenticate($username, $password): ?User
    {
        $user = $this->findUserByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function findUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userRow) {
            return null;
        }

        $stmtRoles = $this->db->prepare("SELECT roles FROM user_roles WHERE user_id = :user_id");
        $stmtRoles->bindParam(':user_id', $userRow['id']);
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);

        return new User(
            $userRow['id'],
            $userRow['username'],
            $userRow['password'],
            $userRow['nombre'],
            $userRow['apellidos'],
            $userRow['email'],
            $userRow['created_at'],
            $userRow['updated_at'],
            $userRow['is_deleted'],
            $roles
        );
    }
}