<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

class DoctorTimeVisitController extends FOSRestController {

    private function isDoctorUser($id) {
        ///cli_panel/doctor/list
        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:User');
        //query
        $query = $Repository->createQueryBuilder('p');
        //read all doctors

        $query->where("p.roles LIKE :roles and p.id=:id")
                ->setParameter('roles', '%ROLE_DOCTOR%')
                ->setParameter('id', $id);
        $q = $query->getQuery();
        if (count($q->getResult()) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function ComputingStringClock($strField, $defaultVisitTime) {

        $arr = explode(";", $strField);
        if (count($arr) < 3) {
            return 0;
        }

        if ($arr[0] == "false") {
            return 0;
        } else {
            $morningTime = explode("-", $arr[1]);
            $afternoonTime = explode("-", $arr[2]);
            //get Morning time different
            $partOne = strtotime($morningTime[1]) - strtotime($morningTime[0]);
            //get AfterNoon time different
            $partTwo = strtotime($afternoonTime[1]) - strtotime($afternoonTime[0]);
        }

        return intval(floor(($partOne + $partTwo) / $defaultVisitTime));
    }

    private function CountAllWeek($idDoctor) {
        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:TimeDoctor');
        $infoUser = $this->getDoctrine()->getRepository('AppBundle:User')->find($idDoctor);
        $query = $Repository->createQueryBuilder('p');

        $day = date('w') + 1;
        $week_start = date('Y-m-d', strtotime('-' . $day . ' days'));
        $query->where("p.started >= :now and p.user = :user")
                ->setParameter('now',$week_start)
                ->setParameter('user', $infoUser);
        $q = $query->getQuery();
        $result = $q->getResult();

        return count($result);
    }

    /**
     *
     * @return array
     * @View()
     * @param $id,$offset
     * @throws NotFoundHttpException when comment not exist
     * list of all  doctorTime
     * 
     */
    public function getDoctorTimeReservedAction($idDoctor, $offset) {
        //cli_panel/doctors/{idDoctor}/times/{offset}/reserved.{_format} 
        $defaultTime = 20 * 60;
        $countPerson = array();
        $allWeek = 0;
        $startedWeek = '';
        $idTimeDoctor = '';
        //checking user is doctor or found
        if (!$this->isDoctorUser($idDoctor)) {
            $query2 = 'This user not doctor or not found';
            return array(
                'TableResultForWeek' => array('0' => $query2)
            );
        }

        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:TimeDoctor');

        try {
            $infoUser = $this->getDoctrine()->getRepository('AppBundle:User')->find($idDoctor);
        } catch (Exception $ex) {
            $query2 = 'This user not doctor or not found';
            return array(
                'TableResultForWeek' => array('0' => $query2)
            );
        }

        $query = $Repository->createQueryBuilder('p');

        $day = date('w') + 1;
        $week_start = date('Y-m-d', strtotime('-' . $day . ' days'));

        $query->where("p.started >= :now and p.user = :user")
                ->setParameter('now', $week_start)
                ->setParameter('user', $infoUser)
                ->setFirstResult($offset)
                ->setMaxResults(1)
                ->orderBy('p.started', 'asc');
        $q = $query->getQuery();
        $result = $q->getResult();

        if (count($result) > 0) {
            $idTimeDoctor = $result[0]->getId();
            $countPerson[0] = $this->ComputingStringClock($result[0]->getOne(), $defaultTime);
            $countPerson[1] = $this->ComputingStringClock($result[0]->getTow(), $defaultTime);
            $countPerson[2] = $this->ComputingStringClock($result[0]->getThree(), $defaultTime);
            $countPerson[3] = $this->ComputingStringClock($result[0]->getFour(), $defaultTime);
            $countPerson[4] = $this->ComputingStringClock($result[0]->getFive(), $defaultTime);
            $countPerson[5] = $this->ComputingStringClock($result[0]->getSix(), $defaultTime);
            $countPerson[6] = $this->ComputingStringClock($result[0]->getSeven(), $defaultTime);
            $startedWeek = $result[0]->getStarted();

            $conn = $this->get('database_connection');
            $query2 = $conn->fetchAll("SELECT count(*) as count,x._date as DateSelect,x.started ,DATEDIFF(x._date,x.started) as daysDiff, DATE_ADD(x.started,INTERVAL 6 DAY) as WEEK FROM reserve_time p inner join (SELECT px.id,px._date,s.started,px.active FROM ticket px left join timedoctor s on s.id = px.time_doctor_id where s.user_id = $idDoctor) x on p.ticket_id = x.id where x.active = 1 and x._date between x.started and DATE_ADD(x.started,INTERVAL 6 DAY) group by x._date");


            foreach ($query2 as $record) { //daysDiff            
                $countPerson[intval($record['daysDiff'])] = $countPerson[intval($record['daysDiff'])] - intval($record['count']);
            }

            $allWeek = $this->CountAllWeek($idDoctor);
        }

        return array(
            'TableResultForWeek' => $countPerson,
            'CountWeek' => $allWeek,
            'CurrentWeek' => $offset,
            'StartedWeek' => $startedWeek,
            'idTimeDoctor' => $idTimeDoctor
           
        );
    }

}
