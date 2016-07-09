<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="category", columns={"category"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Product {

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="keyword", type="string", length=512, nullable=true)
     */
    private $keyword;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="rate_pro", type="float", nullable=false)
     */
    private $rate = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="numrate_pro", type="integer", nullable=false)
     */
    private $numRate = '0';

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="productNo", type="string")
     * @SerializedName("productNo")
     */
    private $productNo;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="creat_at", type="datetime", nullable=false)
     * @SerializedName("creatAt")
     */
    private $creatAt;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="descr", type="text",  nullable=true)
     */
    private $descr;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \ShopBundle\Entity\Category
     * @Expose
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->creatAt = new \DateTime();
        //$this->updatedAt = new \DateTime();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set productNo
     *
     * @param integer $productNo
     *
     * @return Product
     */
    public function setProductNo($productNo) {
        $this->productNo = $productNo;

        return $this;
    }

    /**
     * Get productNo
     *
     * @return string
     */
    public function getProductNo() {
        return $this->productNo;
    }

    function getRate() {
        return $this->rate;
    }

    function getNumRate() {
        return $this->numRate;
    }

    function setRate($rate) {
        $this->rate = $rate;
    }

    function setNumRate($numRate) {
        $this->numRate = $numRate;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Product
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Product
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Set creatAt
     *
     * @param \DateTime $creatAt
     *
     * @return Product
     */
    public function setCreatAt($creatAt) {
        $this->creatAt = $creatAt;

        return $this;
    }

    /**
     * Get creatAt
     *
     * @return \DateTime
     */
    public function getCreatAt() {
        return $this->creatAt;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return Product
     */
    public function setDescr($descr) {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr() {
        return $this->descr;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param \ShopBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\ShopBundle\Entity\Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ShopBundle\Entity\Category
     */
    public function getCategory() {
        return $this->category;
    }

}
