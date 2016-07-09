<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Animals;
use AppBundle\Entity\Animalsphoto;
use AppBundle\Entity\UserMessage;

class MobileController extends FOSRestController {

    /**
     * @return array
     * @View()
     * @throws NotFoundHttpException when post not exist
     */
    public function getMobileUserAction() {
        return $this->getUser();
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postUserInfoEditAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
         
        
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $user->setName($request->request->get('name'));
        $user->setFamily($request->request->get('family'));
        $user->setEmail($request->request->get('email'));
        $user->setPhone('0' . $request->request->get('phone'));
        $user->setMobile($request->request->get('mobile'));
        if ($request->request->get('sex') == null) {
            $user->setSex(0);
        } else {
            $user->setSex($request->request->get('sex'));
        }

        $user->setAddress($request->request->get('address'));
        $user->setPostCode($request->request->get('post_code'));

        $photo = $request->request->get('photo');
        if ($photo) {
            $user->setPhoto($photo);
        } else {
            $user->setPhoto(null);
        }
        //$em->persist($user);
        $em->flush();
        return array('message' => 'message is changed');
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postUserPhotoMobileEditAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $photo = $request->request->get('photo');
        if ($photo) {
            $user->setPhoto($photo);
        } else {
            $user->setPhoto(null);
        }
        $em->persist($user);
        $em->flush();
        return array('message' => 1);
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAnimalsUserMobileAction() {

        $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
        $photodefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals, 'photoDefault' => true));
        //$countanimals = count($infoAnimals);
        //
        return array(
            'infoAnimals' => $infoAnimals,
            'defaultPhoto' => $photodefault
        );
    }

    /**
     * @param $idAnimal, $updateAtClient, $countClientRows
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAnimalSyncronizedIsAction($idAnimal, $updateAtClient, $countClientRows) {

        $CountServer = Count($this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true)));
        if ($CountServer != $countClientRows) {
            $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
            $photodefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals, 'photoDefault' => true));
            return array(
                'infoAnimals' => $infoAnimals,
                'defaultPhoto' => $photodefault,
                'sync' => 0
            );
        }

        $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('id' => $idAnimal, 'user' => $this->getUser(), 'active' => true, 'updateAt' => new \DateTime($updateAtClient)));
        if (count($infoAnimals) > 0) {
            return array('sync' => 1);
        } else {
            $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
            $photodefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals, 'photoDefault' => true));
            return array(
                'infoAnimals' => $infoAnimals,
                'defaultPhoto' => $photodefault,
                'sync' => 0
            );
        }
        return array('error' => 'err');
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function postCheckAnimalsSyncAction(Request $request) {
        $animals = $request->request->all();

        $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
        $photodefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals, 'photoDefault' => true));

        if (count($infoAnimals) != count($animals)) {
            return array(
                'infoAnimals' => $infoAnimals,
                'defaultPhoto' => $photodefault,
                'sync' => 0
            );
        }

        $sync = 0;

        for ($i = 0; $i < count($animals); $i++) {
            if (new \DateTime($animals[$i]['updated_at']) != $infoAnimals[$i]->getUpdateAt()) {
                $sync = 0;
            } else {
                $sync = 1;
            }
        }

        if ($sync == 1) {
            return array(
                'sync' => 1
            );
        } else {
            return array(
                'infoAnimals' => $infoAnimals,
                'defaultPhoto' => $photodefault,
                'sync' => 0
            );
        }
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postAnimalsEditInfoMobileAction(Request $request, $id) {
        ///cli_panel/animals/{id}/edit/info.{_format} 
        $msg = null;
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $infoAnimals = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($id);
            $infoAnimals->setName($request->request->get('name'));
            $infoAnimals->setAge($request->request->get('age'));
            $infoAnimals->setSex($request->request->get('sex'));
            $infoAnimals->setWeight($request->request->get('weight'));
            $infoAnimals->setStature($request->request->get('stature'));
            $category = $request->request->get('_animalscategory')['id'];
            $catAnimal = $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->find($category);
            $infoAnimals->setAnimalscategory($catAnimal);
            $em->flush();
            $msg = 0;
        } catch (Exception $ex) {
            $msg = 1;
        }
        return array('message' => $msg);
    }



    /**
     * 
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postAnimalsInsertMobileAction(Request $request) {
        //animals/inserts/mobiles.json
        $msg = null;
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new Animals();
            $photoImage = new Animalsphoto();

            $entity->setName($request->request->get('name'));
            $entity->setAge($request->request->get('age'));
            $entity->setSex($request->request->get('sex'));
            $entity->setWeight($request->request->get('weight'));
            $entity->setStature($request->request->get('stature'));

            $category = $request->request->get('_animalscategory');
            $catAnimal = $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->find($category);

            $entity->setAnimalscategory($catAnimal);
            $entity->setMicroChip($request->request->get('micro_chip'));
            $entity->setActive(false);

            $entity->setUser($this->getUser());

            $em->persist($entity);
            $em->flush();

            $photoImage->setAnimals($entity);
            $photoImage->setPhotoDefault(true);
            $photoImage->setPhoto($request->request->get('photo'));
            $em->persist($photoImage);
            $em->flush();

            $msg = 0;
        } catch (Exception $ex) {
            $msg = 1;
        }
        return array('message' => $msg);
    }

    /**
     * @param $animals
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getPicturesAnimalsMobileAction($animals) {
        $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($animals);
        return $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findByAnimals($animal);
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAllPicturesAnimalsMobileAction() {
        $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->findBy(array('user' => $this->getUser(), 'active' => true));
        return $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $animal));
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postPhotoAnimalsMobileAction(Request $request) {

        if ($request->request->get('photoInsert')) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new Animalsphoto();
            $photo = $request->request->get('photoInsert');
            $entity->setPhoto($photo);
            $Animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($request->request->get('animal'));
            $entity->setAnimals($Animal);
            $entity->setPhotoDefault($request->request->get('photo_default'));
            $em->persist($entity);
            $em->flush();
            return array(
                'message' => $entity
            );
        }
        return array(
            'message' => 1
        );
    }

    /**
     * Put action
     * @var integer $id 
     * @var integer $idAnimals 
     * @return View|array
     */
    public function putAnimalsDefaultPictureMobileAction($id, $idAnimals) {
        ///cli_panel/animals/{id}/defaults/{idAnimals}/picture.{_format}
        $msg = null;
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($idAnimals);
            $photos = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findByAnimals($animal);
            foreach ($photos as $item) {
                $item->setPhotoDefault(false);
            }
            $em->flush();
            $photoDefault = $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->find($id);
            $photoDefault->setPhotoDefault(true);
            $em->flush();
            $msg = 0;
        } catch (Exception $ex) {
            $msg = 1;
        }
        return array('message' => $msg);
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deletePhotoAnimalsMobileAction($id) {
        //photos/{id}/animals/mobile.{_format}
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:Animalsphoto')->find($id);
        $em->remove($entity);
        $em->flush();
        return array('message' => 0);
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteOwnerAnimalsMobileAction($id) {
        //photos/{id}/animals/mobile.{_format}
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:Animals')->find($id);
        $entity->setUser(Null);
        $em->flush();
        return array('message' => 0);
    }

    ///////////////////////////////  message function ///////////////////////////////////////////
    //SingleUser = 0 - MultiUser = 1 - SindleDoctor = 2 - MultiDoctor = 3


    private function GetAdminUser() {
        ///cli_panel/doctor/list
        $em = $this->getDoctrine()->getEntityManager();
        //Repository
        $Repository = $em->getRepository('AppBundle:User');
        //query
        $query = $Repository->createQueryBuilder('p');
        //read all doctors

        $query->where("p.roles LIKE :roles")
                ->setParameter('roles', '%ROLE_SUPER_ADMIN%');
        $q = $query->getQuery();
        $result = $q->getResult();
        if (count($result) > 0) {
            return $result[0]->getId();
        } else {
            return 0;
        }
    }

    /**
     * @param int $id logined user id
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getMessagesUserMobileAction() {
      $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare
                (               
                sprintf("select * from user_message where (ISNULL(user_id) or user_id ='%s') and is_read = 0 and ((message_type = 3 and DATEDIFF(NOW(),created_at) < 2 ) or message_type = '1') order by created_at", $this->getUser()->getId()));

        $statement->execute();
        $r = $statement->fetchAll();
 
        // set readed all message message_type = 1 ======= > user message
        $message = $this->getDoctrine()->getRepository('AppBundle:UserMessage')->findBy(array('user' => $this->getUser(),'messageType' => 1,'isRead' => false));
        if (count($message) > 0) {
            foreach ($message as $msg) {
                $msg->setIsRead(true);
            }
            $em->flush();
        }
        
        return $r;
    }

    /**
     * Put action
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putSetReadedMessageMobileAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $message = $this->getDoctrine()->getRepository('AppBundle:UserMessage')->find($id);
        $message->setIsRead(true);
        $em->flush();
        return array('message' => '0');
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postSendMessageToAdminMobileAction(Request $request) {
        $user = $this->GetAdminUser();
        if ($user) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new UserMessage();
            $entity->setUser($this->getUser());
            $entity->setMessage($request->request->get('message'));
            $entity->setMessageType(0);
            $entity->setIsRead(0);
            $entity->setMessageOwner($this->getUser()->getId());
            $entity->setUserNameMessageOwner($this->getUser()->getUsername());
            $em->persist($entity);
            $em->flush();
        } else {
            return array(
                "message" => "-1"
            );
        }
        return array(
            "message" => '0'
        );
    }

    /**
     * @param int $id 
     * @return array
     * @View()
     * @throws NotFoundHttpException when post not exist
     */
    public function getActivityMobileAction($idAnimal) {
        $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($idAnimal);
        $em = $this->getDoctrine()->getManager();
        $Repository = $em->getRepository('AppBundle:Activity');
        $query = $Repository->createQueryBuilder('p');
        $query->where("p.animale = :animal")
                ->orderBy("p.date", "desc")
                ->setMaxResults(10)
                ->setParameter('animal', $animal);
        $q = $query->getQuery();
        $result = $q->getResult();
        return $result;
    }

}
