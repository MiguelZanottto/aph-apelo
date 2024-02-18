<?php

namespace services;

use models\Categoria;
use PDO;
use Ramsey\Uuid\Uuid;
require_once __DIR__ . '/../models/Categoria.php';

class CategoriasService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Categoria $categoria){
        $categoriaNameExist = $this->findByName($categoria->nombre);
        if($categoriaNameExist){
            return null;
        }

        $sql = "INSERT INTO categorias (id, nombre, created_at, updated_at, is_deleted) 
         VALUES (:id, :nombre, :created_at, :updated_at, :is_deleted)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', Uuid::uuid4(), PDO::PARAM_STR);
        $stmt->bindValue(':nombre',$categoria->nombre, PDO::PARAM_STR);
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':is_deleted', false, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    public function update(Categoria $categoria){
        $categoriaNameExist = $this->findByName($categoria->nombre);

        if($categoriaNameExist && $categoriaNameExist->id != $categoria->id){
            return null;
        }

        $sql = "UPDATE categorias SET
            nombre = :nombre,
            updated_at = :updated_at
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':nombre',$categoria->nombre, PDO::PARAM_STR);
        $categoria->updatedAt= date('Y-m-d H:i:s');
        $stmt->bindValue(':updated_at', $categoria->updatedAt, PDO::PARAM_STR);
        $stmt->bindValue(':id', $categoria->id, PDO::PARAM_STR);
        return $stmt->execute();
    }


    public function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias ORDER BY id ASC");
        $stmt->execute();

        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM categorias
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $categoria = new Categoria(
            $row['id'],
            $row['nombre'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );

        return  $categoria;
    }

    public function findByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE LOWER(nombre) = LOWER(:nombre)");
        $stmt->bindValue(':nombre', $name, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $categoria = new Categoria(
            $row['id'],
            $row['nombre'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        return $categoria;
    }

    public function deleteById($id){
        $sql = "SELECT * 
        FROM funkos f
        WHERE f.categoria_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id',$id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            return null;
        }

        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }
}