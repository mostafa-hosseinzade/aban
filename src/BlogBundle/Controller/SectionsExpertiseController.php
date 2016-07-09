<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Contact;

/**
 * @Route("/expertise")
 */
class SectionsExpertiseController extends Controller {

    /**
     * @Route("/FindAll")
     * @return array
     * 
     */
    public function ShowAllSectionExpertise() {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorySections = $em->getRepository('AppBundle:Sections')->findAll();
        $repositoryExpertise = $em->getRepository('AppBundle:Expertise')->findAll();
        $data = array();
        $i = 0;
        foreach ($repositorySections as $value) {
            $data['sections'][$i]['id'] = $value->getId();
            $data['sections'][$i]['value'] = $value->getValue();
            $i++;
        }

        $i = 0;
        foreach ($repositoryExpertise as $value) {
            $data['expertise'][$i]['id'] = $value->getId();
            $data['expertise'][$i]['value'] = $value->getValue();
            $i++;
        }

        $value = \json_encode($data);
        return new Response($value);
    }

    /**
     * @Route("/FindByName/{name}")
     * @return array
     * 
     */
    public function FindByName($name) {
        $em = $this->getDoctrine()->getEntityManager();

        $searchTerms = explode(' ', $name);
        $searchTermBits = array();
        foreach ($searchTerms as $term) {
            $term = trim($term);
            if (!empty($term)) {
                $searchTermBits[] = "u.name LIKE '%$term%'";
                $searchTermBits[] = "u.family LIKE '%$term%'";
            }
        }
        $c = $em->getConnection();
        $q = $c->query(sprintf("select u.name , u.family , u.id , u.photo  from fos_user u  where u.roles like '%s' and ( u.name like '%s' or u.family like '%s' or %s ) order by u.id ",'%ROLE_DOCTOR%', '%' . $name . '%', '%' . $name . '%', implode(' OR ', $searchTermBits)));
        $user = $q->fetchAll();
        $data = array('sections' => array(), 'expertise' => array());
        $ids = array();
        //read information query
        foreach ($user as $val) {
            $ids[] = $val['id'];
        }
        //find section user
        $Result3 = $em->getRepository('AppBundle:SectionsUserCategory')->findBy(array('user' => $ids));
        foreach ($Result3 as $value3) {
            $data['sections'][$value3->getUser()->getId()][] = $value3->getSections()->getValue();
        }

        //find expertise user
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $ids));
        foreach ($Result4 as $value4) {
            $data['expertise'][$value4->getUser()->getId()][] = $value4->getExpertise()->getValue();
        }

        return new JsonResponse(array('user' => $user, 'data' => $data));
    }

    /**
     * @Route("/FindByExpertise/{id}")
     * @return array
     * 
     */
    public function FindByExpertise($id) {
        $em = $this->getDoctrine()->getEntityManager();

        //repository
        $Expertise = $em->getRepository('AppBundle:Expertise')->find($id);
        $Result = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('expertise' => $Expertise));
        $data = array('sections' => array(), 'expertise' => array());
        $user = array();
        $ids = array();
        foreach ($Result as $val) {
            $user[] = array('name' => $val->getUser()->getName(),
                'family' => $val->getUser()->getFamily(),
                'id' => $val->getUser()->getId(),
                'photo' => $val->getUser()->getPhoto());
            $ids[] = $val->getUser()->getId();
        }
        $Result3 = $em->getRepository('AppBundle:SectionsUserCategory')->findBy(array('user' => $ids));
        foreach ($Result3 as $value3) {
            $data['sections'][$value3->getUser()->getId()][] = $value3->getSections()->getValue();
        }

        //find expertise user
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $ids));
        foreach ($Result4 as $value4) {
            $data['expertise'][$value4->getUser()->getId()][] = $value4->getExpertise()->getValue();
        }
        return new JsonResponse(array('user' => $user, 'data' => $data));
    }

    /**
     * @Route("/FindBySections/{id}")
     * @return array
     * 
     */
    public function FindBySections($id) {
        $em = $this->getDoctrine()->getEntityManager();
        //repository
        $Sections = $em->getRepository('AppBundle:Sections')->find($id);

        $Result = $em->getRepository('AppBundle:SectionsUserCategory')->findBy(array('sections' => $Sections));
        $data = array('sections' => array(), 'expertise' => array());
        $user = array();
        $ids = array();
        foreach ($Result as $val) {
            $user[] = array('name' => $val->getUser()->getName(),
                'family' => $val->getUser()->getFamily(),
                'id' => $val->getUser()->getId(),
                'photo' => $val->getUser()->getPhoto());
            $ids[] = $val->getUser()->getId();
        }
        $Result3 = $em->getRepository('AppBundle:SectionsUserCategory')->findBy(array('user' => $ids));
        foreach ($Result3 as $value3) {
            $data['sections'][$value3->getUser()->getId()][] = $value3->getSections()->getValue();
        }

        //find expertise user
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $ids));
        foreach ($Result4 as $value4) {
            $data['expertise'][$value4->getUser()->getId()][] = $value4->getExpertise()->getValue();
        }
        return new JsonResponse(array('user' => $user, 'data' => $data));
    }

    /**
     * @Route("/FindDoctorById/{id}")
     * @return array
     * 
     */
    public function FindDoctorById($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $ResultCertificate = $em->getRepository('AppBundle:Certificateofdoctors')->findBy(array('user' => $user));
        $Result3 = $em->getRepository('AppBundle:SectionsUserCategory')->findBy(array('user' => $user));
        $Result4 = $em->getRepository('AppBundle:ExpertiseUserCategory')->findBy(array('user' => $user));
        $data = array();

        $data['user']['name'] = $user->getName();
        $data['user']['family'] = $user->getFamily();
        $data['user']['photo'] = $user->getPhoto();

        foreach ($ResultCertificate as $val) {
            $data['certificate'][] = array(
                'desc' => $val->getCertificate(),
                'photo' => $val->getPhoto(),
                'expired_at' => $val->getExpirydate()
            );
        }
        foreach ($Result3 as $value3) {
            $data['sections'][] = $value3->getSections()->getValue();
        }

        foreach ($Result4 as $value4) {
            $data['expertise'][] = $value4->getExpertise()->getValue();
        }

        if (!empty($data)) {
            $data['LengthCertificate'] = count($data['certificate']);
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    /**
     * @Route("/FindDoctorByIdReserve/{id}")
     * @return array
     * 
     */
    public function FindDoctorByIdReserve($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $Result = $em->getRepository('AppBundle:DoctorTimeCategory')->findBy(array('user' => $user));
        $ResultAnimale = $em->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser()));
        $data = array();
        $t = 0;
        foreach ($ResultAnimale as $value) {
            $data['animale'][$t]['name'] = $value->getName();
            $data['animale'][$t]['id'] = $value->getId();
            $t++;
        }

        $data['user']['name'] = $user->getName();
        $data['user']['family'] = $user->getFamily();
        $data['user']['photo'] = $user->getPhoto();

        $i = 0;
        foreach ($Result as $value) {
            $data['time'][$i]['day'] = $value->getTimeDoctor()->getDay();
            $data['time'][$i]['hour'] = $value->getTimeDoctor()->getHour();
            $i++;
        }

        if (!empty($data)) {
            $data['LengthTime'] = count($data['time']);
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    /**
     * @Route("/ReserveTemplate")
     * @Template("BlogBundle::Default/reserve.html.twig")
     */
    public function ReserveAction() {
        return array();
    }

}
