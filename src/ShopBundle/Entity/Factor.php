<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factor
 *
 * @ORM\Table(name="factor", indexes={@ORM\Index(name="customer", columns={"customer"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Factor
{
    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue() {
        $this->date = new \DateTime();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total;

    /**
     * @var integer
     *
     * @ORM\Column(name="descr", type="integer", nullable=true)
     */
    private $descr;

    /**
     * @var string
     *
     * @ORM\Column(name="refsale", type="string", length=512, nullable=true)
     */
    private $refsale;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_client", type="string", length=512, nullable=false)
     */
    private $ipClient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;


        /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer", referencedColumnName="id")
     * })
     */
    private $customer;

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Factor
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set descr
     *
     * @param integer $descr
     *
     * @return Factor
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return integer
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set refsale
     *
     * @param string $refsale
     *
     * @return Factor
     */
    public function setRefsale($refsale)
    {
        $this->refsale = $refsale;

        return $this;
    }

    /**
     * Get refsale
     *
     * @return string
     */
    public function getRefsale()
    {
        return $this->refsale;
    }

    /**
     * Set ipClient
     *
     * @param string $ipClient
     *
     * @return Factor
     */
    public function setIpClient($ipClient)
    {
        $this->ipClient = $ipClient;

        return $this;
    }

    /**
     * Get ipClient
     *
     * @return string
     */
    public function getIpClient()
    {
        return $this->ipClient;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Factor
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


     /**
     * Set status
     *
     * @param $status
     *
     * @return Factor
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return status
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    
    
    /**
     * Set customer
     *
     * @param \AppBundle\Entity\User $customer
     *
     * @return Factor
     */
    public function setCustomer(\AppBundle\Entity\User $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\User
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
