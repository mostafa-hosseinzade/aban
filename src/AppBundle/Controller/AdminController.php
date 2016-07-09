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
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Jalali;
/**
 * @Route("/admin" )
 */
class AdminController extends Controller {

    /**
     * @Route("/",name="_admin")
     * @Template("BlogBundle::Admin/index.html.twig")
     */
    public function indexAction() {
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents('../app/config/admin.yml'));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
        $session = $this->get('Session');
        if (!$session->get('myconfig')) {
            $session->set('myconfig', $value);
        }
        return array('entity' => json_encode($value));
    }

    /**
     * @Route("/forum",name="forum_admin")
     * @Template("ForumBundle::AdminForum:index.html.twig")
     */
    public function forumAction() {
        return array();
    }

    /**
     * @Route("/ShowNewAnimals")
     */
    public function ShowNewAnimals() {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->query(sprintf("select * from animals where active = '0' order by id desc limit 10"));
        $statement->execute();
        $q = $statement->fetchAll();
        if (!empty($q)) {
            $data = array();
            $i = 0;
            foreach ($q as $val) {
                $data['animale'][$i]['name'] = $val['name'];
                $data['animale'][$i]['id'] = $val['id'];
                $data['animale'][$i]['active'] = $val['active'];
                $data['animale'][$i]['sex'] = $val['sex'];
                $data['animale'][$i]['microChip'] = $val['microChip'];
                $data['animale'][$i]['codeParvande'] = $val['codeParvande'];
                $data['animale'][$i]['user_id'] = $val['user_id'];
                if ($val['created_at'] != '') {
                    $DateTime = $val['created_at'];
                    $DateTime = new \DateTime($DateTime);
                    $DateTime = $DateTime->format('Y-m-d');
                    $DateTime = explode('-', $DateTime);
                    $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                    $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                    $data['animale'][$i]['cteateAt'] = $DateTime;
                } else {
                    $data['animale'][$i]['cteateAt'] = '';
                }
                $i++;
            }
            $result = \json_encode($data);
            return new Response($result);
        }
        return new Response('Not Info');
    }

}
