<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface {

    private $router;
    private $session;

    /**
     * Constructor
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct(RouterInterface $router, Session $session) {
        $this->router = $router;
        $this->session = $session;
        $yaml = new Parser();
        $result = $yaml->parse(\file_get_contents(__DIR__ . "../../yaml/KeyValue.yml"));
        if (!empty($result)) {
            $session->start();
            $session->set('KeyValue', $result);
        }
    }

    /**
     * onAuthenticationSuccess
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	TokenInterface $token
     * @return 	Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        // if AJAX login
        if ($request->isXmlHttpRequest()) {

            $array = array('success' => true, 'role' => $token->getRoles()[0]->getRole()); // data to return via JSON
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

            // if form login 
        } else {

            if ($this->session->get('_security.main.target_path')) {

                $url = $this->session->get('_security.main.target_path');
            } else {
                $url = $this->router->generate('panel');
            } // end if

            return new RedirectResponse($url);
        }
    }

    /**
     * onAuthenticationFailure
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	AuthenticationException $exception
     * @return 	Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        // if AJAX login
        if ($request->isXmlHttpRequest()) {
            $message = '';
            switch ($exception->getMessage()) {
                case 'Username could not be found.': $message = 'کاربری با این مشخصات یافت نشد.';
                    break;
                case 'Bad credentials.':$message = 'اطلاعات وارد شده نامعتبر است.';
                    break;
                case 'User account has expired.':$message = 'حساب کاربری شما منقضی شده است، با مدیر سایت تماس بگیرید.';
                    break;
                case 'User account is disabled.':$message = 'حساب کاربری شما فعال نیست.';
                    break;
                default :$message = $exception->getMessage();
            }
            $array = array('success' => false, 'message' => $message); // data to return via JSON
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

            // if form login 
        } else {

            // set authentication exception to session
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse($this->router->generate('fos_user_security_login'));
        }
    }

}
