<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;
use AppBundle\Entity\Address;
use AppBundle\Entity\Image;
use AppBundle\Entity\Country;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

    /**
     * @Route("/users", name="test")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $response = array();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User');
        $allUser = $user->findAll();
        $serializer = $this->get('jms_serializer');

        $response['body'] = $allUser;
        $response['status'] = true;
        $response['error'] = [];

        $res = new Response($serializer->serialize($response,'json'));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    /**
     * @Route("/users", name="save_user")
     * @Method({"POST"})
     */
    public function addUserAction(Request $request)
    {

        
        //get file from request
        $file = $request->files->get('file');
        $serializer = $this->get('jms_serializer');
        
        //validate file
        $image = new Image();
        $image->setFile($file);
        $validator = $this->get('validator');
        $errors = $validator->validate($image);
        //validate upload image
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorMessage = [];
            foreach ($errors as $error) {
                $errorMessage[$error->getPropertyPath()] =  $error->getMessage();
            }

            $response['body'] = [];
            $response['status'] = false;
            $response['error'] = $errorMessage;

            return new Response($serializer->serialize($response,'json'));
            die();
        }





        $fileName = $this->get('app.file_uploader')->upload($file);

        $formData = $request->request->all();

        //save form data
        //$request->query->get('id'); retrive post get data example
        $user = new User();
        $address = new Address();
        $user_permission = new Group();
        $image = new Image();

        //response to API
        $response = array();
        //$serializer = $this->get('jms_serializer');

        //NEED VALIDATE FORM DATA IN FUTURE

        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $check_duplicat_name = $repository->findOneByUsername($formData['name']);


        //check duplicat
        if($check_duplicat_name){

            $response['error'] = 'Username already exist!';
            $array = $serializer->toArray($response);
            return new JsonResponse($array);


        }

        $check_duplicat_email = $repository->findOneByEmail($formData['email']);
        //check duplicat
        if($check_duplicat_email){

            $response['error'] = 'Email already exist!';
            $array = $serializer->toArray($response);
            return new JsonResponse($array);
        }

        $em = $this->getDoctrine()->getManager();
        $user_role = $em->getRepository('AppBundle:Role')
            ->loadRoleByRolename('ROLE_USER'); //my custom repository

        $user_country = $em->getRepository('AppBundle:Country')
            ->loadCountryByName($formData['country']);
        //*/
        //save form data to database
        // $image->setPath($form_data['file_path']);
        $image->setPath($request->getScheme() . '://' . $request->getHttpHost().'/upload/'.$fileName);
        $address->setAddress($formData['address']);
        $pwd= 111111; //$user->getPassword()
        $encoder=$this->container->get('security.password_encoder');
        $pwd=$encoder->encodePassword($user, $pwd);
        $user->setPassword($pwd);
        $user->setUsername($formData['name']);
        $user->setEmail($formData['email']);
        $user->setOnline();

        //set user relation
        $user->setAddress($address);
        $user->setCountry($user_country);
        $user->setImage($image);
        //add user permission
        $user_permission->setName($formData['name']);
        $user_permission->setUserRole($user_role);

        $user->addGroup($user_permission);
        $em = $this->getDoctrine()->getManager();

        $em->persist($address);
        $em->persist($user);
        $em->persist($user_permission);
        $em->persist($image);
        $em->flush();

        //return last save object
        $last_object = $repository->find($user->getId());

        // $serializer = $serializer::create()->build();
        $array = $serializer->toArray($last_object);
        $response['body'] = $array;
        $response['status'] = true;
        $response['error'] = [];


        return new Response($serializer->serialize($response,'json'));
        die();
    }

    /**
     * @Route("/users/{id}", name="delete_user")
     * @Method({"DELETE"})
     */
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($user);
        $em->flush();
        $res = new Response(json_encode(true));
        return $res;
    }

    //public route to display image
    /**
     * @Route("/test", name="test_image")
     * @Method({"GET"})
     */
    public function test(request $request)
    {
        $file = $this->getParameter('upload_path').'/'.'1a2e824562e22ec6956a9fdb4a0328ee.jpeg';
        $response = new BinaryFileResponse($file);
        // you can modify headers here, before returning
        return $response;
    }

    /**
     * @Route("/login", name="login_user")
     * @Method({"POST"})
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $serializer = $this->get('jms_serializer');
        $formData = $request->request->all();

        $user = new User();
        $encoder=$this->container->get('security.password_encoder');
        $pwd=$encoder->encodePassword($user, $formData['password']);
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneBy(
            ['email' => $formData['email'], 'password' => $pwd]
        );
        if($user) {
            //update login user online time
            $user->setOnline();
            $em->flush();

            $response['body'] = $serializer->toArray($user);
            $response['status'] = true;
            $response['error'] = [];
        } else {
            $response['body'] = '';
            $response['status'] = false;
            $response['error'] = ['User do not exist'];
        }
        $res = new Response($serializer->serialize($response,'json'));
        return $res;
    }

    /**
     * @Route("/online/update", name="userOnline")
     * @Method({"POST"})
     */
    public function updateOnlineStatus(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $serializer = $this->get('jms_serializer');
        $params = array();
        $content =$request->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true); // 2nd param to get as array
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->find($params['currentUser']);
        if($user) {
            //update login user online time
            $user->setOnline();
            $em->flush();
            $response['message'] = ['User online status update successfully'];
            $response['status'] = 200;
            $response['error'] = [];
        } else {
            $response['message'] = [];
            $response['status'] = 404;
            $response['error'] = ['User do not exist'];
        }
        $res = new Response($serializer->serialize($response,'json'), $response['status']);
        return $res;
    }

    /**
     * @Route("/online/user", name="getUserOnline")
     * @Method({"GET"})
     */
    public function getOnlineUser(Request $request)
    {
        $currentId = $request->query->get('currentUser');
        $currentTime = date("Y-m-d H:i:s", (time() - 60)); //current time - 1 minute
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder();
        $user = $em
            ->getRepository('AppBundle:User')
            ->createQueryBuilder('e')
           //\ ->where($qb->expr()->notIn('e.id', [(int)$currentId]))
            ->andWhere('e.online > :currentDate')
            ->setParameter('currentDate', $currentTime)
            ->getQuery()
            ->getResult();
        if($user) {
            //update login user online time
            $response['body'] = $user;
            $response['status'] = 200;
            $response['error'] = [];
        } else {
            $response['body'] = [];
            $response['status'] = 404;
            $response['error'] = ['User do not exist'];
        }
        $res = new Response($serializer->serialize($response,'json'), $response['status']);
        return $res;
    }

    //check authentication
    /**
     * @Route("login/confirm", name="login_confirm")
     * @Method({"POST"})
     */
    public function loginConfirmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $serializer = $this->get('jms_serializer');
        $formData = $request->request->all();


        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $user = $em
            ->getRepository('AppBundle:User')
            ->createQueryBuilder('e')
            ->where('e.isActive = :active')
            ->setParameter('active',1)
            ->andWhere('e.email = :emaill')
            ->setParameter('emaill', $formData['email'])
            ->getQuery()
            ->getResult();


        if($user) {

            $response['status'] = true;
            $response['error'] = [];
        } else {
            $response['status'] = false;
            $response['error'] = ['User do not exist'];
        }
        $res = new Response($serializer->serialize($response,'json'));
        return $res;
    }
}
