<?php
require_once __DIR__ . '/../utils/View.php';

class PruebaController
{
    public function index()
    {
        View::render("indexView", ["home" => 0]);
    }

    public function Aldair(){
          View::render("Aldair/aldairView", ["Aldair" => 0]);
    }

    public function create()
    {
        echo "This method create";
    }

    public function read()
    {
        echo "This method read";
    }

    public function update()
    {
        echo "This method update";
    }

    public function delete()
    {
        echo "This method delete";
    }

    public function other()
    {
        echo "This method other";
    }


}

?>