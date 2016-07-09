<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="parent", columns={"parent"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Category {

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="name", type="string", length=512, nullable=false)
     */
    private $name;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="ctgNo", type="string", nullable=false, unique=true)
     * @SerializedName("ctgNo")
     */
    private $ctgNo;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="descr", type="string", length=512, nullable=true)
     */
    private $descr;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="photo", type="text", nullable=true)
     */
    private $photo;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="creat_at", type="datetime", nullable=true)
     * @SerializedName("creatAt")
     */
    private $creatAt;

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
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     * })
     */
    private $parent;

    /**
     * @var boolean
     * @Expose
     * @ORM\Column(name="isHasSubCtg", type="boolean", nullable=false)
     * @SerializedName("isHasSubCtg")
     */
    private $isHasSubCtg = false;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
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
     * Set ctgNo
     *
     * @param integer $ctgNo
     *
     * @return Category
     */
    public function setCtgNo($ctgNo) {
        $this->ctgNo = $ctgNo;

        return $this;
    }

    /**
     * Get ctgNo
     *
     * @return string
     */
    public function getCtgNo() {
        return $this->ctgNo;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return Category
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
     * Set photo
     *
     * @param string $photo
     *
     * @return Category
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
     * @return Category
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
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->creatAt = new \DateTime();
        //$this->updatedAt = new \DateTime();
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
     * Set parent
     *
     * @param \ShopBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\ShopBundle\Entity\Category $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \ShopBundle\Entity\Category
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set isHasSubCtg
     *
     * @param boolean $isHasSubCtg
     *
     * @return Category
     */
    public function setIsHasSubCtg($isHasSubCtg) {
        $this->isHasSubCtg = $isHasSubCtg;

        return $this;
    }

    /**
     * Get isHasSubCtg
     *
     * @return boolean
     */
    public function getIsHasSubCtg() {
        return $this->isHasSubCtg;
    }

}
