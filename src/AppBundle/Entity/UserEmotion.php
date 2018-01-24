<?php
/**
 * Created by PhpStorm.
 * User: Tamulis
 * Date: 1/21/2018
 * Time: 9:19 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

//use MongoDB\BSON\Timestamp;

/**
 * UserEmotion
 *
 * @ORM\Table(name="userEmotion")
 * @ORM\Entity()
 */
class UserEmotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="emotions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="emotionType", type="string", length=255)
     */
    private $emotion;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserEmotion
     */
    public function setUser(User $user): UserEmotion
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set emotion
     *
     * @param string $emotion
     *
     * @return Entity
     */
    public function setEmotion($emotion)
    {
        $this->emotion = $emotion;

        return $this;
    }

    /**
     * Get emotion
     *
     * @return string
     */
    public function getEmotion(){
        return $this->emotion;
    }

    /**
     * Set date
     *
     * @param DATE $date
     *
     * @return Entity
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

}


