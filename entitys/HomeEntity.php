<?php

class HomeEntity 
{

    private $pkHome;
    private $home;

    public function __construct($pkHome, $home) 
    {
        $this->pkHome = $pkHome;
        $this->home = $home;
    }

    public function getPkHome() 
    {
        return $this->pkHome;
    }

    public function setPkHome($pkHome) 
    {
        $this->pkHome = $pkHome;
    }

    public function getHome() 
    {
        return $this->home;
    }

    public function setHome($home) 
    {
        $this->home = $home;
    }
}

?>
