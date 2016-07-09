<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Service\Jalali;
use AppBundle\Entity\Animals;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Animalsphoto;
use AppBundle\Entity\Historyofanimalexamination;
use AppBundle\Service\HelperFunction;
use AppBundle\Entity\Activity;

/**
 * @Route("/admin/UserManager")
 */
class UserManagerController extends Controller {

    private $UrlMsg = "http://87.107.121.54/post/send.asmx?wsdl";
    private $UserNameMsg = "amin_kh_s";
    private $PasswordMsg = "9NSJMOet";
    private $SenderNumber = "30001220000048";

    /**
     * @Route("/",name="UserManager")
     * @Template("AppBundle::UserManager.html.twig")
     */
    public function index() {
        return array();
    }

    ////////////////////////
    // User Code
    ///////////////////////
    /**
     * @Route("/UserAll")
     */
    public function UserAll() {
        $em = $this->getDoctrine()->getManager();
        $c = $em->getConnection();
        $count = $c->query(sprintf("select count(*) from fos_user"));
        $count = $count->fetchAll();
        if (empty($count)) {
            $data['count'] = 0;
        } else {
            $data['count'] = $count[0]['count(*)'];
        }
        $response = \json_encode($data);
        return new Response($response);
        $Repository = $em->getRepository("AppBundle:User");
        $q = $Repository->createQueryBuilder('p')->
                where('p.roles = :roles')->
                setParameter('roles', 'a:0:{}')->
                getQuery();
        $user_all = $q->getResult();

        $data = array();
        $i = 0;
        foreach ($user_all as $value) {
            $data['user'][$i]['username'] = $value->getUserName();
            $data['user'][$i]['name'] = $value->getName();
            $data['user'][$i]['family'] = $value->getFamily();
            $data['user'][$i]['id'] = $value->getId();
            $data['user'][$i]['mobile'] = $value->getMobile();
            $data['user'][$i]['phone'] = $value->getPhone();
            $data['user'][$i]['sex'] = $value->getSex();
            $data['user'][$i]['postcode'] = $value->getPostCode();
            $data['user'][$i]['money'] = $value->getMoney();
            $data['user'][$i]['address'] = $value->getAddress();
            $data['user'][$i]['email'] = $value->getEmail();
            $data['user'][$i]['enabled'] = $value->getEnabled();

            if ($value->getLastLogin() != '') {
                $DateTime = $value->getLastLogin();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['lastlogin'] = $DateTime;
            } else {
                $data['user'][$i]['lastlogin'] = '';
            }
            $data['user'][$i]['locked'] = $value->getLocked();
            $data['user'][$i]['expired'] = $value->isExpired();

            if ($value->getExpiresAt() != '') {
                $DateTime = $value->getExpiresAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['expires_at'] = $DateTime;
            } else {
                $data['user'][$i]['expires_at'] = '';
            }

            if ($value->getCreateAt() != '') {
                $DateTime = $value->getCreateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['createAt'] = $DateTime;
            } else {
                $data['user'][$i]['createAt'] = '';
            }

            if ($value->getUpdateAt() != '') {
                $DateTime = $value->getUpdateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['updateAt'] = $DateTime;
            } else {
                $data['user'][$i]['updateAt'] = '';
            }
            $data['user'][$i]['photo'] = $value->getPhoto();
            $i++;
        }
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * @Route("/ShowUser/{id}")
     */
    public function ShowUser($id) {
        $em = $this->getDoctrine()->getManager();
        $value = $em->getRepository("AppBundle:User")->find($id);
        if (empty($value)) {
            return new Response('Not Found User');
        }
        $data['user']['username'] = $value->getUserName();
        $data['user']['name'] = $value->getName();
        $data['user']['family'] = $value->getFamily();
        $data['user']['id'] = $value->getId();
        $data['user']['mobile'] = $value->getMobile();
        $data['user']['phone'] = $value->getPhone();
        $data['user']['sex'] = $value->getSex();
        $data['user']['postcode'] = $value->getPostCode();
        $data['user']['money'] = $value->getMoney();
        $data['user']['address'] = $value->getAddress();
        $data['user']['email'] = $value->getEmail();
        $data['user']['enabled'] = $value->getEnabled();

        if ($value->getLastLogin() != '') {
            $DateTime = $value->getLastLogin();
            $DateTime = $DateTime->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
            $data['user']['lastlogin'] = $DateTime;
        } else {
            $data['user']['lastlogin'] = '';
        }
        $data['user']['locked'] = $value->getLocked();
        $data['user']['expired'] = $value->isExpired();

        if ($value->getExpiresAt() != '') {
            $DateTime = $value->getExpiresAt();
            $DateTime = $DateTime->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
            $data['user']['expires_at'] = $DateTime;
        } else {
            $data['user']['expires_at'] = '';
        }

        if ($value->getCreateAt() != '') {
            $DateTime = $value->getCreateAt();
            $DateTime = $DateTime->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
            $data['user']['createAt'] = $DateTime;
        } else {
            $data['user']['createAt'] = '';
        }

        if ($value->getUpdateAt() != '') {
            $DateTime = $value->getUpdateAt();
            $DateTime = $DateTime->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
            $data['user']['updateAt'] = $DateTime;
        } else {
            $data['user']['updateAt'] = '';
        }
        $data['user']['photo'] = $value->getPhoto();
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/users/filters/{filter}/orders/{attr}/bies/{asc}/offsets/{offset}/limits/{constPageItems}")
     */
    public function UserPaginatSearch($filter, $attr, $asc, $offset, $constPageItems) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:User');
        $c = $em->getConnection();
        $count = $c->query(sprintf("select count(*) from fos_user"));
        $count = $count->fetchAll();
        if ($count[0]['count(*)'] < 1) {
            $data['user']['msg'] = "نمایش کاربران;اطلاعاتی یافت نشد; danger; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $data = array();
        $data['count'] = $count[0]['count(*)'];

        if ($filter != -1) {
            $c = $em->getConnection();
            $q = $c->query(sprintf("select * from fos_user u  where id <> 2 and ( u.name like '%s' or u.family like '%s' or u.email like '%s'"
                            . " or u.address like '%s' or u.mobile like '%s' or u.phone like '%s' or u.post_code like '%s') order by u.%s %s limit %s OFFSET %s"
                            , '%' . $filter . '%', '%' . $filter . '%', '%' . $filter . '%', '%' . $filter . '%', '%' . $filter . '%', '%' . $filter . '%', '%' . $filter . '%'
                            , $attr, $asc, $constPageItems, $offset));
            $user = $q->fetchAll();
        } else {
            $c = $em->getConnection();
            $q = $c->query(sprintf("select * from fos_user u where id <> 2 order by u.%s %s limit %s OFFSET %s"
                            , $attr, $asc, $constPageItems, $offset));
            $user = $q->fetchAll();
        }

        $i = 0;
        foreach ($user as $value) {
            $data['user'][$i]['username'] = $value['username'];
            $data['user'][$i]['name'] = $value['name'];
            $data['user'][$i]['family'] = $value['family'];
            $data['user'][$i]['id'] = $value['id'];
            $data['user'][$i]['mobile'] = $value['mobile'];
            $data['user'][$i]['phone'] = $value['phone'];
            $data['user'][$i]['sex'] = $value['sex'];
            $data['user'][$i]['postcode'] = $value['post_code'];
            $data['user'][$i]['money'] = $value['money'];
            $data['user'][$i]['address'] = $value['address'];
            $data['user'][$i]['email'] = $value['email'];
            $data['user'][$i]['enabled'] = $value['enabled'];

            if ($value['last_login'] != '') {
                $DateTime = $value['last_login'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['lastlogin'] = $DateTime;
            } else {
                $data['user'][$i]['lastlogin'] = '';
            }
            $data['user'][$i]['locked'] = $value['locked'];
            $data['user'][$i]['expired'] = $value['expired'];
            if ($value['roles'] != "a:0:{}") {
                try {
                    $roles = explode('"', $value['roles']);
                    $roles = $roles[1];
                    $data['user'][$i]['roles'] = $roles;
                } catch (\Exception $e) {
                    $data['user'][$i]['roles'] = "a:0:{}";
                }
            } else {
                $data['user'][$i]['roles'] = $value['roles'];
            }
            if ($value['expires_at'] != '') {
                $DateTime = $value['expires_at'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['expires_at'] = $DateTime;
            } else {
                $data['user'][$i]['expires_at'] = '';
            }

            if ($value['created_at'] != '') {
                $DateTime = $value['created_at'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['createAt'] = $DateTime;
            } else {
                $data['user'][$i]['createAt'] = '';
            }

            if ($value['updated_at'] != '') {
                $DateTime = $value['updated_at'];
                $DateTime = new \DateTime($DateTime);
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['user'][$i]['updateAt'] = $DateTime;
            } else {
                $data['user'][$i]['updateAt'] = '';
            }
            $data['user'][$i]['photo'] = $value['photo'];
            $i++;
        }

        if (empty($data['user'])) {
            $data['msg'] = "جستجوی کاربران;اطلاعاتی یافت نشد; danger; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $data['count_data'] = count($data['user']);
        $result = \json_encode($data);

        return new Response($result);
    }

    /**
     * @Route("/EditUser")
     * @param Request $request
     * @Method("post")
     */
    public function EditUser(\Symfony\Component\HttpFoundation\Request $request) {
        if ($request->get('name') == '' || $request->get('family') == '' || $request->get('mobile') == '' || $request->get('email') == '') {
            return new Response('ثبت کاربر;فیلد های نام و نام خانوادگی و موبایل وایمیل نمیتواند خالی باشد;error;true');
        }
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $check = $conn->query(sprintf("select * from fos_user where id <> '%s' and (mobile = '%s' or email = '%s' or username= '%s')", $request->get('id'), $request->get('mobile'), $request->get('email'), $request->get('username')));
        $check = $check->fetchAll();
        if (!empty($check)) {
            return new Response('ثبت کاربر;فیلد ایمیل یا تلفن همراه یا نام کاربری قبلا  توسط کاربر دیگری انتخاب شده است;error;true');
        }

        $user = $em->getRepository("AppBundle:User")->find($request->get('id'));
        if (!$user) {
            return new Response('ویرایش اطلاعات کاربر;مشکل در اطلاعات ارسال شده;error;false');
        }
        $user->setName($request->get('name'));
        $user->setFamily($request->get('family'));
        $user->setMobile($request->get('mobile'));
        $user->setEmail($request->get('email'));
        $user->setUpdateAt(new \DateTime());
        $user->setPostCode($request->get('postcode'));
        $user->setAddress($request->get('address'));
        $user->setPhone($request->get('phone'));
        $user->setMoney($request->get('money'));
        if ($request->get('expired') == 1) {
            $user->setExpired(1);
        } else {
            $user->setExpired(0);
        }
        if ($request->get('username') == '') {
            $user->setUserName($request->get('mobile'));
        } else {
            $user->setUserName($request->get('username'));
        }
        if (!empty($request->get('expires_at'))) {
            $DateTime = $request->get('expires_at');
            $DateTime = explode('/', $DateTime);
            $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
            $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
            $user->setExpiresAt($DateTime);
        }
        if ($request->get('sex') == '') {
            $user->setSex(1);
        } else if ($request->get('sex') == 0) {
            $user->setSex(0);
        } else {
            $user->setSex(1);
        }
        if ($request->get('locked') == 1) {
            $user->setLocked(1);
        } else {
            $user->setLocked(0);
        }
        if ($request->get('roles') == '') {
            $user->setRoles(array('ROLE_USER'));
        } else {
            $user->setRoles(array($request->get('roles')));
        }
        if ($request->get('enabled') == '') {
            $user->setEnabled(1);
        } elseif ($request->get('enabled') == false) {
            $user->setEnabled(0);
        } else {
            $user->setEnabled(1);
        }
        if ($request->get('photo') != '') {
            $user->setPhoto($request->get('photo'));
        }

        $em->flush();
        return new Response('ویرایش اطلاعات کاربر;اطلاعات با موفقیت ویرایش شد;success;true');
    }

    /**
     * @Route("/AddUser")
     * @param Request $request
     * @Method("post")
     */
    public function AddUser(Request $request) {
        if ($request->get('name') == '' || $request->get('family') == '' || $request->get('mobile') == '' || $request->get('email') == '') {
            return new Response('ثبت کاربر;فیلد های نام و نام خانوادگی و موبایل وایمیل نمیتواند خالی باشد;error;true');
        }
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $check = $conn->query(sprintf("select * from fos_user where mobile = '%s' or email = '%s' or username= '%s'", $request->get('mobile'), $request->get('email'), $request->get('username')));
        $check = $check->fetchAll();
        if (!empty($check)) {
            return new Response('ثبت کاربر;فیلد ایمیل یا تلفن همراه یا نام کاربری قبلا انتخاب شده است;error;true');
        }
        $user = new User();
        $user->setName($request->get('name'));
        $user->setFamily($request->get('family'));
        $user->setMoney($request->get('money'));
        $user->setEmail($request->get('email'));
        $user->setMobile($request->get('mobile'));
        $user->setPostCode($request->get('postcode'));
        $user->setAddress($request->get('address'));
        $user->setCreateAt(new \DateTime());

        $user->setPhone($request->get('phone'));
        if ($request->get('roles') == '') {
            $user->addRole("ROLE_USER");
        } else {
            $user->addRole($request->get('roles'));
        }
        if ($request->get('expired') == 1) {
            $user->setExpired(1);
        } else {
            $user->setExpired(0);
        }
        if ($request->get('username') == '') {
            $user->setUserName($request->get('mobile'));
        } else {
            $user->setUserName($request->get('username'));
        }
        if ($request->get('enabled') == '') {
            $user->setEnabled(1);
        } elseif ($request->get('enabled') == false) {
            $user->setEnabled(0);
        } else {
            $user->setEnabled(1);
        }
        if ($request->get('password') == '') {
            $user->setPlainPassword($request->get('mobile'));
        } else {
            $user->setPlainPassword($request->get('password'));
        }
        if (!empty($request->get('expires_at'))) {
            $DateTime = $request->get('expires_at');
            $DateTime = explode('/', $DateTime);
            $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
            $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
            $user->setExpiresAt($DateTime);
        }
        if ($request->get('sex') == '') {
            $user->setSex(1);
        } else if ($request->get('sex') == 0) {
            $user->setSex(0);
        } else {
            $user->setSex(1);
        }
        if ($request->get('locked') == 1) {
            $user->setLocked(1);
        } else {
            $user->setLocked(0);
        }
        if ($request->get('photo') != '') {
            $user->setPhoto($request->get('photo'));
        }
        $em->persist($user);
        $em->flush();
        return new Response('ثبت کاربر;اطلاعات با موفقیت ثبت شد;success;true');
    }

    /**
     * @Route("/DeleteUser/{id}")
     */
    public function DeleteUser($id) {
        if ($id == 2) {
            return new Response('حذف کاربر;این کاربر قابلیت حذف شدن ندارد;error;true');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:User")->find($id);
        if (!$entity) {
            return new Response('حذف کاربر;مشکل در اطلاعات ارسال شده;danger;true');
        }
        $em->remove($entity);
        $em->flush();
        return new Response('حذف کاربر;اطلاعات با موفقیت حذف شد;success;true');
    }

    /**
     * @Route("/SendMsg")
     * @Method("post")
     */
    public function SendMsg(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->get('One') == false) {
            $id = $request->get('id');
            $Users = $em->getRepository("AppBundle:User")->findBy(array('id' => $id));
            if (!$Users) {
                return new Response(' کاربران;مشکل در اطلاعات ارسال شده;error;true');
            }
            $i = 0;
            $number = array();
            foreach ($Users as $user) {
                if ($user->getMobile() == '') {
                    $i++;
                } else {
                    $number[] = $user->getMobile();
                }
            }

            if ($i == count($request->get('id'))) {
                return new Response(' کاربران;کاربران انتخاب شده شماره موبایل خود را وارد نکرده است;error;true');
            } else if ($i != 0) {

                $result = HelperFunction::SMS($this->UrlMsg, $this->UserNameMsg, $this->PasswordMsg, $this->SenderNumber, $number, $request->get('msg'));
                if ($result == true) {
                    return new Response(' کاربران;تعدادی از کاربران انتخاب شده شماره موبایل خود را وارد نکرده اند ;danger;true');
                } else {
                    return new Response(' کاربران;مشکل در ارسال پیام;error;true');
                }
            } else {
                $result = HelperFunction::SMS($this->UrlMsg, $this->UserNameMsg, $this->PasswordMsg, $this->SenderNumber, $number, $request->get('msg'));
                if ($result == true) {
                    return new Response(' کاربران;اطلاعات با موفقیت ارسال شد;success;true');
                } else {
                    return new Response(' کاربران;مشکل در ارسال پیام;error;true');
                }
            }
        } else {
            return new Response(' کاربران;مشکل در اطلاعات ارسال شده;error;true');
        }
    }

    ///////////////////////
    // End User Code
    ///////////////////////
    //
    //
    //////////////////////
    // Animale Code
    /////////////////////
    /**
     * @Route("/UserShowAnimale/{id}")
     */
    public function ShowAnimaleUser($id) {
        if ($id == "undefined") {
            return new Response('Not True Id');
        }
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $q = $em->getRepository('AppBundle:Animals')->createQueryBuilder('p');
        $result = $q->where('p.user = :user')
                ->setParameter("user", $id)
                ->getQuery()
                ->getResult();
        if (empty($result)) {
            $data['animale']['msg'] = ' کاربران;حیوانی برای این کاربر ثبت نشده است;danger;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $data = array();
        $i = 0;
        foreach ($result as $val) {
            $photo = $connection->query(sprintf("select photo from animalsphoto where animals_id = '%s' and photoDefault = '1' limit 1", $val->getId()));
            $photo->execute();
            $photo = $photo->fetchAll();
            $data['animale'][$i]['name'] = $val->getName();
            $data['animale'][$i]['id'] = $val->getId();
            $data['animale'][$i]['active'] = $val->getActive();
            $data['animale'][$i]['age'] = $val->getAge();
            $data['animale'][$i]['sex'] = $val->getSex();
            $data['animale'][$i]['weight'] = $val->getWeight();
            $data['animale'][$i]['stature'] = $val->getStature();
            $data['animale'][$i]['microChip'] = $val->getMicroChip();
            $data['animale'][$i]['color'] = $val->getColor();
            $data['animale'][$i]['codeParvande'] = $val->getCodeParvande();
            $data['animale'][$i]['goneh'] = $val->getGoneh();
            $data['animale'][$i]['nezhad'] = $val->getNezhad();
            if (!empty($photo)) {
                $data['animale'][$i]['photo'] = $photo[0]['photo'];
            }
            $data['animale'][$i]['animalsTypeId'] = "";
            try {
                if ($val->getAnimalsCategory())
                    $data['animale'][$i]['animalsType'] = $val->getAnimalsCategory()->getAnimalstype();

                if ($val->getAnimalscategory())
                    $data['animale'][$i]['animalsTypeId'] = $val->getAnimalscategory()->getId();
            } catch (\Exception $e) {
                file_put_contents(__DIR__ . "/../logsBusinessCode/ErrorUserManager" . date("Y-m-d"), $e->getMessage() . "\n in line : " . $e->getCode());
            }
            $data['animale'][$i]['dateCreateParvande'] = '';
            if ($val->getDateCreateParvande() != '') {
                $DateTime = $val->getDateCreateParvande();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['animale'][$i]['dateCreateParvande'] = $DateTime;
            }
            if ($val->getCreateAt() != '') {
                $DateTime = $val->getCreateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['animale'][$i]['cteateAt'] = $DateTime;
            } else {
                $data['animale'][$i]['cteateAt'] = '';
            }
            if ($val->getUpdateAt() != '') {
                $DateTime = $val->getUpdateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['animale'][$i]['updateAt'] = $DateTime;
            } else {
                $data['animale'][$i]['updateAt'] = '';
            }
            $i++;
        }
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * 
     * @param type $id
     * @Route("/History/{id}/id/{filter}/filter/{offset}/offsets/{limit}/limits")
     */
    public function getAnimalHistory($id, $filter, $limit, $offset) {
        $em = $this->getDoctrine()->getManager();
        $q = $em->getRepository("AppBundle:Historyofanimalexamination")->createQueryBuilder("p");
        $result = $q->where("p.animals = :animals")
                ->setParameter("animals", $id)
                ->orderBy("p.createAt", "desc")
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->getQuery()
                ->getResult();
        if (empty($result)) {
            $data['history']['msg'] = ' سوابق;اطلاعاتی یافت نشد;danger;true';
            $response = \json_encode($data);
            return new Response($response);
        }
        $i = 0;
        $data = array();
        foreach ($result as $value) {
            $data['history'][$i] = $value->Serialization();
            $i++;
        }
        $connection = $em->getConnection();
        $statement = $connection->prepare(sprintf("select count(*) as c from historyofanimalexamination where animals_id = '%s'", $id));
        $statement->execute();
        $count = $statement->fetchAll();
        $data['count'] = $count[0]['c'];
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/AddHistory")
     * @Method("post")
     */
    public function AddHistory(Request $request) {
        if (empty($request->get("file")) || empty($request->get("dateHistory"))) {
            $data['msg'] = "افزودن سوابق;فیلد های اسکن فایل و تاریخ ایجاد پرونده نمی تواند خالی باشد;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository("AppBundle:Animals")->find($request->get("animale_id"));
        if (!$animal) {
            $data['msg'] = "افزودن سوابق;مشکل در اطلاعات ارسال شده;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $fileEntity = new Historyofanimalexamination();
        $fileEntity->setCreateAt(new \DateTime());
        $fileEntity->setDescrib($request->get('Describ'));
        $fileEntity->setFile($request->get('file'));
        $DateTime = $request->get('dateHistory');
        $DateTime = explode('/', $DateTime);
        $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
        $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
        $fileEntity->setDatehistory($DateTime);
        $fileEntity->setAnimals($animal);
        $em->persist($fileEntity);
        $em->flush();
        $data['msg'] = "افزودن سوابق;اطلاعات با موفقیت ثبت شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/EditHistory")
     * @Method("post")
     */
    public function EditHistory(Request $request) {
        if (empty($request->get("dateHistory"))) {
            $data['msg'] = "ویرایش سوابق;فیلد های اسکن فایل و تاریخ ایجاد پرونده نمی تواند خالی باشد;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $em = $this->getDoctrine()->getManager();
        if ($request->get("id") == "") {
            $data['msg'] = ' ویرایش سوابق;مشکل در اطلاعات ارسال شده;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $HistoryAnimal = $em->getRepository("AppBundle:Historyofanimalexamination")->find($request->get("id"));
        if (!$HistoryAnimal) {
            $data['msg'] = ' ویرایش سوابق;مشکل در اطلاعات ارسال شده;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $HistoryAnimal->setDescrib($request->get('Describ'));
        if (!empty($request->get("file"))) {
            $HistoryAnimal->setFile($request->get('file'));
        }
        $DateTime = $request->get('dateHistory');
        $DateTime = explode('/', $DateTime);

        if (strlen($DateTime[2]) == 4) {
            $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
        } else {
            $DateTime = Jalali::jalali_to_gregorian($DateTime[0], $DateTime[1], $DateTime[2]);
        }

        $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
        $HistoryAnimal->setDatehistory($DateTime);
        $HistoryAnimal->setUpdateAt(new \DateTime());
        $em->flush();
        $data['msg'] = "ویرایش سوابق;اطلاعات با موفقیت ویرایش شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * 
     * @param type $id
     * @Route("/DeleteHistory/{id}")
     */
    public function DeleteHistory($id) {
        $em = $this->getDoctrine()->getManager();
        $history = $em->getRepository("AppBundle:Historyofanimalexamination")->find($id);
        if (!$history) {
            $data['msg'] = 'حذف سوابق;مشکل در اطلاعات ارسال شده;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $em->remove($history);
        $em->flush();
        $data['msg'] = 'حذف سوابق;اطلاعات با موفقیت حذف شد;success;true';
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * @Route("/EditAnimale")
     * @param Request $request
     * @Method("post")
     */
    public function EditAnimale(Request $request) {
        if ($request->get('name') == '' || $request->get('nezhad') == '' || $request->get('codeParvande') == '' ||
                $request->get('animalsTypeId') == '') {
            $data['msg'] = ' ویرایش حیوانات;فیلد نام و نژاد و گونه کد پرورنده و دسته بندی نمی تواند خالی باشد;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $infoAnimals = $em->getRepository('AppBundle:Animals')->find($request->get('id'));
        if (!$infoAnimals) {
            $data['animale']['msg'] = ' ویرایش حیوانات;مشکل در اطلاعات ارسال شده;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $infoAnimals->setName($request->get('name'));
        $infoAnimals->setAge($request->get('age'));
        $infoAnimals->setSex($request->get('sex'));
        $infoAnimals->setWeight($request->get('weight'));
        $infoAnimals->setStature($request->get('stature'));
        $infoAnimals->setMicroChip($request->get('microChip'));

        if ($request->get('active') == true) {
            $infoAnimals->setActive(1);
        } else {
            $infoAnimals->setActive(0);
        }

        $infoAnimals->setColor($request->get('color'));
        $infoAnimals->setGoneh($request->get('goneh'));
        $code = $infoAnimals->getCodeParvande();
        if ($code == '' && $request->get('codeParvande') != '') {
            $infoAnimals->setCodeParvande($request->get('codeParvande'));
            $infoAnimals->setDateCreateParvande(new \DateTime());
        }

        $infoAnimals->setCodeParvande($request->get('codeParvande'));
        $infoAnimals->setNezhad($request->get('nezhad'));
        $infoAnimals->setUpdateAt(new \DateTime());
        //set category animals
        $catAnimal = $em->getRepository('AppBundle:Animalscategory')->find($request->get('animalsTypeId'));
        $infoAnimals->setAnimalscategory($catAnimal);

        $PhotoAnimale = $em->getRepository('AppBundle:Animalsphoto')->findBy(array('animals' => $infoAnimals));

        if ($PhotoAnimale && $request->get('photo') != '') {
            $PhotoAnimale[0]->setPhoto($request->get('photo'));
        } elseif ($request->get('photo') != '') {
            $AnimalsPhoto = new Animalsphoto;
            $AnimalsPhoto->setAnimals($infoAnimals);
            $AnimalsPhoto->setPhoto($request->get('photo'));
            $AnimalsPhoto->setPhotoDefault(true);
            $em->persist($AnimalsPhoto);
        }

        $em->flush();
        $data['msg'] = 'ویرایش اطلاعات حیوان;اطلاعات با موفقیت ویرایش شد;success;true';
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * @Route("/CategoryAnimaleAll")
     */
    public function CategoryAnimaleAll() {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Animalscategory")->findAll();
        $data = array();
        if ($entity) {
            $i = 0;
            foreach ($entity as $value) {
                $data[$i]['id'] = $value->getId();
                $data[$i]['describtion'] = $value->getDescribtion();
                $data[$i]['animalsType'] = $value->getAnimalsType();
                $i++;
            }

            $result = \json_encode($data);
            return new Response($result);
        }
        return new Response('Not Info');
    }

    /**
     * @Route("/AddAnimale")
     * @Method("post")
     */
    public function AddAnimale(Request $request) {
        if ($request->get('name') == '' || $request->get('nezhad') == '' || $request->get('codeParvande') == '' ||
                $request->get('animalsTypeId') == '') {
            $data['animale']['msg'] = ' ویرایش حیوانات;فیلد نام و نژاد و گونه کد پرورنده و دسته بندی نمی تواند خالی باشد;error;true';
            $result = \json_encode($data);
            return new Response($result);
        }
        $em = $this->getDoctrine()->getEntityManager();
        $animalEntity = new Animals();
        $animalEntity->setName($request->request->get('name'));
        $animalEntity->setAge($request->request->get('age'));
        $animalEntity->setSex($request->request->get('sex'));
        $animalEntity->setWeight($request->request->get('weight'));
        $animalEntity->setStature($request->request->get('stature'));
        $animalEntity->setMicroChip($request->request->get('microChip'));
        if ($request->get('active') == true) {
            $animalEntity->setActive(1);
        } else {
            $animalEntity->setActive(0);
        }
        $animalEntity->setColor($request->get('color'));
        $animalEntity->setGoneh($request->get('goneh'));
        $animalEntity->setNezhad($request->get('nezhad'));
        $animalEntity->setCodeParvande($request->get('codeParvande'));
        $animalEntity->setDateCreateParvande(new \DateTime());

        if ($request->request->get('animalsTypeId') == '') {
            return new Response('افزودن حیوانات;دسته بندی حیوانات نمی تواند خالی باشد;error;true');
        }
        $catAnimalEntity = $this->getDoctrine()->getRepository('AppBundle:Animalscategory')->find($request->request->get('animalsTypeId'));
        $animalEntity->setAnimalscategory($catAnimalEntity);
        $user = $em->getRepository("AppBundle:User")->find($request->get('user_id'));
        if (!$user) {
            return new Response('افزودن حیوانات;مشکل در اطلاعات ارسال شده;error;true');
        }
        $animalEntity->setUser($user);
        $em->persist($animalEntity);
        $em->flush();

        if ($request->get('photo') != '') {
            $photoEntity = new Animalsphoto();
            $photoEntity->setAnimals($animalEntity);
            $photoEntity->setPhotoDefault(true);
            $photo = $request->request->get('photo');
            $photoEntity->setPhoto($photo);
            $em->persist($photoEntity);
            $em->flush();
        }

        $data['animale']['msg'] = 'افزودن حیوانات;اطلاعات با موفقیت ثبت شد;success;true';
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * 
     * @Route("/DeleteAnimale/{id}")
     */
    public function DeleteAnimale($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("AppBundle:Animals")->find($id);
        if (!$entity) {
            return new Response('حذف حیوانات;مشکل در اطلاعات ارسال شده;danger;true');
        }
        $em->remove($entity);
        $em->flush();
        return new Response('حذف حیوانات;اطلاعات با موفقیت حذف شد;success;true');
    }

    ////////////////////
    // End Animale Code
    ////////////////////
    //
    //
    //
    /////////////////////
    // Activity Code
    /////////////////////

    /**
     * 
     * @Route("/Activity/{id}")
     */
    public function Activity($id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Activity');
        $q = $repository->createQueryBuilder('p')
                ->where("p.animale = :id")
                ->setParameter('id', $id)
                ->setMaxResults(15)
                ->orderBy('p.id', 'desc')
                ->getQuery()
                ->getResult();

        if (empty($q)) {
            $data['activity']['msg'] = "نمایش فعالیت ها;اطلاعاتی یافت نشد; danger; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $data = array();
        $i = 0;
        foreach ($q as $value) {
            $data['activity'][$i]['activityName'] = $value->getActivityName()->getName();
            $data['activity'][$i]['activityName_id'] = $value->getActivityName()->getId();
            $data['activity'][$i]['desc'] = $value->getDesc();
            $data['activity'][$i]['sendsms'] = $value->getSendSms();
            $data['activity'][$i]['send_with_message'] = $value->getSend_with_message();
            if ($value->getDate() != '') {
                $DateTime = $value->getDate();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['activity'][$i]['date'] = $DateTime;
            } else {
                $data['activity'][$i]['date'] = '';
            }

            $data['activity'][$i]['id'] = $value->getId();
            if ($value->getCreateAt() != '') {
                $DateTime = $value->getCreateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['activity'][$i]['createAt'] = $DateTime;
            } else {
                $data['activity'][$i]['createAt'] = '';
            }
            if ($value->getUpdateAt() != '') {
                $DateTime = $value->getUpdateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['activity'][$i]['updateAt'] = $DateTime;
            } else {
                $data['activity'][$i]['updateAt'] = '';
            }
            $i++;
        }
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * 
     * @Route("/AllActivityType")
     */
    public function AllActivityType() {
        $result = $this->getDoctrine()->getManager()->getRepository("AppBundle:ActivityName")->findAll();
        if (empty($result)) {
            $data['activity_name']['msg'] = "نمایش فعالیت ها;اطلاعاتی یافت نشد; danger; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $data['activity_name'][$i]['id'] = $value->getId();
            $data['activity_name'][$i]['name'] = $value->getName();
            $i++;
        }
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * @Route("/AddActivity")
     * @Method("post")
     */
    public function AddActivity(Request $request) {
        if ($request->get('activityName_id') == '' || $request->get('date') == '') {
            $data['activity']['msg'] = "افزودن فعالیت ها;فیلد های نام فعالیت و تاریخ آن نمی تواند خالی باشد; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $em = $this->getDoctrine()->getManager();
        $activity = new Activity();
        $DateTime = $request->get('date');
        $DateTime = explode('/', $DateTime);
        $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
        $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
        $activity->setDate($DateTime);
        $animale = $em->getRepository("AppBundle:Animals")->find($request->get('animale_id'));
        if (!$animale) {
            $data['activity']['msg'] = "افزودن فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $activity->setAnimals($animale);
        $activityName = $em->getRepository("AppBundle:ActivityName")->find($request->get('activityName_id'));
        if (!$activityName) {
            $data['activity']['msg'] = "افزودن فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $activity->setActivityName($activityName);
        $activity->setCreateAt(new \DateTime());
        $activity->setDesc($request->get('desc'));
        $activity->setActive_crone(1);
        $activity->setSend_with_message(($request->get("send_with_message") == false ? 0 : 1));
        $em->persist($activity);
        $em->flush();
        $data['activity']['msg'] = ' فعالیت ها;اطلاعات با موفقیت ثبت شد;success;true';
        $data['activity']['add']['activityName'] = $activity->getActivityName()->getName();
        $data['activity']['add']['activityName_id'] = $activity->getActivityName()->getId();
        $data['activity']['add']['desc'] = $activity->getDesc();
        $data['activity']['add']['date'] = $request->get('date');
        $data['activity']['add']['id'] = $activity->getId();

        if ($activity->getCreateAt() != '') {
            $DateTime = $activity->getCreateAt();
            $DateTime = $DateTime->format('Y-m-d');
            $DateTime = explode('-', $DateTime);
            $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
            $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
            $data['activity']['add']['createAt'] = $DateTime;
        } else {
            $data['activity']['add']['createAt'] = '';
        }
        $result = \json_encode($data);
        return new Response($result);
    }

    /**
     * @Route("/EditActivity")
     * @Method("post")
     */
    public function EditActivity(Request $request) {
        if ($request->get('activityName_id') == '' || $request->get('date') == '') {
            $data['activity']['msg'] = "ویرایش فعالیت ها;فیلد های نام فعالیت و تاریخ آن نمی تواند خالی باشد; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $em = $this->getDoctrine()->getManager();
        $activity = $em->getRepository("AppBundle:Activity")->find($request->get('id'));
        if (!$activity) {
            $data['activity']['msg'] = "ویرایش فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $DateTime = $request->get('date');
        $DateTime = explode('/', $DateTime);
        $DateTime = Jalali::jalali_to_gregorian($DateTime[2], $DateTime[1], $DateTime[0]);
        $DateTime = new \DateTime($DateTime[0] . '-' . $DateTime[1] . '-' . $DateTime[2]);
        $activity->setDate($DateTime);
        $animale = $em->getRepository("AppBundle:Animals")->find($request->get('animale_id'));
        if (!$animale) {
            $data['activity']['msg'] = "ویرایش فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $activity->setAnimals($animale);
        $activityName = $em->getRepository("AppBundle:ActivityName")->find($request->get('activityName_id'));
        if (!$activityName) {
            $data['activity']['msg'] = "ویرایش فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $activity->setActivityName($activityName);
        $activity->setDesc($request->get('desc'));
        $activity->setUpdateAt(new \DateTime());
        $activity->setSend_with_message($request->get("send_with_message"));
        $em->flush();
        return new Response('فعالیت ها;اطلاعات با موفقیت ثبت شد;success;true');
    }

    /**
     * @Route("/DeleteActivity/{id}")
     */
    public function DeleteActivity($id) {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Activity')->find($id);
        if (!$result) {
            $data['activity']['msg'] = "ویرایش فعالیت ها;مشکل در اطلاعات ارسال شده; error; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $em->remove($result);
        $em->flush();
        $data['activity']['msg'] = 'فعالیت ها;اطلاعات با موفقیت حذف شد;success;true';
        $result = \json_encode($data);
        return new Response($result);
    }

    ////////////////////
    //End Activity Code
    ////////////////////
    ////////////////////
    // User Document
    ////////////////////

    /**
     * 
     * @param type $id
     * @Route("/UserShowInfoUpload/{id}")
     */
    public function ShowInfoUserUpload($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->find($id);
        $result = $em->getRepository("AppBundle:UserDocument")->findBy(array("user" => $user));
        if (empty($result)) {
            $data['msg'] = "نمایش  اطلاعات کاربر;اطلاعاتی یافت نشد; danger; true";
            $result = \json_encode($data);
            return new Response($result);
        }
        $i = 0;
        $data = array();
        foreach ($result as $value) {
            $data['ShowInfoUserUpload'][$i] = $value->Serialize();
            $i++;
        }
        $response = \json_encode($data);
        return new Response($response);
    }

}
