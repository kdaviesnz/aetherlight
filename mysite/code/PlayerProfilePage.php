<?php

class PlayerProfilePage extends Page {

}

class PlayerProfilePage_Controller extends Page_Controller {

    private $player;

    public function init() {
        
        parent::init();

        // PlayerProfile object
        $this->player = new \scarletcitystudios\aetherlight\PlayerProfile();

        // Check if we're already logged in
        $playerLoaded = $this->player->loadPlayer();

        // Not logged in so try and log in
        if (!is_bool($playerLoaded)) {
            // If we have a username and password then authenticate and load the player fields so that we can display them
            // If we can't load the player fields then redirect user to the login page
            if (!isset($_POST['username']) || !$this->validateUserName($_POST['username']) || !isset($_POST['password']) || !$this->validateUserPassword($_POST['username'])) {
                header('Location:../login/');
                echo '            '; // required to force a redirect
            } else {
                $playerLoaded = $this->player->loadFromAPI($_POST['username'], $_POST['password']);
                if (!is_bool($playerLoaded)) {
                    header('Location:../login/');
                    echo '            '; // required to force a redirect
                }
            }
        }

    }

    public function CharLevelId(){
        return is_object($this->player)?$this->player->getCharLevelId():'';
    }

    public function CharacterName(){
        return is_object($this->player)?$this->player->getCharacterName():'';
    }

    public function CreatedDate(){
        return is_object($this->player)?$this->player->getCreatedDate():'';
    }

    public function CreatedDateFormatted(){
        return is_object($this->player)?$this->player->getCreatedDateFormatted('l jS \of F Y'):'';
    }

    public function EpisodeId(){
        return is_object($this->player)?$this->player->getEpisodeId():'';
    }

    public function Gender(){
        return is_object($this->player)?$this->player->getGender():'';
    }

    public function UserId(){
        return is_object($this->player)?$this->player->getUserId():'';
    }

    public function loginDate(){
        return is_object($this->player)?$this->player->getLoginDate():'';
    }

    public function UserName(){
        return is_object($this->player)?$this->player->getUserName():'';
    }

    public function Avatar(){
        return is_object($this->player)?$this->player->getAvatar():'';
    }

    public function Email(){
        return is_object($this->player)?$this->player->getEmail():'';
    }

    public function TimeSinceCreated(){
        return is_object($this->player)?$this->player->getTimeSinceCreated('hours'):'0 minutes';
    }

    private function validateUserName($username)
    {
        // Verify that the user name is a string and is not longer than 255 characters
        return is_string($username) && strlen($username) < 255;
    }

    private function validateUserPassword($password)
    {
        // Verify that the password is a string and is not longer than 100 characters
        return is_string($password) && strlen($password) < 100;
    }
}