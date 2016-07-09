<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="menu",uniqueConstraints={@ORM\UniqueConstraint(name="page_id", columns={"page_id"})}, indexes={@ORM\Index(name="parent", columns={"parent"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu {

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * 
     * @Expose
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_list", type="integer", nullable=true)
     * 
     * @SerializedName("orderList")
     * @Expose
     */
    private $orderList;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @SerializedName("createdAt")
     * @Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @SerializedName("updatedAt")
     * @Expose
     */
    private $updatedAt;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="active", type="boolean")
     * @Expose
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $id;

    /**
     * @var \BlogBundle\Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id")
     * })
     * @Expose
     */
    private $parent;

    /**
     * @var \BlogBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id",onDelete="SET NULL")
     * })
     * @Expose
     */
    private $page;

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->updateAt = new \DateTime();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Menu
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return boolean
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive() {
        return $this->active;
    }
    
    /**
     * Set orderList
     *
     * @param integer $orderList
     *
     * @return Menu
     */
    public function setOrderList($orderList) {
        $this->orderList = $orderList;

        return $this;
    }

    /**
     * Get orderList
     *
     * @return integer
     */
    public function getOrderList() {
        return $this->orderList;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Menu
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Menu
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id=$id;
    }

    public function getPage() {
        return $this->page;
    }

    public function setPage(\BlogBundle\Entity\Page $page) {
        $this->page = $page;
    }

    /**
     * Set parent
     *
     * @param \BlogBundle\Entity\Menu $parent
     *
     * @return Menu
     */
    public function setParent(\BlogBundle\Entity\Menu $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \BlogBundle\Entity\Menu
     */
    public function getParent() {
        return $this->parent;
    }
    
}
