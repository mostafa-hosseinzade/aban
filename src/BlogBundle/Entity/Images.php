<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Images
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="user_id", columns={"user_id", "gallery_id"}), @ORM\Index(name="order_list", columns={"order_list"}), @ORM\Index(name="user_id_2", columns={"user_id"}), @ORM\Index(name="gallery_id", columns={"gallery_id"}), @ORM\Index(name="user_id_3", columns={"user_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Images {

    /**
     * @ORM\PrePersist()
     */
    public function setCreateAtValue() {
        $this->createAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }

    /**
     * @ORM\PreFlush()
     */
    public function setUpdateAtValue() {
        $this->updateAt = new \DateTime();
    }

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="alt", type="string", length=512, nullable=true)
     */
    private $alt;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="meta", type="string", length=1024, nullable=true)
     */
    private $meta;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="src", type="text", nullable=true)
     */
    private $src;
    private $temp;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     * @SerializedName("createAt")
     */
    private $createAt;

    /**
     * @var datetime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     * @SerializedName("updateAt")
     */
    private $updateAt;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \BlogBundle\Entity\Gallery
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Gallery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     * })
     */
    private $gallery;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="order_list", type="string", length=1024, nullable=true)
     * @SerializedName("orderList")
     */
    private $orderList;

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
     * Set alt
     *
     * @param string $alt
     *
     * @return Images
     */
    public function setAlt($alt) {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt() {
        return $this->alt;
    }

    public function getSrc() {
        return $this->src;
    }

    public function setSrc($src) {
        $this->src = $src;
    }

    /**
     * Set meta
     *
     * @param string $meta
     *
     * @return Images
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
     * @return Images
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
     * @param datetime $updateAt
     *
     * @return Images
     */
    public function setUpdateAt($updateAt) {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return datetime
     */
    public function getUpdateAt() {
        return $this->updateAt;
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
     * Set gallery
     *
     * @param \BlogBundle\Entity\Gallery $gallery
     *
     * @return Images
     */
    public function setGallery(\BlogBundle\Entity\Gallery $gallery = null) {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \BlogBundle\Entity\Galery
     */
    public function getGallery() {
        return $this->gallery;
    }

    /**
     * Set orderList
     *
     * @param $orderList
     *
     * @return Images
     */
    public function setOrderList($orderList = null) {
        $this->orderList = $orderList;

        return $this;
    }

    /**
     * Get orderList
     *
     * @return string
     */
    public function getOrderList() {
        return $this->orderList;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Images
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
