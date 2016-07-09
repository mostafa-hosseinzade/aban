<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;

class CertificateofdoctorsController extends FOSRestController {

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     * list of all  doctor
     * 
     */
    public function getDoctorListAction() {
        ///cli_panel/doctor/list
        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:User');
        //query
        $query = $Repository->createQueryBuilder('p');
        //read all doctors

        $query->where("p.roles LIKE :roles")
                ->setParameter('roles', '%ROLE_DOCTOR%')
                ->setMaxResults(8);
        $q = $query->getQuery();
        return array(
            'Doctors' => $q->getResult(),
        );
    }

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     * list of all  doctor
     * 
     */
    public function getDoctorListByExpertiseAction() {

        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:User');
        //query
        $query = $Repository->createQueryBuilder('p');
        //read all doctors
        $query->where("p.roles LIKE :roles")
                ->setParameter('roles', '%ROLE_DOCTOR%')
                ->setMaxResults(8);
        $q = $query->getQuery();
        $user=$q->getResult();
        $data = array('user' => array(), 'expertise' => array());
        $ids = array();
        //read information query
        foreach ($user as $val) {
            $ids[] = $val->getId();
            $data['user'][]=array(
                'id'=>$val->getId(),
                'name'=>$val->getName(),
                'family'=>$val->getFamily()
            );
        }
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $ids));
        foreach ($Result4 as $value4) {
            $data['expertise'][$value4->getUser()->getId()][] = $value4->getExpertise()->getValue();
        }
        return $data;
    }

}
