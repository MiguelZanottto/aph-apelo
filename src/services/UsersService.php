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

    public function saveUser($user){
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE LOWER(username) = LOWER(:username) OR  LOWER(email) = LOWER(:email)");
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return false;
        }

        $query = "SELECT nextval('usuarios_id_seq') AS next_id";
        $result = $this->db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $id = $row['next_id'];

        $sql = "INSERT INTO usuarios (id, nombre, apellidos, email, username, password, created_at, updated_at) VALUES (:id, :nombre, :apellidos, :email, :username, :password, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $user->nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $user->apellidos, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->password, PDO::PARAM_STR);
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->execute();

        $sql = "INSERT INTO user_roles (user_id, roles) VALUES (:user_id, :roles)";
        $stmt = $this->db->prepare($sql);
        foreach ($user->roles as $role){
            $stmt->bindValue(':user_id', $id);
            $stmt->bindValue(':roles', $role, PDO::PARAM_STR);
            $stmt->execute();
        }
        return true;
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