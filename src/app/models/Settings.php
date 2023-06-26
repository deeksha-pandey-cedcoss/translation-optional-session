<?php

use Phalcon\Mvc\Model;

// user database
class Settings extends Model
{
    public $id;
    public $title;
    public $price;
    public $stock;
    public $zipcode;
}
