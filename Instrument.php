<?php
class Instrument{
public $name;
public $retail;
public $finalPrice;
public $discount;

public function __construct($name, $retail, $finalPrice, $discount){
    $this->name = $name;
    $this->retail = $retail;
    $this->finalPrice = $finalPrice;
    $this->discount = $discount; 
}
}