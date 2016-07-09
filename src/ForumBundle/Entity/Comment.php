<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="post_id", columns={"post_id"})})
 * @ORM\Entity
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Comment {

    
    
    /**
     * @ORM\PrePersist()
     * 
     */
    public function setCreatedAtValue(){
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
     /**
     * @ORM\PreUpdate()
     * 
     */
    public function setUpdatedAtValue(){
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
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank")
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="like_Comment", type="integer", nullable=false)
     */
    private $like = '0';

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
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
     * @var \ForumBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $post;

    /**
     * @var \AppBundle\Entity\User
     * 
     * @Expose
     * 
     * @Assert\NotBlank(message = "not_blank")
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var boolean
     * @Expose
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    public function __construct() {

//        $this->createdAt = new \DateTime();
//        $this->updatedAt = new \DateTime();
        $this->enabled=false;
        $this->like = 0;
        
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * Set post
     *
     * @param \ForumBundle\Entity\Post $post
     *
     * @return Comment
     */
    public function setPost(\ForumBundle\Entity\Post $post = null) {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \ForumBundle\Entity\Post
     */
    public function getPost() {
        return $this->post;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(User $user = null) {
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Comment
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
