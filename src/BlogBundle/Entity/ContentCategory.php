<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * ContentCategory
 *
 * @ORM\Table(name="contentcategory")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class ContentCategory {

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
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;
    
    /**
     * @var boolean
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="slug", type="string", length=500, nullable=false)
     */
    private $slug;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @SerializedName("createdAt")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @SerializedName("updatedAt")
     */
    private $updatedAt;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    function __construct() {
        
    }

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
     * @return ContentCategory
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
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
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return ContentCategory
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContentCategory
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
     * @return ContentCategory
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

}
