<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

use AppBundle\Entity\Animalsphoto;


class AnimalsphotoController extends FOSRestController {

    /**
     * @param $animals
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getPicturesAnimalsAction($animals) {
        $animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($animals);
        return $this->getDoctrine()->getRepository('AppBundle:Animalsphoto')->findByAnimals($animal);
    }

    /**
     * Collection post action
     * @var Request $request
     * @return View|array
     * 
     */
    public function postPhotoAnimalsAction(Request $request) {

        if ($request->request->get('photo')) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = new Animalsphoto();
            $photo = $request->request->get('photo');
            $entity->setPhoto($photo);
            $Animal = $this->getDoctrine()->getRepository('AppBundle:Animals')->find($request->request->get('animals'));
            $entity->setAnimals($Animal);
            $entity->setPhotoDefault($request->request->get('photo_default'));
            $em->persist($entity);
            $em->flush();
            return array(
                'message' => 0
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
    public function putAnimalsDefaultPictureAction(Request $request, $id, $idAnimals) {
        ///cli_panel/animals/{id}/defaults/{idAnimals}/picture.{_format}
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

        return array('Message' => 'Success defalut image');
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deletePhotoAnimalsAction($id) {
        $message = '';
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:Animalsphoto')->find($id);
        if ($entity->getPhotoDefault()) {
            $message = 'def image';
        } else {
            $message = 'Deleted Image';
            $em->remove($entity);
            $em->flush();
        }


        return array('deleteMessage' => $message);
    }

}
