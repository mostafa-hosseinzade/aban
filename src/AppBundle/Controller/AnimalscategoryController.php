<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

class AnimalscategoryController extends FOSRestController {

    /**
     *
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function getAnimalsCategoryAction() {
        return $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->findAll();
    }

}
