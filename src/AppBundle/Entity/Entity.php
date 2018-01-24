<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity
 *
 * @ORM\Table(name="entity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntityRepository")
 */
class Entity
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
     * @var int
     *
     * @ORM\Column(name="brewery_id", type="integer")
     */
    private $breweryId;

    /**
     * @var string
     *
     * @ORM\Column(name="beer", type="string", length=255)
     */
    private $beer;

    /**
     * @var int
     *
     * @ORM\Column(name="latitude", type="integer")
     */
    private $latitude;

    /**
     * @var int
     *
     * @ORM\Column(name="longtitude", type="integer")
     */
    private $longtitude;

    /**
     * @var string
     *
     * @ORM\Column(name="brewery_name", type="string", length=255)
     */
    private $breweryName;


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
     * Set breweryId
     *
     * @param integer $breweryId
     *
     * @return Entity
     */
    public function setBreweryId($breweryId)
    {
        $this->breweryId = $breweryId;

        return $this;
    }

    /**
     * Get breweryId
     *
     * @return int
     */
    public function getBreweryId()
    {
        return $this->breweryId;
    }

    /**
     * Set beer
     *
     * @param string $beer
     *
     * @return Entity
     */
    public function setBeer($beer)
    {
        $this->beer = $beer;

        return $this;
    }

    /**
     * Get beer
     *
     * @return string
     */
    public function getBeer()
    {
        return $this->beer;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     *
     * @return Entity
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return int
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longtitude
     *
     * @param integer $longtitude
     *
     * @return Entity
     */
    public function setLongtitude($longtitude)
    {
        $this->longtitude = $longtitude;

        return $this;
    }

    /**
     * Get longtitude
     *
     * @return int
     */
    public function getLongtitude()
    {
        return $this->longtitude;
    }

    /**
     * Set breweryName
     *
     * @param string $breweryName
     *
     * @return Entity
     */
    public function setBreweryName($breweryName)
    {
        $this->breweryName = $breweryName;

        return $this;
    }

    /**
     * Get breweryName
     *
     * @return string
     */
    public function getBreweryName()
    {
        return $this->breweryName;
    }
}
?>
