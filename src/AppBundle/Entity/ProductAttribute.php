<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductAttribute
 *
 * @ORM\Table(name="product_attribute")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductAttributeRepository")
 */
class ProductAttribute
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
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $isDefault;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="bigint")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=3)
     */
    private $price;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @var string|null
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="isbn", type="string", length=255, nullable=true)
     */
    private $isbn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ean13", type="string", length=255, nullable=true)
     */
    private $ean13;

    /**
     * @var string|null
     *
     * @ORM\Column(name="upc", type="string", length=255, nullable=true)
     */
    private $upc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mpn", type="string", length=255, nullable=true)
     */
    private $mpn;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
     * @ORM\JoinColumn(name="product_id", nullable=false)
     */
    private $product;


    /**
     * @ORM\ManyToMany(targetEntity="AttributValue")
     * @ORM\JoinTable(name="attributvalues_productattributes",
     *  joinColumns={@ORM\JoinColumn(name="product_attribute_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="attribut_value_id", referencedColumnName="id")}
     *  )
     */
    private $attributValues;



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
     * Set isDefault.
     *
     * @param bool $isDefault
     *
     * @return ProductAttribute
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return ProductAttribute
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return ProductAttribute
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set reference.
     *
     * @param string|null $reference
     *
     * @return ProductAttribute
     */
    public function setReference($reference = null)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string|null
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set slug.
     *
     * @param string|null $slug
     *
     * @return ProductAttribute
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set isbn.
     *
     * @param string|null $isbn
     *
     * @return ProductAttribute
     */
    public function setIsbn($isbn = null)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn.
     *
     * @return string|null
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set ean13.
     *
     * @param string|null $ean13
     *
     * @return ProductAttribute
     */
    public function setEan13($ean13 = null)
    {
        $this->ean13 = $ean13;

        return $this;
    }

    /**
     * Get ean13.
     *
     * @return string|null
     */
    public function getEan13()
    {
        return $this->ean13;
    }

    /**
     * Set upc.
     *
     * @param string|null $upc
     *
     * @return ProductAttribute
     */
    public function setUpc($upc = null)
    {
        $this->upc = $upc;

        return $this;
    }

    /**
     * Get upc.
     *
     * @return string|null
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * Set mpn.
     *
     * @param string|null $mpn
     *
     * @return ProductAttribute
     */
    public function setMpn($mpn = null)
    {
        $this->mpn = $mpn;

        return $this;
    }

    /**
     * Get mpn.
     *
     * @return string|null
     */
    public function getMpn()
    {
        return $this->mpn;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return ProductAttribute
     */
    public function setProduct(\AppBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributvalueProductattributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add attributvalueProductattribute.
     *
     * @param \AppBundle\Entity\AttributvalueProductattribute $attributvalueProductattribute
     *
     * @return ProductAttribute
     */
    public function addAttributvalueProductattribute( $attributvalueProductattribute)
    {
        $this->attributvalueProductattributes[] = $attributvalueProductattribute;

        return $this;
    }

    /**
     * Remove attributvalueProductattribute.
     *
     * @param \AppBundle\Entity\AttributvalueProductattribute $attributvalueProductattribute
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttributvalueProductattribute( $attributvalueProductattribute)
    {
        return $this->attributvalueProductattributes->removeElement($attributvalueProductattribute);
    }

    /**
     * Get attributvalueProductattributes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributvalueProductattributes()
    {
        return $this->attributvalueProductattributes;
    }

    /**
     * Add attributValue.
     *
     * @param \AppBundle\Entity\AttributValue $attributValue
     *
     * @return ProductAttribute
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
}
