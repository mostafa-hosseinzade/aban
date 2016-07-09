<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Entity\Animals;
use AppBundle\Entity\Animalsphoto;

/**
 * Description of MicroChipController
 *
 * @author administrator
 * @Route("/MicroChip")
 */
class MicroChipController extends Controller {

    /**
     * @Route("/InsertMicroChip")
     * @Method("post")
     */
    public function InsertMicroChip(Request $request) {
        $user = $request->request->get('user');
        $animal = $request->request->get("animal");
        if (empty($user['email']) ||
                empty($user['name']) ||
                empty($user['mobile']) ||
                empty($user['address']) ||
                empty($animal['name']) ||
                empty($animal['code']) ||
                empty($animal['ctg'])) {
            $response['msg'] = "danger;اطلاعات ارسال شده صحیح نمی باشد";
            $response = \json_encode($response);
        }
        $em = $this->getDoctrine()->getManager();
        $check_user_email = $em->getRepository("AppBundle:User")->findBy(array('email' => $user['email']));
        $check_user_mobile = $em->getRepository("AppBundle:User")->findBy(array('mobile' => $user['mobile']));
        if (!empty($check_user_mobile || !empty($check_user_email))) {
            $check_animal = $em->getRepository("AppBundle:Animals")->findBy(array('microChip' => $animal['code']));
            if (!empty($check_animal)) {
                $response['msg'] = "danger;کد میکروچیپ قبلا در سامانه ثبت شده است";
                $response = \json_encode($response);
                return new Response($response);
            }
            $animalAdd = new Animals();
            $animalAdd->setName($animal['name']);
            $animalAdd->setActive(0);
            if (isset($animal['age'])) {
                $animalAdd->setAge($animal['age']);
            }
            $ctg_animal = $em->getRepository("AppBundle:Animalscategory")->find($animal['ctg']);
            $animalAdd->setAnimalscategory($ctg_animal);
            if (isset($animal['weight'])) {
                $animalAdd->setWeight($animal['weight']);
            }
            if (!empty($check_user_mobile)) {
                $animalAdd->setUser($check_user_mobile[0]);
            } else {
                $animalAdd->setUser($check_user_email[0]);
            }
            $animalAdd->setMicroChip($animal['code']);
            if (isset($animal['sex'])) {
                if ($animal['sex'] == 0) {
                    $animalAdd->setSex(0);
                } else {
                    $animalAdd->setSex(1);
                }
            } else {
                $animalAdd->setSex(1);
            }
            $em->persist($animalAdd);
            $em->flush();
            
            if(isset($animal['photo'])){
                $animalsPhoto = new Animalsphoto();
                $animalsPhoto->setAnimals($animalAdd);
                $animalsPhoto->setPhoto($animal['photo']);
                $animalsPhoto->setPhotoDefault(1);
                $animalsPhoto->setCreateAtValues();
                $em->persist($animalsPhoto);
                $em->flush();
            }
        } else {
            $userAdd = new User();
            $userAdd->setName($user['name']);
            $userAdd->setUsername($user['mobile']);
            $userAdd->setFamily($user['family']);
            $userAdd->setMobile($user['mobile']);
            $userAdd->setAddress($user['address']);
            $userAdd->setEmail($user['email']);
            $userAdd->setEnabled(FALSE);
            $userAdd->setPlainPassword($user['mobile']);
            if (isset($user['sex'])) {
                if ($user['sex'] == 0) {
                    $userAdd->setSex(0);
                } else {
                    $userAdd->setSex(1);
                }
            } else {
                $userAdd->setSex(1);
            }
            $em->persist($userAdd);
            $em->flush();

            $animalAdd = new Animals();
            $animalAdd->setName($animal['name']);
            $animalAdd->setActive(0);
            if (isset($animal['age'])) {
                $animalAdd->setAge($animal['age']);
            }
            $ctg_animal = $em->getRepository("AppBundle:Animalscategory")->find($animal['ctg']);
            $animalAdd->setAnimalscategory($ctg_animal);
            if (isset($animal['weight'])) {
                $animalAdd->setWeight($animal['weight']);
            }
            $animalAdd->setUser($userAdd);
            $animalAdd->setMicroChip($animal['code']);
            if ($animal['sex'] == 0) {
                $animalAdd->setSex(0);
            } else {
                $animalAdd->setSex(1);
            }
            $em->persist($animalAdd);
            $em->flush();
            if (isset($animal['photo'])) {
                $animalsPhoto = new Animalsphoto();
                $animalsPhoto->setAnimals($animalAdd);
                $animalsPhoto->setPhoto($animal['photo']);
                $animalsPhoto->setPhotoDefault(1);
                $animalsPhoto->setCreateAtValues();
                $em->persist($animalsPhoto);
                $em->flush();
            }
        }
        $response['msg'] = "success;اطلاعات با موفقیت ثبت شد";
        $response = \json_encode($response);
        return new Response($response);
    }

    /**
     * @Route("/getAllCtg")
     */
    public function getAllCtg() {
        $em = $this->getDoctrine()->getManager();
        $ctg = $em->getRepository("AppBundle:Animalscategory")->findAll();
        if (empty($ctg)) {
            return new Response("No Data");
        }
        $i = 0;
        $data = array();
        foreach ($ctg as $value) {
            $data['ctg'][$i] = $value->Serialize();
            $i++;
        }
        $response = \json_encode($data);
        return new Response($response);
    }

}
