<?php

class Woo_Generate_Feed
{

    public $service;

    public function __construct($feedService, $feedRule)
    {
        $this->service = new $feedService($feedRule);
    }

    public function getProducts()
    {
        return $this->service->returnFinalProduct();
    }
}





