<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Service\Jalali;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Service\UploadImage;

/**
 * Description of SliderController
 *
 * @Route("/admin/Slider")
 */
class SliderController extends Controller {

    /**
     * @Route("/",name="Slider")
     * @Template("AppBundle::slider.html.twig")
     */
    public function index() {
        return array();
    }

    /**
     * @Route("/AddPic")
     */
    public function AddPic(Request $request) {
        $slider = new Slider();
        $file = $request->get('photo');
        if (empty($file)) {
            $data['slider']['msg'] = "افزودن تصاویر اسلایدر;لطفا تصویر را آپلود نمایید;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $upload = $this->base64_to_jpeg($file, 'null.jpg');
        if (!$upload) {
            $data['slider']['msg'] = "افزودن تصاویر اسلایدر;مشکل در آپلود تصویر لطفا دوباره اطلاعات را ارسال نمائید;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $slider->setFile($upload);
        $slider->setTitleH1($request->get('title_h1'));
        $slider->setTitleH3($request->get('title_h3'));
        $slider->setActive($request->get('active'));
        $slider->setCreateAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($slider);
        $em->flush();
        $data['slider']['new']['title_h1'] = $slider->getTitleH1();
        $data['slider']['new']['title_h3'] = $slider->getTitleH3();
        $data['slider']['new']['active'] = $slider->getActive();
        $data['slider']['new']['file'] = $slider->getFile();
        $data['slider']['new']['id'] = $slider->getId();
        $DateTime = $slider->getCreateAt();
        $DateTime = $DateTime->format('Y-m-d');
        $DateTime = explode('-', $DateTime);
        $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
        $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
        $data['slider']['new']['createAt'] = $DateTime;
        $data['slider']['msg'] = "افزودن تصاویر اسلایدر;اطلاعات با موفقیت ثبت شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

    public function base64_to_jpeg($data, $OldFile) {
        try {
            if (file_exists(__DIR__ . '/../../../public_html/bundles/public/img/slider/' . $OldFile)) {
                unlink(__DIR__ . '/../../../public_html/bundles/public/img/slider/' . $OldFile);
            }
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $name_file = new \DateTime();
            $name_file = $name_file->format('YmdHis');
            $file = __DIR__ . '/../../../public_html/bundles/public/img/slider/' . 'slider' . $name_file . '.jpg';
            $success = file_put_contents($file, $data);
            if ($success == true) {
                return 'slider' . $name_file . '.jpg';
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @Route("/ShowAll")
     */
    public function ShowAll() {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("AppBundle:Slider")->findAll();
        if (empty($result)) {
            $data['slider']['msg'] = "نمایش تصاویر اسلایدر;اطلاعاتی یافت نشد;danger;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $data['slider'][$i]['file'] = $value->getFile();
            $data['slider'][$i]['title_h1'] = $value->getTitleH1();
            $data['slider'][$i]['title_h3'] = $value->getTitleH3();
            $data['slider'][$i]['id'] = $value->getId();
            $data['slider'][$i]['active'] = $value->getActive();
            if ($value->getCreateAt() != '') {
                $DateTime = $value->getCreateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['slider'][$i]['createAt'] = $DateTime;
            } else {
                $data['slider'][$i]['createAt'] = '';
            }
            if ($value->getUpdateAt() != '') {
                $DateTime = $value->getUpdateAt();
                $DateTime = $DateTime->format('Y-m-d');
                $DateTime = explode('-', $DateTime);
                $DateTime = Jalali::gregorian_to_jalali($DateTime[0], $DateTime[1], $DateTime[2]);
                $DateTime = $DateTime[0] . '/' . $DateTime[1] . '/' . $DateTime[2];
                $data['slider'][$i]['updateAt'] = $DateTime;
            } else {
                $data['slider'][$i]['updateAt'] = '';
            }
            $i++;
        }
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/EditSlider")
     */
    public function EditSlider(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository("AppBundle:Slider")->find($request->get("id"));
        if (!$slider) {
            $data['slider']['msg'] = "ویرایش تصاویر اسلایدر;مشکل در اطلاعات ارسال شده;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $slider->setTitleH1($request->get("title_h1"));
        $slider->setTitleH3($request->get('title_h3'));
        if ($request->get('active') == 1) {
            $slider->setActive(1);
        } elseif ($request->get('active') == '') {
            $slider->setActive(1);
        } else {
            $slider->setActive(0);
        }

        $file = $request->get('photo');
        $check = explode(';', $file);
        if (isset($check[1])) {
            $OldFile = $slider->getFile();
            $file = $this->base64_to_jpeg($file, $OldFile);
            if (!$file) {
                $data['slider']['msg'] = "ویرایش تصاویر اسلایدر;مشکل در آپلود تصویر ارسال شده;error;true";
                $response = \json_encode($data);
                return new Response($response);
            }
            $slider->setFile($file);
            $data['slider']['img'] = $file;
        }
        $em->flush();
        $data['slider']['msg'] = "ویرایش تصاویر اسلایدر;اطلاعات با موفقیت وبرایش شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/DeleteSlider/{id}")
     */
    public function DeleteSlider($id) {
        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository("AppBundle:Slider")->find($id);
        if (!$slider) {
            $data['slider']['msg'] = "حذف تصاویر اسلایدر;مشکل در اطلاعات ارسال شده;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $em->remove($slider);
        $em->flush();
        $data['slider']['msg'] = "حذف تصاویر اسلایدر;اطلاعات با موفقیت حذف شد;success;true";
        $response = \json_encode($data);
        return new Response($response);
    }

    /**
     * @Route("/ReadYaml")
     */
    public function ReadYaml() {
        $yaml = new Parser();
        $result = $yaml->parse(\file_get_contents(__DIR__ . "../../yaml/KeyValue.yml"));
        if (empty($result)) {
            $result = $yaml->parse(\file_get_contents(__DIR__ . "../../yaml/DefaultKeyValue.yml"));
            $dumper = new Dumper();
            $yaml = $dumper->dump($result);
            file_put_contents(__DIR__ . "../../yaml/KeyValue.yml", $yaml);
        }
        $response = \json_encode($result);
        return new Response($response);
    }

    /**
     * @Route("/WriteYaml")
     * @Method("post")
     */
    public function WriteYaml(Request $request) {
        $photo = $request->get('photo');
        if ($photo) {
            $key = $request->get('key');
            $value = $request->get('value');
            if (empty($key) || empty($value)) {
                $data['keyvalue']['msg'] = "جدول مقدارها;مشکل در اطلاعات ارسال شده;error;true";
                $response = \json_encode($data);
                return new Response($response);
            }
            $yaml = new Parser();
            $result = $yaml->parse(\file_get_contents(__DIR__ . "../../yaml/KeyValue.yml"));
            $name = $this->base64_to_jpeg_keyvalue($photo, $value[0]);
            $result[$key][0] = 'bundles/public/img/upload/' . $name;
            $dumper = new Dumper();
            $yaml = $dumper->dump($result, 3);
            file_put_contents(__DIR__ . "../../yaml/KeyValue.yml", $yaml);
            $response = \json_encode($result);
            return new Response($response);
        }
        $array = $request->get('data');
        if (empty($array)) {
            $data['keyvalue']['msg'] = "جدول مقدارها;مشکل در اطلاعات ارسال شده;error;true";
            $response = \json_encode($data);
            return new Response($response);
        }
        $data = array();
        foreach ($array as $key => $value) {
//            if ($key == 'Sections') {
//                if ($value['ForumBundle'] == 1) {
//                    $this->EnabledForumBundle();
//                } else {
//                    $this->DisabledForumBundle();
//                }
//                if ($value['ShopBundle'] == 1) {
//                    $this->EnabledShopBundle();
//                } else {
//                    $this->DisabledShopBundle();
//                }
//            }
            $data[$key] = $value;
        }
        $dumper = new Dumper();
        $yaml = $dumper->dump($data, 3);
        file_put_contents(__DIR__ . "../../yaml/KeyValue.yml", $yaml);
        $session = new Session();
        $session->set('KeyValue', $data);
        $data2['msg'] = "جدول مقدارها;اطلاعات با موفقیت ثبت شد;success;true";
        $response = \json_encode($data2);
        return new Response($response);
    }

    public function base64_to_jpeg_keyvalue($data, $old_file) {
        try {
            if (file_exists($old_file)) {
                unlink($old_file);
            }
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $name_file = new \DateTime();
            $name_file = $name_file->format('YmdHis');
            $file = __DIR__ . '/../../../public_html/bundles/public/img/upload/' . 'Image' . $name_file . '.jpg';
            $success = file_put_contents($file, $data);
            if ($success == true) {
                return 'Image' . $name_file . '.jpg';
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @Route("/DisabledForumBundle")
     */
    public function DisabledForumBundle() {
        $result = \file_get_contents(__DIR__ . "../../../../app/config/routing.yml");
        $find = strpos($result, 'forum_bundle:
    resource: "@ForumBundle/Resources/config/route/route.yml"');
//        var_dump($find);
        if ($find != false) {
            $RemoveShopBundle = str_replace('forum_bundle:
    resource: "@ForumBundle/Resources/config/route/route.yml"', '', $result);
            $app2 = \file_put_contents(__DIR__ . "../../../../app/config/routing.yml", $RemoveShopBundle);
        } else {
            return new Response('Latest Disabled');
        }
        $app = \file_get_contents(__DIR__ . "../../../../app/AppKernel.php");
        $find = strpos($app, "new ForumBundle\ForumBundle(),");
        if ($find != false) {
            $RemoveForumBundle = str_replace("new ForumBundle\ForumBundle(),", "", $app);
            $result = \file_put_contents(__DIR__ . "../../../../app/AppKernel.php", $RemoveForumBundle);
            return new Response('Ok ' . $result);
        } else {
            return new Response("Later Disabled");
        }
    }

    /**
     * @Route("/EnabledForumBundle")
     */
    public function EnabledForumBundle() {
        $result = \file_get_contents(__DIR__ . "../../../../app/config/routing.yml");
        $find = strpos($result, 'forum_bundle:
    resource: "@ForumBundle/Resources/config/route/route.yml"');
//        var_dump($find);
        if ($find == false) {
            $AddShopBundle = $result . '    
forum_bundle:
    resource: "@ForumBundle/Resources/config/route/route.yml"    
            ';

            $enable_route = \file_put_contents(__DIR__ . "../../../../app/config/routing.yml", $AddShopBundle);
        } else {
            return new Response('Latest Enabled');
        }

        $app = \file_get_contents(__DIR__ . "../../../../app/AppKernel.php");
        $find = strpos($app, "new ForumBundle\ForumBundle(),");
        if ($find == false) {
            $AddForumBundle = str_replace("new BlogBundle\BlogBundle(),", "new BlogBundle\BlogBundle(),"
                    . "new ForumBundle\ForumBundle(), ", $app);
        } else {
            return new Response('Later Enabled');
        }
        $result = \file_put_contents(__DIR__ . "../../../../app/AppKernel.php", $AddForumBundle);
        $AddForumBundle = $AddForumBundle . ' ';
        $result = \file_put_contents(__DIR__ . "../../../../app/AppKernel.php", $AddForumBundle);
        return new Response('Ok ' . $result);
    }

    /**
     * @Route("/DisabledShopBundle")
     */
    public function DisabledShopBundle() {
        $result = \file_get_contents(__DIR__ . "../../../../app/config/routing.yml");
        $find = strpos($result, 'shop:
    resource: "@ShopBundle/Controller/"
    type: annotation
    prefix:   /');
//        var_dump($find);
        if ($find != false) {
            $RemoveShopBundle = str_replace('shop:
    resource: "@ShopBundle/Controller/"
    type: annotation
    prefix:   /', '', $result);
            $app2 = \file_put_contents(__DIR__ . "../../../../app/config/routing.yml", $RemoveShopBundle);
        } else {
            return new Response('Latest Disabled');
        }

        $app = \file_get_contents(__DIR__ . "../../../../app/AppKernel.php");
        $find = strpos($app, "new ShopBundle\ShopBundle(),");
        if ($find != false) {
            $RemoveForumBundle = str_replace("new ShopBundle\ShopBundle(),", "", $app);
            $result = \file_put_contents(__DIR__ . "../../../../app/AppKernel.php", $RemoveForumBundle);
            return new Response('Ok Disabled Shop Bundle ' . $result);
        } else {
            return new Response("Later Disabled");
        }
    }

    /**
     * @Route("/EnabledShopBundle")
     */
    public function EnabledShopBundle() {
        $result = \file_get_contents(__DIR__ . "../../../../app/config/routing.yml");
        $find = strpos($result, 'shop:
    resource: "@ShopBundle/Controller/"
    type: annotation
    prefix:   /');

        if ($find == false) {
            $AddShopBundle = $result . '
shop:
    resource: "@ShopBundle/Controller/"
    type: annotation
    prefix:   /   
            ';

            $enable_route = \file_put_contents(__DIR__ . "../../../../app/config/routing.yml", $AddShopBundle);
        } else {
            return new Response('Latest Enabled');
        }

        $app = \file_get_contents(__DIR__ . "../../../../app/AppKernel.php");
        $findKernel = strpos($app, "new ShopBundle\ShopBundle(),");
        if ($findKernel == false) {
            $AddForumBundle = str_replace("new FOS\UserBundle\FOSUserBundle(),", "new ShopBundle\ShopBundle(),"
                    . "new FOS\UserBundle\FOSUserBundle(),", $app);
            $result = \file_put_contents(__DIR__ . "../../../../app/AppKernel.php", $AddForumBundle);
            return new Response('Ok Enable Shop Bundle ' . $result);
        } else {
            return new Response('Later Enabled');
        }
    }

}
