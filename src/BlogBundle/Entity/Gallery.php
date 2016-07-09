<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Galery
 *
 * @ORM\Table(name="galery", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery {

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
     * @ORM\Column(name="title", type="string", length=256, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="meta", type="string", length=1024, nullable=true)
     */
    private $meta;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="create_at", type="datetime", nullable=true)
     * @SerializedName("createAt")
     */
    private $createAt;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     * @SerializedName("updateAt")
     */
    private $updateAt;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="order_list", type="integer", nullable=true)
     * @SerializedName("orderList")
     */
    private $orderList;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    function __construct() {
        $this->createAt = new \DateTime();
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
     * @return Gallery
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
     * Set meta
     *
     * @param string $meta
     *
     * @return Gallery
     */
    public function setMeta($meta) {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return string
     */
    public function getMeta() {
        return $this->meta;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Gallery
     */
    public function setCreateAt($createAt) {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt() {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Gallery
     */
    public function setUpdateAt($updateAt) {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt() {
        return $this->updateAt;
    }

    /**
     * Set orderList
     *
     * @param integer $orderList
     *
     * @return Gallery
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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Gallery
     */
    public function setUser(\AppBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

}
