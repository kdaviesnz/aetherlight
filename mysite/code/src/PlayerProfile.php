<?php

namespace scarletcitystudios\aetherlight; // use vendorname\subnamespace\classname;


/**
 * Class PlayerProfile
 * @package scarletcitystudios\aetherlight
 */
class PlayerProfile
{

    private $charLevelId = '';
    private $characterName = '';
    private $createdDate = '';
    private $email = '';
    private $enabled = false;
    private $episodeId = '';
    private $gender = '';
    private $userId = '';
    private $loginDate = '';
    private $messagingEnabled = false;
    private $userName = '';
    private $avatar = '';
    private $nameModerated = false;
    private $isMod = false;
    private $isMuted = false;
    private $defaultLang = '';
    private $activationCode = '';
    private $httpClient;

    /**
     * PlayerProfile constructor.
     */
    public function __construct()
    {
        $this->httpClient = new \GuzzleHttp\Client(['cookies' => true]);
    }

    /**
     * @param $username
     * @param $password
     * @return bool|\Exception
     */
    public function loadFromAPI($username, $password)
    {
        $playerLoaded = false;

        // Try and authenticate the user
        try {
            $response = $this->httpClient->request('POST', 'https://api.theaetherlight.com/user/login', [
                    'json' => [
                        'username' => $username,
                        'password' => $password
                    ]
                ]
            );
        } catch (\Exception $e) {
            return $e;
        }

        if (204 == $response->getStatusCode() * 1) {

            // Player authenticated, now get the player's details
            try {
                $response = $this->httpClient->GET('https://api.theaetherlight.com/user/');
            } catch (\Exception $e) {
                return $e;
            }

            if (200 == $response->getStatusCode() * 1) {
                // Get the body from the response and load it
                $characterDetails = json_decode((string)$response->getBody());
                $playerLoaded = $this->loadPlayerDetails($characterDetails);
            }

        }

        return $playerLoaded;

    }

    /**
     * @return bool|\Exception
     */
    public function loadPlayer()
    {
        $playerLoaded = false;

        // Check if the player has already been authenticated
        // todo This is not working. It always returns an exception (403) even if we have logged in.
        try {
            $response = $this->httpClient->GET('https://api.theaetherlight.com/user/');
        } catch (\Exception $e) {
            return $e;
        }

        // Player authenticated so load player details
        if (200 == $response->getStatusCode() * 1) {
            // Get the body from the response and load it
            $characterDetails = json_decode((string)$response->getBody());
            $playerLoaded = $this->loadPlayerDetails($characterDetails);
        }

        return $playerLoaded;
    }

    /**
     * @param $characterDetails
     * @return bool
     */
    private function loadPlayerDetails($characterDetails)
    {
        // Load the player's details
        // name moderated and gender must be set before setting the character name
        $this->setNameModerated($characterDetails->nameModerated);
        $this->setGender($characterDetails->gender);
        $this->setCharacterName($characterDetails->characterName);
        $this->setCharLevelId($characterDetails->charLevelId);
        $this->setCreatedDate($characterDetails->createdDate);
        $this->setEmail($characterDetails->email);
        $this->setEnabled($characterDetails->enabled);
        $this->setEpisodeId($characterDetails->episodeId);
        $this->setLoginDate($characterDetails->loginDate);
        $this->setMessagingEnabled($characterDetails->messagingEnabled);
        $this->setAvatar();

        return true;

    }
    /**
     * @return mixed
     */
    public function getCharLevelId()
    {
        return $this->charLevelId;
    }

    /**
     * @param mixed $charLevelId
     */
    public function setCharLevelId($charLevelId)
    {
        $this->charLevelId = $charLevelId;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getMessagingEnabled()
    {
        return $this->messagingEnabled;
    }

    /**
     * @param mixed $messagingEnabled
     */
    public function setMessagingEnabled($messagingEnabled)
    {
        $this->messagingEnabled = $messagingEnabled;
    }

    /**
     * @return mixed
     */
    public function getLoginDate()
    {
        return $this->loginDate;
    }

    /**
     * @param mixed $loginDate
     */
    public function setLoginDate($loginDate)
    {
        $this->loginDate = $loginDate;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getEpisodeId()
    {
        return $this->episodeId;
    }

    /**
     * @param mixed $episodeId
     */
    public function setEpisodeId($episodeId)
    {
        $this->episodeId = $episodeId;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return string
     */
    public function getCreatedDateFormatted($format)
    {
        return date($format, strtotime($this->createdDate));
    }


    /**
     * @return mixed
     */
    public function getCharacterName()
    {
        return $this->characterName;
    }

    /**
     * @param mixed $characterName
     */
    public function setCharacterName($characterName)
    {
        if ($this->nameModerated) {
            $this->characterName = $characterName;
        }
        else{
            list(,$lastName) = explode(' ', $characterName, 2);
            if ($this->gender=='M') {
                $this->characterName = 'Mr ' . $lastName[0];
            }
            else {
                $this->characterName = 'Mrs ' . $lastName[0];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar()
    {
        $this->avatar = $this->gender == 'M'?'https://theaetherlight.com/themes/base/production/images/parentaccountpage/male.png':'https://theaetherlight.com/themes/base/production/images/parentaccountpage/female.png';
    }

    /**
     * @return boolean
     */
    public function isNameModerated()
    {
        return $this->nameModerated;
    }

    /**
     * @param boolean $nameModerated
     */
    public function setNameModerated($nameModerated)
    {
        $this->nameModerated = $nameModerated;
    }

    /**
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    /**
     * @return string
     */
    public function getDefaultLang()
    {
        return $this->defaultLang;
    }

    /**
     * @param string $defaultLang
     */
    public function setDefaultLang($defaultLang)
    {
        $this->defaultLang = $defaultLang;
    }

    /**
     * @return boolean
     */
    public function isIsMuted()
    {
        return $this->isMuted;
    }

    /**
     * @param boolean $isMuted
     */
    public function setIsMuted($isMuted)
    {
        $this->isMuted = $isMuted;
    }

    /**
     * @return boolean
     */
    public function isIsMod()
    {
        return $this->isMod;
    }

    /**
     * @param boolean $isMod
     */
    public function setIsMod($isMod)
    {
        $this->isMod = $isMod;
    }

    /**
     * @return string
     */
    public function getTimeSinceCreated($unit = 'hours')
    {
        $timeInSeconds = time() - strtotime($this->createdDate);
        $timeSinceCreated = null;
        switch ($unit) {
            case 'seconds':
                $timeSinceCreated = $timeInSeconds . ' seconds';
                break;
            case 'minutes':
                $timeSinceCreated = round($timeInSeconds/60) . ' minutes';
                break;
            case 'hours':
                $timeSinceCreated = round($timeInSeconds/60/60) . ' hours';
                break;
            case 'days':
                $timeSinceCreated = round($timeInSeconds/60/60/60) . ' days';
                break;
            case 'months':
                // todo this could be improved
                $timeSinceCreated = round($timeInSeconds/60/60/60/30) . ' months';
                break;
            case 'years':
                $timeSinceCreated = round($timeInSeconds/60/60/60/365) . ' years';
                break;
            default:
                $timeSinceCreated = round($timeInSeconds/60/60) . ' hours';
                break;
        }
        return $timeSinceCreated;
    }



}