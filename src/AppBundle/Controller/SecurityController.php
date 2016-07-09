<?php

namespace AppBundle\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController {

    public function loginAction(Request $request) {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider') ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;
        }
        if ($request->isXmlHttpRequest()) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }

        return $this->renderLogin(array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    'csrf_token' => $csrfToken,
                    'data' => $this->getMenu()
        ));
    }

    private function getMenu() {
        $menu = $this->getDoctrine()->getRepository('BlogBundle:Menu')->findAll();
        $ContentCategory = $this->getDoctrine()->getRepository('BlogBundle:ContentCategory')->findAll();

        $data = array();
        $j = 0;
        $data['ContentCategory'] = array();
        foreach ($ContentCategory as $value) {
            if ($value->getActive() == 1) {
                $data['ContentCategory'][$j]['title'] = $value->getTitle();
                $data['ContentCategory'][$j]['slug'] = $value->getSlug();
                $data['ContentCategory'][$j]['id'] = $value->getId();
                $j++;
            }
        }


        $i = 0;
        $data['menu'] = array();
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
        $data['parent'] = array();
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
        return $data;
    }

}
