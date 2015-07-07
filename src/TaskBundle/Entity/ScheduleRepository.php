<?php

namespace TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ScheduleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScheduleRepository extends EntityRepository
{
    public function getHistory($id){
        $date = (new \DateTime())->format('Y-m-d H');
        return $this->getEntityManager()
            ->createQuery(
                "SELECT st
                  FROM TaskBundle:ScheduleTasks st
                  INNER JOIN TaskBundle:Tasks t
                  WITH st.task_id=t.id
                  INNER JOIN AppBundle:Accounts ac
                  WITH t.account_id=ac.id
                  WHERE ac.id = $id and st.runAt > '$date'"
            )
            ->getResult();
    }
}
