<?php
/**
 * TimeStampale.php
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * 
 */
abstract class TimeStampale {

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime",nullable=true)
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     */
    private $updateAt;

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return Post
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get $createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return Post
     * 
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Auto set the updated date
     *
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue()
    {
       $this->setUpdateAt(new \DateTime());
    }

    /**
     * Set initial value for created/updated values
     *
     * @ORM\PrePersist
     */
    public function setCreateAtValues()
    {
        $this->setCreateAt(new \DateTime());
    }
    public function __construct() {
        $this->setCreateAtValues();
    }
}
