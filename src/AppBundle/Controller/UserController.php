<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\UserDocument;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends FOSRestController {

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getUserAction() {
        return array($this->getUser());
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getUserMojodiHesabAction() {
        ///cli_panel/user/mojodi/hesab.{_format} 
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser()->getId());
        return array(
            'monyUser' => $user->getMoney()
        );
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteDocumentUserDeleteAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AppBundle:UserDocument')->find($id);
        $em->remove($entity);
        $em->flush();
        return array('deleteMessage' => 0);
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postUserDocumentInsertAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        $entity = new UserDocument();
        $entity->setUser($user);
        $entity->setDocumentTitle($request->request->get('documentTitle'));
        $photo = $request->request->get('photo');

        if ($photo) {
            $entity->setPhoto($photo);
        } else {
            return array(
                "message" => "-1"
            );
        }
        $em->persist($entity);
        $em->flush();

        return array(
            "message" => "0"
        );
    }
    
    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */    
    public function getDocumentUserAction() {
        return $this->getDoctrine()->getManager()->getRepository("AppBundle:UserDocument")->findBy(array("user"=>  $this->getUser()));
    }
    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postUserInfoAction(Request $request) {
//        $em = $this->getDoctrine()->getEntityManager();
//        $user = $this->container->get('security.token_storage')->getToken()->getUser();
//
//        $user->setName($request->request->get('name'));
//        $user->setFamily($request->request->get('family'));
//        $user->setEmail($request->request->get('email'));
//        $user->setPhone($request->request->get('phone'));
//        $user->setMobile($request->request->get('mobile'));
//        $user->setAddress($request->request->get('address'));
//
//        $photo = $request->request->get('photo');
//
//        if ($photo) {
//            $user->setPhoto($photo);
//        }
//
//        $em->flush();
//
//        return array(
//            "message" => ""
//        );
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postUserPasswordAction(Request $request) {

        $user = $this->getUser();
        $user->setPlainPassword($request->request->get('password'));
        $this->get('fos_user.user_manager')->updateUser($user, false);
        $this->getDoctrine()->getManager()->flush();
        return array();
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getFactorUserAction() {
        ///cli_panel/user/mojodi/hesab.{_format} 
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser()->getId());

        $em = $this->getDoctrine()->getEntityManager();
        $Repository = $em->getRepository('ShopBundle:Factor');
        $query = $Repository->createQueryBuilder('p');
        $query->Where('p.customer = :user')
                ->setParameter('user', $user)
                ->setMaxResults(10)
                ->setFirstResult(0)
                ->orderBy('p.date', 'desc');

        $q = $query->getQuery();
        $result = $q->getResult();

        return array(
            'factor' => $result
        );
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function getBasketFactorAction($idFactor) {
        $factor = $this->getDoctrine()->getRepository('ShopBundle:Factor')->find($idFactor);
        $basket = $this->getDoctrine()->getRepository('ShopBundle:Basket')->findByFactor(array('factor' => $factor));

        return array(
            'basket' => $basket
        );
    }

    /**
     * Delete action
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteFactorRemoveAction($idFactor) {
        ///cli_panel/factors/{idFactor}/remove.json
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('ShopBundle:Factor')->find($idFactor);
        $em->remove($entity);
        $em->flush();
        return array('deleteMessage' => '0');
    }

    /**
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postRedirectcallbackurlAction(Request $request) {
        $data = $request->request->all();

        $session = $this->getSession();
        $total = $session->get('total');
        $orderId = $session->get('orderId');
        $refSaleID = $session->get('refID');


        if ($data['ResCode'] == "0") {
            try {
                $client = new \Soapclient('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
            } catch (Exception $e) {
                return array(
                    'error' => '1',
                    'message' => 'سرور بانک پاسخگو نمی باشد'
                );
            }

            $em = $this->getDoctrine()->getEntityManager();
            $optionsApplication = $em->getRepository('AppBundle:TableOptions')->findAll();

            $config['Terminalid'] = $optionsApplication[0]->getOptionValue();
            $config['userName'] = $optionsApplication[1]->getOptionValue();
            $config['password'] = $optionsApplication[2]->getOptionValue();

            $parameters = array(
                'terminalId' => $config['Terminalid'],
                'userName' => $config['userName'],
                'userPassword' => $config['password'],
                'orderId' => $orderId,
                'saleOrderId' => $orderId,
                'saleReferenceId' => $refSaleID);

            $result = $client->bpVerifyRequest($parameters);
            $res = explode(',', $result->return);

            if (is_array($res)) {
                if ($res[0] == "0") {

                    $resultsettle = $client->bpSettleRequest($parameters);
                    $resultStrsettle = $resultsettle->return;
                    $ressettle = explode(',', $resultStrsettle);
                    $ResCodesettle = $ressettle[0];

                    if ($ResCodesettle == "0") {
                        //pardakht movafagh
                        //return $this->render('default/resaultPayment.html.twig', array('customer' => $order));
                        return $this->redirect($this->generateUrl('app_default_index', array('success' => 'با موفقیت پرداخت شد'), UrlGeneratorInterface::ABSOLUTE_URL)."#/shopInfo");
                    }
                } else {
                    //pardakht na movafagh
                    return $this->redirect($this->generateUrl('app_default_index', array('error' => 'شما موفق به پرداخت نشدید'), UrlGeneratorInterface::ABSOLUTE_URL)."#/shopInfo");
                }
            } else {
                //$this->get('session')->getFlashBag()->set('msg', 'کاربر گرامی، در حال حاظر سرور بانک برای تایید خرید پاسخگو نمی باشد.');
                return $this->redirect($this->generateUrl('app_default_index', array('error' => $this->responseError($result)), UrlGeneratorInterface::ABSOLUTE_URL)."#/shopInfo");
            }
        } else {
            // etelate session moshkel dare
             return $this->redirect($this->generateUrl('app_default_index', array('error' => 'اطلاعات سشن شناسایی نشد'), UrlGeneratorInterface::ABSOLUTE_URL)."#/shopInfo");
        }
        return $this->redirect($this->generateUrl('app_default_index', array('error' => $this->responseError($result)), UrlGeneratorInterface::ABSOLUTE_URL)."#/shopInfo");
    }

    /**
     * 
     * @return array
     * @View()
     *
     * @throws NotFoundHttpException when post not exist
     */
    public function postPaymentMellatAction($money) {
        $client = null;
        try {
            $client = new \Soapclient('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        } catch (Exception $e) {
            //$this->get('session')->getFlashBag()->set('msg', '1کاربر گرامی، در حال حاظر سرور بانک پاسخگو نمی باشد.');
            return array(
                'error' => '1',
                'message' => 'سرور بانک پاسخگو نمی باشد'
            );
        }

        $milliseconds = round(microtime(true) * 1000);
        $OrderId = $milliseconds + rand(0, $milliseconds);


        $em = $this->getDoctrine()->getEntityManager();
        $optionsApplication = $em->getRepository('AppBundle:TableOptions')->findAll();

        //$config = $session->get('config');

        $config['terminalId'] = $optionsApplication[0]->getOptionValue();
        $config['userName'] = $optionsApplication[1]->getOptionValue();
        $config['password'] = $optionsApplication[2]->getOptionValue();

        $parameters = array(
            'terminalId' => $config['terminalId'],
            'userName' => $config['userName'],
            'userPassword' => $config['password'],
            'orderId' => $OrderId,
            'amount' => $money,
            'localDate' => date('Ymd'),
            'localTime' => date('His'),
            'additionalData' => null,
            'callBackUrl' => $this->generateUrl('post_redirectcallbackurl', array(), UrlGeneratorInterface::ABSOLUTE_URL),
            'payerId' => 0);

        $result = $client->bpPayRequest($parameters);
        $res = explode(',', $result->return);
        if (is_array($res)) {
            $ResCode = $res[0];
            if ($ResCode == "0") {

                $Session = $this->getSession();

                $Session->set($res[1], array(
                    'user' => $this->getUser()->getId(),
                    'total' => $money,
                    'orderId' => $OrderId,
                    'refID' => $res[1]
                ));

                return array('formRefId' => '<script language="javascript" type="text/javascript"> 
				function postRefId (refIdValue) {
				var form = document.createElement("form");
				form.setAttribute("method", "POST");
				form.setAttribute("action", "https://pgws.bpm.bankmellat.ir/pgwchannel/startpay.mellat");         
				form.setAttribute("target", "_self");
				var hiddenField = document.createElement("input");              
				hiddenField.setAttribute("name", "refid");
				hiddenField.setAttribute("value", refIdValue);
				form.appendChild(hiddenField);
				document.body.appendChild(form);         
				form.submit();
				document.body.removeChild(form);
			}
			postRefId("' . $res[1] . '");</script>');
            } else {
                return array(
                    'error' => '1',
                    'message' => 'با برنامه نویس جهت اطلاعات درگاه بانک تماس بگیرید'
                );
            }
        } else {
            return array(
                'error' => '1',
                'message' => $this->responseError($result)
            );
        }
    }

    private function responseError($number) {
        switch ($number) {
            case 31 :
                $err = "پاسخ نامعتبر است!";
                break;
            case 17 :
                $err = "کاربر از انجام تراکنش منصرف شده است!";
                break;
            case 21 :
                $err = "پذیرنده نامعتبر است!";
                break;
            case 25 :
                $err = "مبلغ نامعتبر است!";
                break;
            case 34 :
                $err = "خطای سیستمی!";
                break;
            case 41 :
                $err = "شماره درخواست تکراری است!";
                break;
            case 421 :
                $err = "ای پی نامعتبر است!";
                break;
            case 412 :
                $err = "شناسه قبض نادرست است!";
                break;
            case 45 :
                $err = "تراکنش از قبل ستل شده است";
                break;
            case 46 :
                $err = "تراکنش ستل شده است";
                break;
            case 35 :
                $err = "تاریخ نامعتبر است";
                break;
            case 32 :
                $err = "فرمت اطلاعات وارد شده صحیح نمیباشد";
                break;
            case 43 :
                $err = "درخواست verify قبلا صادر شده است";
                break;
        }
        return $err;
    }

}
