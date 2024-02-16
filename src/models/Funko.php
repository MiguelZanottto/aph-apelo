<?php

namespace models;

class Funko
{
    public static $IMAGEN_DEFAULT = 'https://via.placeholder.com/150';
    private $id;
    private $nombre;
    private $precio;
    private $stock;
    private $imagen;
    private $createdAt;
    private $updatedAt;
    private $categoriaId;
    private $categoriaNombre;
    private $isDeleted;

    public function __construct($id = null, $nombre = null,  $precio = null, $stock = null, $imagen = null,  $createdAt = null, $updatedAt = null, $categoriaId = null, $categoriaNombre = null, $isDeleted = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->imagen = $imagen ?? self::$IMAGEN_DEFAULT;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoriaId = $categoriaId;
        $this->categoriaNombre = $categoriaNombre;
        $this->isDeleted = $isDeleted;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}