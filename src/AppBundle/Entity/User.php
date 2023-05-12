<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use SbS\AdminLTEBundle\Model\UserInterface as ThemeUser;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser implements ThemeUser , ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cin", type="string", length=255, nullable=true)
     */
    private $cin ;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="company_name", type="string", nullable = true)
     *
     */
    protected $companyName;

    /**
     * @var string
     * @ORM\Column(name="company_phone", type="string", nullable = true)
     *
     */
    protected $companyPhone;

    /**
     * @var string
     * @ORM\Column(name="company_address", type="string", nullable = true)
     *
     */
    protected $companyAddress;

    /**
     * @var string
     * @ORM\Column(name="company_tax_identification_number", type="string", nullable = true)
     *
     */
    protected $companyTaxIdentificationNumber;

    /**
     * @ORM\Column(name="client_type", type="integer", nullable=true, options={"default":1})
     */
    protected $clientType = 1;

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



    /**
    * @var \DateTime $created
    *
    * @ORM\Column(type="datetime")
    */
    protected $created;


    /**
    * @var \DateTime  $updated
    *
    * @ORM\Column(type="datetime")
    */
    protected $updated;

    /**
     * @ORM\OneToMany(targetEntity="AttributValue", mappedBy="user" , cascade={"persist", "remove"})
     */
    private $attributValues;

    /**
     * @ORM\OneToMany(targetEntity="Attribut", mappedBy="user" , cascade={"persist", "remove"})
     */
    private $attributs;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="user" , cascade={"persist", "remove"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user" , cascade={"persist", "remove"})
     */
    private $products;

    

    public function __construct()
    {
        parent::__construct();
        $this->created = new \DateTime;
        $this->updated = new \DateTime;
        $this->enabled = false;
        $this->code = $this->random_alphanumeric_string(6);
    }

    public function getAvatar()
    {
        return "/img/avatar5.png";
    }

    public function getName()
    {
      if ($this->hasRole('ROLE_ADMIN')) {
        return "Administrateur";
      }elseif ($this->hasRole('ROLE_SUPPLIERS')) {
        return "Fournisseur";
      }elseif ($this->hasRole('ROLE_RESELLER')) {
        return "Revendeur";
      }
    }

    public function getMemberSince(){
      return $this->created;
    }

    public function getInfo(){
      return null;
    }

    public function getTitle()
    {
        return "";
    }

    /**
    * @return mixed
    */
    public function getCreated()
    {
       return $this->created;
    }

    /**
    * @param mixed $created
    */
    public function setCreated($created)
    {
       $this->created = $created;
    }

    /**
    * @return mixed
    */
    public function getUpdated()
    {
       return $this->updated;
    }

    /**
    * @param mixed $created
    */
    public function setUpdated($updated)
    {
       $this->updated = $updated;
    }

    public function getAgent()
    {
        return $this->firstName.' '.$this->lastName.' ('. $this->email.')';
    }
    public function __toString()
    {
        return $this->firstName.' '.$this->lastName.' ('. $this->email.')';
    }
    public function getUser()
    {
        return $this->firstName.' '.$this->lastName.' ('. $this->email.')';
    }


    /**
    * @return mixed
    */
    public function getFirstName()
    {
       return $this->firstName;
    }

    /**
    * @param mixed $created
    */
    public function setFirstName($firstName)
    {
       $this->firstName = $firstName;
    }

    /**
    * @return mixed
    */
    public function getLastName()
    {
       return $this->lastName;
    }

    /**
    * @param mixed $created
    */
    public function setLastName($lastName)
    {
       $this->lastName = $lastName;
    }

    public function getCode()
    {
       return $this->code;
    }

    /**
    * @param mixed $created
    */
    public function setCode($code)
    {
       $this->code = $code;
    }

    /**
    * @return mixed
    */
    public function getPhone()
    {
       return $this->phone;
    }

    /**
    * @param mixed $created
    */
    public function setPhone($phone)
    {
       $this->phone = $phone;
    }

    public function getCin()
    {
       return $this->cin;
    }

    /**
    * @param mixed $created
    */
    public function setCin($cin)
    {
       $this->cin = $cin;
    }

    public function random_alphanumeric_string($length) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * Set companyName.
     *
     * @param string|null $companyName
     *
     * @return User
     */
    public function setCompanyName($companyName = null)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName.
     *
     * @return string|null
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyPhone.
     *
     * @param string|null $companyPhone
     *
     * @return User
     */
    public function setCompanyPhone($companyPhone = null)
    {
        $this->companyPhone = $companyPhone;

        return $this;
    }

    /**
     * Get companyPhone.
     *
     * @return string|null
     */
    public function getCompanyPhone()
    {
        return $this->companyPhone;
    }

    /**
     * Set companyAddress.
     *
     * @param string|null $companyAddress
     *
     * @return User
     */
    public function setCompanyAddress($companyAddress = null)
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    /**
     * Get companyAddress.
     *
     * @return string|null
     */
    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }

    /**
     * Set companyTaxIdentificationNumber.
     *
     * @param string|null $companyTaxIdentificationNumber
     *
     * @return User
     */
    public function setCompanyTaxIdentificationNumber($companyTaxIdentificationNumber = null)
    {
        $this->companyTaxIdentificationNumber = $companyTaxIdentificationNumber;

        return $this;
    }

    /**
     * Get companyTaxIdentificationNumber.
     *
     * @return string|null
     */
    public function getCompanyTaxIdentificationNumber()
    {
        return $this->companyTaxIdentificationNumber;
    }

    /**
     * Set clientType.
     *
     * @param int|null $clientType
     *
     * @return User
     */
    public function setClientType($clientType = null)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * Get clientType.
     *
     * @return int|null
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * Set altitude.
     *
     * @param string|null $altitude
     *
     * @return User
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
     * @return User
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
     * Add attributValue.
     *
     * @param \AppBundle\Entity\AttributValue $attributValue
     *
     * @return User
     */
    public function addAttributValue(\AppBundle\Entity\AttributValue $attributValue)
    {
        $this->attributValues[] = $attributValue;

        return $this;
    }

    /**
     * Remove attributValue.
     *
     * @param \AppBundle\Entity\AttributValue $attributValue
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttributValue(\AppBundle\Entity\AttributValue $attributValue)
    {
        return $this->attributValues->removeElement($attributValue);
    }

    /**
     * Get attributValues.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributValues()
    {
        return $this->attributValues;
    }

    /**
     * Add attribut.
     *
     * @param \AppBundle\Entity\Attribut $attribut
     *
     * @return User
     */
    public function addAttribut(\AppBundle\Entity\Attribut $attribut)
    {
        $this->attributs[] = $attribut;

        return $this;
    }

    /**
     * Remove attribut.
     *
     * @param \AppBundle\Entity\Attribut $attribut
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttribut(\AppBundle\Entity\Attribut $attribut)
    {
        return $this->attributs->removeElement($attribut);
    }

    /**
     * Get attributs.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributs()
    {
        return $this->attributs;
    }

    /**
     * Add category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return User
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return User
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
