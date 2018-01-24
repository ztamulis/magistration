<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserEmotion", mappedBy="user")
     *
     * @var UserEmotion[]
     */
    protected $emotions;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return mixed
     */
    public function getEmotions()
    {
        return $this->emotions;
    }

    /**
     * @param mixed $emotions
     * @return User
     */
    public function setEmotions($emotions)
    {
        $this->emotions = $emotions;
        return $this;
    }
}