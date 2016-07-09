<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Content
 *
 * @ORM\Table(name="content", indexes={@ORM\Index(name="ctg_id", columns={"ctg_id"}), @ORM\Index(name="ctg_id_2", columns={"ctg_id", "user_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Content {

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
     * @Assert\NotBlank(message = "not_blank Title")
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * @Expose
     * @Assert\NotBlank(message = "not_blank Content")
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     * @Expose
     * @Assert\NotBlank(message = "not_blank Visit")
     * @ORM\Column(name="visit", type="integer", nullable=true)
     */
    private $visit = '0';

    /**
     * @var integer
     * @Expose
     * @Assert\NotBlank(message = "not_blank OrderList")
     * @ORM\Column(name="order_list", type="integer", nullable=true)
     * @SerializedName("orderList")
     */
    private $orderList;

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

    /**
     * @var \BlogBundle\Entity\ContentCategory
     * @Expose
     * @Assert\NotBlank(message = "not_blank Category")
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\ContentCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ctg_id", referencedColumnName="id")
     * })
     */
    private $ctg;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Content
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
     * @return Content
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
     * Set visit
     *
     * @param integer $visit
     *
     * @return Content
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
     * Set orderList
     *
     * @param integer $orderList
     *
     * @return Content
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
     * @return Content
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
     * @return Content
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
     * Set ctg
     *
     * @param \BlogBundle\Entity\ContentCategory $ctg
     *
     * @return Content
     */
    public function setCtg(\BlogBundle\Entity\ContentCategory $ctg = null) {
        $this->ctg = $ctg;

        return $this;
    }

    /**
     * Get ctg
     *
     * @return \BlogBundle\Entity\ContentCategory
     */
    public function getCtg() {
        return $this->ctg;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Content
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
