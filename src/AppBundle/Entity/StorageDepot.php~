<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StorageDepot
 *
 * @ORM\Table(name="storage_depot")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageDepotRepository")
 */
class StorageDepot
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
     * @var string|null
     *
     * @ORM\Column(name="depot_name", type="string", length=255, nullable=true)
     */
    private $depotName;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", nullable = true)
     *
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", nullable = true)
     *
     */
    protected $address;


    /**
     * @var string|null
     *
     * @ORM\Column(name="altitude", type="text", length=255, nullable=true)
     */
    private $altitude;

    /**
     * @var string|null
     *
     * @ORM\Column(name="longitude", type="text", length=255, nullable=true)
     */
    private $longitude;

    public function __construct() {
      // $this->createdAt = new \DateTime;
      // $this->bis = 0;
      // $this->etat = 0;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set altitude.
     *
     * @param string|null $altitude
     *
     * @return StorageDepot
     */
    public function setAltitude($altitude = null)
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * Get altitude.
     *
     * @return string|null
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set longitude.
     *
     * @param string|null $longitude
     *
     * @return StorageDepot
     */
    public function setLongitude($longitude = null)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set depotName.
     *
     * @param string|null $depotName
     *
     * @return StorageDepot
     */
    public function setDepotName($depotName = null)
    {
        $this->depotName = $depotName;

        return $this;
    }

    /**
     * Get depotName.
     *
     * @return string|null
     */
    public function getDepotName()
    {
        return $this->depotName;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return StorageDepot
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return StorageDepot
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }
}
