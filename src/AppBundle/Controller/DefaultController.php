<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageDetails;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       
        
      //  $user = new User();
            

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find(23);
        $messageDetails = new MessageDetails();
        $message = new Message();
        $message->setMessage('next message');
        $messageDetails->setUser($user);
        $messageDetails->setMessage($message);
        $message->setMessageGroup($messageDetails);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->persist($messageDetails);
        $em->flush();
        







        //display -> NEED ADD relation message with user
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:MessageDetails');
        $allUser = $user->findAll();
        $serializer = $this->get('jms_serializer');

        $t = $serializer->serialize($allUser,'json');

        echo '<pre>';
        print_r(json_decode($t, true));
        echo '</pre>';
        die();



        




        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
