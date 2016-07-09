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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Certificateofdoctors;
use AppBundle\Entity\TimeDoctor;
use AppBundle\Service\Jalali;
use AppBundle\Entity\ReserveTime;

/**
 * @Route("/doc_panel")
 */
class DocPanelController extends Controller {

    /**
     * @Route("/",name="doctor_panel")
     * @Template("AppBundle::doc_panel.html.twig")
     */
    public function indexAction() {

        return array();
    }

    /**
     * @Route("/user")
     * @return array
     * 
     */
    public function FindUser() {
        $user = $this->getUser();

        $data['user']['name'] = $user->getName();
        $data['user']['family'] = $user->getFamily();
        $data['user']['email'] = $user->getEmail();
        $data['user']['phone'] = $user->getPhone();
        $data['user']['mobile'] = $user->getMobile();
        $data['user']['address'] = $user->getAddress();
        $data['user']['photo'] = $user->getPhoto();
        $data['user']['username'] = $user->getUserName();
        if (!empty($data)) {
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    /**
     * @Route("/user/edit")
     * @return array
     * 
     */
    public function EditUserInfo(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
            $entity->setName($request->get('name'));
            $entity->setFamily($request->get('family'));
            $entity->setPhone($request->get('phone'));
            $entity->setEmail($request->get('email'));

            $entity->setPhoto($request->get('photo'));

            $entity->setAddress($request->get('address'));
            $entity->setMobile($request->get('mobile'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $data['msg'] = "ویرایش اطلاعات;اطلاعات با موفقیت ویرایش شد;success;true";
            $response = \json_encode($data);
            return new Response($response);
        } catch (\Exception $e) {
            $data['msg'] = "ویرایش اطلاعات;مشکل در ویرایش اطلاعات با پشتیبانی تماس بگیرید;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
    }

    /**
     * @Route("/user/edit/password")
     * @return array
     * 
     */
    public function EditUserPassword(Request $request) {
        try {
            $em = $this->get('fos_user.user_manager');
            $this->getUser()->setPlainPassword($request->get('password'));
            $em->updateuser($this->getUser());
            $data['msg'] = "ویرایش اطلاعات;اطلاعات با موفقیت ویرایش شد;success;true";
            $response = \json_encode($data);
            return new Response($response);
        } catch (\Exception $e) {
            $data['msg'] = "ویرایش اطلاعات;مشکل در ویرایش اطلاعات با پشتیبانی تماس بگیرید;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
    }

    /**
     * @Route("/usercertificate")
     * @return array
     * 
     */
    public function UserCertificate() {
        $em = $this->getDoctrine()->getManager();
        $ResultCertificate = $em->getRepository('AppBundle:Certificateofdoctors')->findBy(array('user' => $this->getUser()));
        $data = array();
        $j = 0;
        foreach ($ResultCertificate as $val) {
            $data['certificate'][$j]['desc'] = $val->getCertificate();
            $data['certificate'][$j]['photo'] = $val->getPhoto();
            $data['certificate'][$j]['expiry_date'] = $val->getExpirydate();
            $data['certificate'][$j]['dateOfRegistration'] = $val->getdateOfRegistration();
            $data['certificate'][$j]['id'] = $val->getId();
            $j++;
        }
        if (!empty($data)) {
            $data['LengthCertificate'] = count($data['certificate']);
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    /**
     * @Route("/usercertificateadd")
     * @return array
     * 
     */
    public function UserCertificateAdd(Request $request) {
        if (empty($request->get('certificate'))) {
            $data['msg'] = "مدارک;اطلاعات مربوط به مدارک آپلود نشده است;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $entity = new Certificateofdoctors();
        $entity->setCertificate($request->get('certificate'));
        $entity->setDateOfRegistration($request->get('DateOfRegistration'));
        $entity->setExpiryDate($request->get('expiryDate'));
        $entity->setPhoto($request->get('photo'));
        $entity->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();

        $em->persist($entity);
        $em->flush();
        $data['msg'] = "مدارک;اطلاعات با موفقیت ثبت شد;success;true";
        $data['new']['id'] = $entity->getId();
        $data['new']['expiry_date'] = $entity->getExpirydate();
        $data['new']['dateOfRegistration'] = $entity->getDateofregistration();
        $data['new']['photo'] = $entity->getPhoto();
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/usercertificatedelete/{id}")
     * @return array
     * 
     */
    public function UserCertificateDelete($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Certificateofdoctors')->find($id);
        $em->remove($entity);
        $em->flush();

        return new Response('ok deleted id is : ' . $id);
    }

    /**
     * @Route("/usertime")
     * @return array
     * 
     */
    public function UserTime() {
        $em = $this->getDoctrine()->getManager();
        $entity2 = $em->getRepository('AppBundle:TimeDoctor')->findBy(array('user' => $this->getUser()));
        $data = array();
        $i = 0;
        if (!empty($entity2)) {
            foreach ($entity2 as $entity) {
                $data[$i]['Id'] = $entity->getId();
                $data[$i]['One'] = $entity->getOne();
                $data[$i]['Tow'] = $entity->getTow();
                $data[$i]['Three'] = $entity->getThree();
                $data[$i]['Four'] = $entity->getFour();
                $data[$i]['Five'] = $entity->getFive();
                $data[$i]['Six'] = $entity->getSix();
                $data[$i]['Seven'] = $entity->getSeven();
                $date = $entity->getStarted();
                $date = $date->format('Y/n/j');
                $year = explode('/', $date);
                $data[$i]['started'] = Jalali::gregorian_to_jalali($year[0], $year[1], $year[2]);
                $i++;
            }
        } else {
            $this->UserTimeAdd();
            $entity2 = $em->getRepository('AppBundle:TimeDoctor')->findBy(array('user' => $this->getUser()));
            foreach ($entity2 as $entity) {
                $data[$i]['Id'] = $entity->getId();
                $data[$i]['One'] = $entity->getOne();
                $data[$i]['Tow'] = $entity->getTow();
                $data[$i]['Three'] = $entity->getThree();
                $data[$i]['Four'] = $entity->getFour();
                $data[$i]['Five'] = $entity->getFive();
                $data[$i]['Six'] = $entity->getSix();
                $data[$i]['Seven'] = $entity->getSeven();
                $date = $entity->getStarted();
                $date = $date->format('Y/n/j');
                $year = explode('/', $date);
                $data[$i]['started'] = Jalali::gregorian_to_jalali($year[0], $year[1], $year[2]);
                $i++;
            }
        }
        if (!empty($data)) {
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    public function UserTimeAdd() {
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime();
        $date = $date->format('Y/n/j');
        $year = explode('/', $date);
        $jalali_date = Jalali::gregorian_to_jalali($year[0], $year[1], $year[2]);
        $Gregorian = Jalali::jalali_to_gregorian($jalali_date[0], 1, 1);

        $date = new \DateTime($Gregorian[0] . "-" . $Gregorian[1] . "-" . $Gregorian[2]);
        for ($i = 1; $i < 53; $i++) {
            $entity2 = new TimeDoctor();
            $entity2->setStarted($date);
            $date->add(new \DateInterval('P7D'));
            $entity2->setUser($this->getUser());
            $entity2->setOne('false;-|-');
            $entity2->setTow('false;-|-');
            $entity2->setThree('false;-|-');
            $entity2->setFour('false;-|-');
            $entity2->setFive('false;-|-');
            $entity2->setSix('false;-|-');
            $entity2->setSeven('false;-|-');
            $em->persist($entity2);
            $em->flush();
        }
        return new Response('ok');
    }

    /**
     * @Route("/usertimeedit/{id}")
     * @return array
     * 
     */
    public function UserTimeEdit(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $CountWeek = $request->get('CountWeek');
        if ($CountWeek == 0) {
            $entity = $em->getRepository('AppBundle:TimeDoctor')->find($id);
            $entity->setUser($this->getUser());
            $entity->setOne($request->get('One'));
            $entity->setTow($request->get('Tow'));
            $entity->setThree($request->get('Three'));
            $entity->setFour($request->get('Four'));
            $entity->setFive($request->get('Five'));
            $entity->setSix($request->get('Six'));
            $entity->setSeven($request->get('Seven'));
            $em->persist($entity);
        } else {
            for ($i = 0; $i < $CountWeek; $i++) {
                $entity = $em->getRepository('AppBundle:TimeDoctor')->find($id + $i);
                $entity->setUser($this->getUser());
                $entity->setOne($request->get('One'));
                $entity->setTow($request->get('Tow'));
                $entity->setThree($request->get('Three'));
                $entity->setFour($request->get('Four'));
                $entity->setFive($request->get('Five'));
                $entity->setSix($request->get('Six'));
                $entity->setSeven($request->get('Seven'));
                $em->persist($entity);
            }
        }
        $em->flush();
        return new Response('زمانبندی کاربر ;اطلاعات با موفقیت ویرایش شد;success;true');
    }

    /**
     * @Route("/usertimereserve")
     * @return array
     * 
     */
    public function UserTimeReserve() {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $q = $conn->query(sprintf("select o.name,o.family,o.phone,o.mobile,a.name,a.age,a.sex,a.microChip,p._desc,p.number,p._date,p.active,p.id,e.fieldtime from timedoctor t join ticket p on t.id = p.time_doctor_id "
                        . " join animals a on p.animale_id = a.id join fos_user o on a.user_id = o.id LEFT JOIN reserve_time e on p.id = e.ticket_id "
                        . "where t.user_id = %s", $this->getUser()->getId()));
        $result = $q->fetchAll();

        $i = 0;
        foreach ($result as $value) {
            $data['reserve'][$i]['username'] = $value['name'];
            $data['reserve'][$i]['phone'] = $value['phone'];
            $data['reserve'][$i]['mobile'] = $value['mobile'];
            $data['reserve'][$i]['family'] = $value['family'];

            $time = $value['_date'];
            $time = explode('-', $time);
            $jalalidate = Jalali::gregorian_to_jalali($time[0], $time[1], $time[2]);
            $data['reserve'][$i]['date'] = $jalalidate[0] . '-' . $jalalidate[1] . '-' . $jalalidate[2];

            $data['reserve'][$i]['ticketnumber'] = $value['number'];
            $data['reserve'][$i]['active'] = $value['active'];

            if (isset($value['fieldtime'])) {
                $data['reserve'][$i]['fieldtime'] = $value['fieldtime'];
            }

            $data['reserve'][$i]['ticketid'] = $value['id'];
            $data['reserve'][$i]['animalename'] = $value['name'];
            $data['reserve'][$i]['animaleage'] = $value['age'];
            $data['reserve'][$i]['animalesex'] = $value['sex'];
            $data['reserve'][$i]['animalecode'] = $value['microChip'];
            $data['reserve'][$i]['desc'] = $value["_desc"];
            $i++;
        }
        if (!empty($data)) {
            $value = \json_encode($data);
        } else {
            $value = 'Not Info';
        }
        return new Response($value);
    }

    /**
     * @Route("/usertimereserveenable/{id}")
     * @return array
     */
    public function UserTimeReserveEnable(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Ticket')->find($id);

        if (!$entity) {
            return new Response(" رزرواسیون:مشکل در اطلاعات ارسال شده:error:true");
        }
        if ($request->get('time') == "__:__" and $request->get('enable') == 1) {
            return new Response("رزرواسیون:فیلد زمان نمی تواند خالی باشد:error:true");
        }
        if ($request->get('enable') == 'false') {
            $entity->setActive(false);
            $em->flush();
            return new Response("رزرواسیون:اطلاعات با موفقیت ویرایش شد:success:true");
        }
        $HourConvert = explode(':', $request->get('time'));
        $TimeUser = date('h:i:s A', strtotime($request->get('time') . ':00'));
        $date = $entity->getDate();
        $date = $date->format('Y-m-d');
        $date = "'" . $date . " 00:00:00'";
        $conn = $em->getConnection();
        $q = $conn->query(sprintf("select a.fieldtime,t._date from ticket t LEFT join reserve_time a on a.ticket_id = t.id  "
                        . "where t.time_doctor_id = " . $entity->getTimeDoctor()->getId() . " and t.id <> " . $id . " and t.active = 1 and  t._date = " . $date . "  and a.user_id = %s", $this->getUser()->getId()));
        $result = $q->fetchAll();

        $error = 0;
        if ($result) {
            foreach ($result as $val) {
                $low = date('h:i:s A', strtotime($val['fieldtime']) - 1200);
                $cent = date('h:i:s A', strtotime($val['fieldtime']));
                $high = date('h:i:s A', strtotime($val['fieldtime']) + 1200);
                $splitTimeUser = explode(' ', $TimeUser);
                $splitTimeDataBase = explode(' ', $cent);

                if ($TimeUser > $low and
                        $TimeUser < $high &&
                        $splitTimeDataBase[1] == $splitTimeUser[1]
                ) {
                    $error = 1;
                }
            }
        }

        if ($error == 1) {
            return new Response("رزرواسیون:زمان انتخاب شده با زمان های قبلی تداخل دارد حداقل فاصله زمانی ۲۰ دقیقه می باشد:error:true");
        }

        if ($HourConvert[0] > 12) {
            $TimeUser = explode(':', $TimeUser);
            $hour = $TimeUser[0] + 12;
            $TimeUser = $hour . ':' . $TimeUser[1] . ':' . $TimeUser[2];
        }

        $TimeUser = explode(' ', $TimeUser); // $TimeUser
        $TimeUser = $TimeUser[0];
        $reserve_time = $em->getRepository('AppBundle:ReserveTime')->findOneBy(array('ticket' => $id));
        $TimeUser = new \DateTime($TimeUser);

        if (!$reserve_time) {
            $reserve = new ReserveTime();
            $reserve->setTicket($entity);
            $reserve->setUser($this->getUser());
            $reserve->setFieldtime($TimeUser);
            $em->persist($reserve);
            $em->flush();
        } else {

            $reserve_time->setFieldtime($TimeUser);
            $em->flush();
        }

        $entity->setActive(true);
        $em->flush();
        return new Response("زمانبندی کاربر:اطلاعات با موفقیت ویرایش شد:success:true");
    }

}
