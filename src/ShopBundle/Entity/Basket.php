<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Basket
 *
 * @ORM\Table(name="basket", indexes={@ORM\Index(name="product", columns={"product"}), @ORM\Index(name="factor", columns={"factor"})})
 * @ORM\Entity
 */
class Basket
{
    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \ShopBundle\Entity\Factor
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Factor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="factor", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $factor;

    /**
     * @var \ShopBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id",onDelete="SET NULL")
     * })
     */
    private $product;



    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Basket
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set factor
     *
     * @param \ShopBundle\Entity\Factor $factor
     *
     * @return Basket
     */
    public function setFactor(\ShopBundle\Entity\Factor $factor = null)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * Get factor
     *
     * @return \ShopBundle\Entity\Factor
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set product
     *
     * @param \ShopBundle\Entity\Product $product
     *
     * @return Basket
     */
    public function setProduct(\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ShopBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
