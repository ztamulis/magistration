<?php
class Navigacija{

    public $brand;
    public $model;
    public $maps_number;
    public $price;

    function __construct($brand, $model, $maps_number, $price)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->maps_number = $maps_number;
        $this->price = $price;
    }
}
