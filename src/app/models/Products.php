<?php

use Phalcon\Mvc\Model;

// user database
class Products extends Model
{
    public $id;
    public $name;
    public $description;
    public $tags;
    public $price;
    public $stock;
}
