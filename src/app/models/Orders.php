<?php

use Phalcon\Mvc\Model;

// user database
class Orders extends Model
{
    public $id;
    public $customer_name;
    public $customer_address;
    public $zipcode;
    public $product_name;
    public $quantity;
}
