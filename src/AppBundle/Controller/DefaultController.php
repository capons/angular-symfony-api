<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageDetails;
use AppBundle\Entity\MessageTo;
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
       

            
        /*
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find(23);
        $message = new Message();
        $messageTo = new MessageTo();
        $message->setMessage('ohhhhh hellooooo');
        $message->setUser($user);
        $message->setTime();

        $userTo = $userRepository->find(24);
        //response to message
        $messageTo->setUser($userTo);
        $messageTo->setMessage('heeeee');
        $messageTo->setMessageTo($message);
        $messageTo->setTime();

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->persist($messageTo);
        $em->flush();

        die();
        */


        







        //display -> NEED ADD relation message with user\
        /*
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message');
        $allUser = $message->findAll();
        $serializer = $this->get('jms_serializer');
        $t = $serializer->serialize($allUser,'json');
        echo '<pre>';
        print_r(json_decode($t, true));
        echo '</pre>';
        die();
        */
        $em = $this->getDoctrine()->getManager();
        /*
        $entity = $em
            ->getRepository('AppBundle:Message')
            ->createQueryBuilder('e')
            ->join('e.messageTo', 'r')
            ->where('e.user = 23')
            ->getQuery()
            ->getResult();
        */
        $entity = $em->createQuery(
        'SELECT m.userMessage as from_user, mm.userMessage as to_user
                FROM AppBundle:Message m
                LEFT JOIN  AppBundle:MessageTo mm WITH m.id = mm.message
                WHERE m.user = 23
                '
    );
        $entity = $entity->getResult();

        $serializer = $this->get('jms_serializer');
        $t = $serializer->serialize($entity,'json');

        $resultArray = json_decode($t, true);

        foreach ($resultArray as $k => $v) {
            echo 'my message -> '.$v['from_user'];
            echo '<br>';
            echo 'response -> '.$v['to_user'];
            echo '<br>';
            echo '--------------';
            echo '<br>';
        }
        die();


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
