<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;
use ForumBundle\Entity\GroupForum;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="group_id", columns={"group_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Post {

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

    public function __construct() {
        $this->enabled = false;
    }

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank title")
     * 
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank content")
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="like_value", type="integer", nullable=false)
     */
    private $like = 0;

    /**
     * @var \DateTime
     * @Expose
     * 
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Expose
     * 
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
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

    /**
     * @var \AppBundle\Entity\User
     * 
     * @Expose
     * 
     * 
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \ForumBundle\Entity\GroupForum
     * @Expose
     * 
     * @Assert\NotBlank(message = "not_blank")
     * 
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\GroupForum")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $group;

    /**
     * @var boolean
     * @Expose
     * 
     * 
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * @return Post
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
     * Set like
     *
     * @param integer $like
     *
     * @return Post
     */
    public function setLike($like) {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return integer
     */
    public function getLike() {
        return $this->like;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
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
     * @return Post
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

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Post
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

    /**
     * Set group
     *
     * @param \ForumBundle\Entity\GroupForum $group
     *
     * @return Post
     */
    public function setGroup(\ForumBundle\Entity\GroupForum $group = null) {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \ForumBundle\Entity\GroupForum
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Post
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }

}
