<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/forum")
 */
class DefaultController extends Controller
{

     /**
     * @Route("/",name="ShowForumClient")
     * @Template("ForumBundle:ClientForum:index.html.twig")
     */
    public function ForumAction()
    {
        $menu = $this->getDoctrine()->getRepository('BlogBundle:Menu')->findAll();
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->findAll();

        $data = array();
        $data['ContentCategory']=array();
        $data['menu']=array();
        $j = 0;
        foreach ($ContentCategory as $value) {
            if ($value->getActive() == 1) {
                $data['ContentCategory'][$j]['title'] = $value->getTitle();
                $data['ContentCategory'][$j]['slug'] = $value->getSlug();
                $data['ContentCategory'][$j]['id'] = $value->getId();
                $j++;
            }
        }


        $i = 0;
        foreach ($menu as $value) {
            if ($value->getPage() != NULL && $value->getActive() == 1) {
                $data['menu'][$i]['title'] = $value->getTitle();
                if ($value->getParent() != NULL) {
                    $data['menu'][$i]['parent'] = $value->getParent();
                    $data['parent'][] = $value->getParent()->getId();
                } else {
                    $data['menu'][$i]['parent'] = -1;
                }
                $data['menu'][$i]['page'] = $value->getPage();
                $data['menu'][$i]['id'] = $value->getId();
                $data['menu'][$i]['parent'] = $value->getParent();
                $i++;
            }
        }
        $data['parent']=array();
        for ($j = 0; $j < count($data['menu']); $j++) {
            if (in_array($data['menu'][$j]['id'], $data['parent'])) {
                foreach ($data['menu'] as $key => $child) {
                    if (isset($child['parent'])) {
                        if ($child['parent']->getId() == $data['menu'][$j]['id']) {
                            $data['menu'][$j]['child'][] = $child;
                        }
                        unset($data['menu'][$key]);
                    }
                }
            } else {
                $data['menu'][$j]['child'][0] = 0;
            }
        }

        foreach ($data['menu'] as $key => $value) {
            if (isset($value['child']) == false) {
                $data['menu'][$key]['child'][0] = 0;
            }
        }
        return array('data'=>$data);
    }
}

