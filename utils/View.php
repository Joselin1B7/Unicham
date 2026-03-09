
<?php

class View 
{
    public static function render($view, $data = []) 
    {
        extract($data);

        $viewFile = __DIR__ . "/../views/" . $view . ".php";

        if (!file_exists($viewFile)) 
        {
            die("Error: The view <b>$view</b> not found.");
        }

        include $viewFile;
    }
}

?>