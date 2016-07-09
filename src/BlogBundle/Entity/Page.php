<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Page
 *
 * @ORM\Table(name="page", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Page {

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
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="title", type="string", length=1024, nullable=true)
     * @Expose
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Expose
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="meta", type="text", length=65535, nullable=true)
     * @Expose
     */
    private $meta;

    /**
     * @var integer
     *
     * @ORM\Column(name="visit", type="integer", nullable=false)
     * @Expose
     */
    private $visit = 0;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
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
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
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
     * @return Page
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
     * Set content
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set meta
     *
     * @param string $meta
     *
     * @return Page
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
     * Set visit
     *
     * @param integer $visit
     *
     * @return Page
     */
    public function setVisit($visit) {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit
     *
     * @return integer
     */
    public function getVisit() {
        return $this->visit;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Page
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
     * @return Page
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
    
    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Page
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
    
    public function Serialize() {
        $data = array();
        $data['title'] = $this->title;
        $data['content'] = $this->content;
        $data['id'] = $this->id;
        $data['visit'] = $this->visit;
        $data['meta'] = $this->meta;
        $data['createAt'] = $this->createAt;
        $data['updateAt'] = $this->updateAt;
        return $data;
    }
}
