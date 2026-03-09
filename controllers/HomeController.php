<?php

require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../interfaces/HomeInterface.php';
require_once __DIR__ . '/../entitys/HomeEntity.php';
require_once __DIR__ . '/../models/HomeModel.php';

class HomeController implements HomeInterface
{
    protected $table = "tbl_home";

    public function index() 
	{
        $homeEntity = new HomeEntity("152","TheHommy195");
        $homeModel = new HomeModel();

        $home = array
        (
            'pkHome' => $homeEntity->getPkHome(),
            'home'   => $homeEntity->getHome()
        );

        //$homeModel->save($this->table, $homeEntity); 
        //$homeModel->insert($this->table, $home);
        $homeModel->modify($this->table, $home); 
        //$homeModel->edit($this->table, $home, "pkHome"); 
    
        //$users = ["pkHome" => $homeEntity->getPkHome(), "home" => $homeEntity->getHome()];

        View::render("home/indexView", ["home" => $home]);
    }

    public function create() 
	{
        echo "Method create";
    }

    public function read() 
	{
        echo "Method read";
    }

    public function update($id = null) 
	{
        echo "Method update ".$id;
    }

    public function delete($id = null) 
	{
        echo "Method delete ".$id;
    }
}
?>
