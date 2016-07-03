<?php

class LoginPage extends Page {

}

class LoginPage_Controller extends Page_Controller {

    private $error = '';

    public function init()
    {

        parent::init();
        if (isset($_GET['error'])) {
            $this->error = $_GET['error'];
        }

    }

    /**
     * @return string
     */
    public function Error()
    {
        return $this->error;
    }
    
}