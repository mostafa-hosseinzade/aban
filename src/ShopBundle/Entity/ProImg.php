<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="pro_img")
 * @ORM\Entity
 */
class ProImg {

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="text", nullable=false)
     */
    private $src;

    /**
     * @var \ShopBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    function getProduct() {
        return $this->product;
    }

    function setProduct(\ShopBundle\Entity\Product $product) {
        $this->product = $product;
    }

        function getSrc() {
        return $this->src;
    }

    function getId() {
        return $this->id;
    }

    function setSrc($src) {
        $this->src = $src;
    }

    function setId($id) {
        $this->id = $id;
    }

}
