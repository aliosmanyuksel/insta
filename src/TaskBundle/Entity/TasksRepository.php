<?php

namespace TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TasksRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TasksRepository extends EntityRepository
{
    public function countRunning($id){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT count(t.id) FROM TaskBundle:Tasks t WHERE t.account_id = $id AND t.status in (0,2)"
            )
            ->getSingleScalarResult();
    }

    public function getUserId($id){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT IDENTITY(a.user) FROM TaskBundle:Tasks t INNER JOIN AppBundle:Accounts a WITH t.account_id=a.id WHERE t.id = $id"
            )
            ->getSingleScalarResult();
    }
    public function getSchedulerHistory($id){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT t.type, DATE_DIFF(st.runAt, CURRENT_TIMESTAMP()) as  runAtDiff, st.runAt, t.count
                FROM
                TaskBundle:ScheduleTasks st
                INNER JOIN
                TaskBundle:Tasks t
                WITH
                t.id = st.task_id
                WHERE t.account_id = $id AND st.runAt > :onedayback
                ORDER BY st.runAt"
            )
            ->setParameter('onedayback', (new \DateTime('now'))->sub(new \DateInterval('P1D')))
            ->getResult();
    }
}
