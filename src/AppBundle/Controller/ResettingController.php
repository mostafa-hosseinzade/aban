<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Service\HelperFunction;

/**
 * Controller managing the resetting of the password
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResettingController extends BaseController {

    //http://my.sepehritc.com
    private $UrlMsg = "http://87.107.121.54/post/send.asmx?wsdl";
    private $UserNameMsg = "amin_kh_s";
    private $PasswordMsg = "9NSJMOet";
    private $SenderNumber = "30001220000048";

    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction(Request $request) {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);
        $mobile = false;
        if (null === $user) {
            $user = $this->getDoctrine()->getManager()
                    ->getRepository("AppBundle:User")
                    ->findOneBy(array("mobile" => $username));
            if (null == $user) {
                return new \Symfony\Component\HttpFoundation\JsonResponse(array('invalid_username' => $username, 'message' => "نام کاربری یا ایمیل یا شماره موبایل $username  موجود نیست."));
            }
            $mobile = true;
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array('request_duplicate' => $username, 'message' => "کلمه عبور برای این کاربر هم اکنون طی ۲۴ ساعت گذشته درخواست شده است."));
        }


        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }
        $pass = mt_rand(1000, 9999999);
        $user->setPlainPassword($pass);
        $smsMessage = "رمز عبور شما با موفقیت تغییر یافت رمز عبور جدید : " . $pass;
        HelperFunction::SMS($this->UrlMsg, $this->UserNameMsg, $this->PasswordMsg, $this->SenderNumber, $username, $smsMessage);
        try{
        $this->sendResettingEmailMessage($user, 'FOSUserBundle:Resetting:email.html.twig');
}catch(\Exception $e){}
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);
        return new \Symfony\Component\HttpFoundation\JsonResponse(array('email' => $this->getObfuscatedEmail($user), 'mobile' => $username));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction(Request $request) {
        $email = $request->query->get('email');

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return array('email' => $email);
    }

    /**
     * Reset user password
     */
    public function resetAction(Request $request, $token) {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'not_user' => 'TRUE',
                'message' => 'کاربری با این مسیر یافت نشد..',
            ));
        }

        if ($request->request->get('plainPassword') !== null) {
            $user->setPlainPassword($request->request->get('plainPassword'));

            $userManager->updateUser($user);

            $url = $this->generateUrl('fos_user_profile_show');
            $response = new RedirectResponse($url);

            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return new \Symfony\Component\HttpFoundation\JsonResponse(array(
                'role' => $user->getRoles()[0],
                'message' => 'کلمه عبور شما با موفقیت تغییر یافت.',
            ));
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'invali_form' => $token,
            'message' => 'اطلاعات شما نامعتبر است.',
        ));
    }

    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user) {
        $email = $user->getEmail();
//        if (false !== $pos = strpos($email, '@')) {
//            $email = '...' . substr($email, $pos);
//        }

        return $email;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user) {
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    /**
     * {@inheritdoc}
     */
    public function sendResettingEmailMessage(UserInterface $user, $themplate) {
        $url = $user->getConfirmationToken();
        $rendered = $this->renderView($themplate, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, 'بازنشانی کلمه عبور', 'info@abanpet.com', $user->getEmail());
    }

    /**
     * @param string $renderedTemplate
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $subject, $fromEmail, $toEmail) {

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($renderedTemplate, 'text/html');
        $this->get('mailer')->send($message);
    }

}
