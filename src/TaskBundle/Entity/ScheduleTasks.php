<?php

namespace TaskBundle\Entity;

use TaskBundle\Entity\Tasks;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedule_tasks")
 * @ORM\Entity(repositoryClass="TaskBundle\Entity\ScheduleRepository")
 */
class ScheduleTasks
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="TaskBundle\Entity\Tasks")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $task_id;

    /**
     * @var array
     */
    protected $days;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $runAt;


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
     * Set runAt
     *
     * @param \DateTime $runAt
     * @return ScheduleTasks
     */
    public function setRunAt($runAt)
    {
        $this->runAt = $runAt;

        return $this;
    }

    /**
     * Get runAt
     *
     * @return \DateTime
     */
    public function getRunAt()
    {
        return $this->runAt;
    }

    /**
     * Set task_id
     *
     * @param \TaskBundle\Entity\Tasks $taskId
     * @return ScheduleTasks
     */
    public function setTaskId(\TaskBundle\Entity\Tasks $taskId = null)
    {
        $this->task_id = $taskId;

        return $this;
    }

    /**
     * Get task_id
     *
     * @return \TaskBundle\Entity\Tasks
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * Set days
     *
     * @param array $days
     * @return ScheduleTasks
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }
}
