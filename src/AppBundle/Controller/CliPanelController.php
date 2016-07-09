<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * @Route("/cli_panel")
 */
class CliPanelController extends Controller{

    /**
     * @Route("/",name="client_panel")
     * @Template("AppBundle::cli_panel.html.twig")
     */
    public function indexAction() {
        
        return array();
    }
}
