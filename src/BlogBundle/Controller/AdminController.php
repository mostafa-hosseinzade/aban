<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/admin" )
 */
class AdminController extends Controller {
    
    /**
     * @Route("/profile_admin",name="profile_admin")
     */
    public function getUserInfoAction() {
        $user = $this->getUser();
        $data = array(
            'name' => $user->getName(),
            'family' => $user->getFamily(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile()
        );
        return new JsonResponse($data);
    }

    /**
     * @Route("/profile_update",name="profile_update")
     */
    public function updateUserAction(Request $request) {
        $data=$request->request->all();
        $userManager = $this->get('fos_user.user_manager');
        $user = $this->getUser();
        $user->setName($data['name']);
        $user->setFamily($data['family']);
        $user->setMobile($data['mobile']);
        $user->setEmail($data['email']);
        $userManager->updateUser($user);
        return new JsonResponse(true);
    }
    
    /**
     * @Route("/profile_update_password",name="profile_update_password")
     */
    public function updatePasswordUserAction(Request $request) {
        if($this->getUser()->getId()== 2){
            return new JsonResponse(true);
        }
        $data=$request->request->all();
        $userManager = $this->get('fos_user.user_manager');
        $user = $this->getUser();
        $user->setPlainPassword($data['password']);
        $userManager->updateUser($user);
        return new JsonResponse(true);
    }
    
}
