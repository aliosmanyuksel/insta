<?php

namespace TaskBundle\Entity;

use AppBundle\Entity\Accounts;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Tasks
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Accounts")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account_id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $tags;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $count;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
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
     * Set tags
     *
     * @param string $tags
     * @return Tasks
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Tasks
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Tasks
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Tasks
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set account_id
     *
     * @param \AppBundle\Entity\Accounts $accountId
     * @return Tasks
     */
    public function setAccountId(\AppBundle\Entity\Accounts $accountId = null)
    {
        $this->account_id = $accountId;

        return $this;
    }

    /**
     * Get account_id
     *
     * @return \AppBundle\Entity\Accounts 
     */
    public function getAccountId()
    {
        return $this->account_id;
    }
}
