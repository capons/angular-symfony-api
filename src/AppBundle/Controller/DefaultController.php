<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageDetails;
use AppBundle\Entity\MessageGroup;
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
        //add chat private message
        $messageGroupRepository = $this->getDoctrine()->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        $to = 23;
        $from = 24;
        $userMessage = 'dsfdsfsdfdsfds';
        //select group
        $messageGroup = $qb->select  ('m')
            ->from    ('AppBundle\Entity\MessageGroup', 'f')
            ->where   ('m.userFrom = :to and m.userTo = :from')
            ->orWhere('m.userFrom = :from and m.userTo = :to')
            ->setParameters(['to' => $to, 'from' => $from])
            ->getQuery()
            ->getOneOrNullResult();


        if($messageGroup) {
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->find($from);
            $message = new Message();
            $message->setMessage($userMessage);
            $message->setUser($user);
            $message->setTime();
            //response to message
            $message->setMessageGroup($messageGroup);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        } else {
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->find($to);
            $message = new Message();
            $messageGroup = new MessageGroup();
            $message->setMessage('fdsfdsfsdfsf');
            $message->setUser($user);
            $message->setTime();
            $userTo = $userRepository->find($from);
            //response to message
            $messageGroup->setUserFrom($user);
            $messageGroup->setUserTo($userTo);
            $message->setMessageGroup($messageGroup);

        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->persist($messageGroup);
        $em->flush();







        $mGroup = $qb->select  ('m')
            ->from    ('AppBundle\Entity\MessageGroup', 'd')
            ->where   ('m.userFrom = :to and m.userTo = :from')
            ->orWhere('m.userFrom = :from and m.userTo = :to')
            ->setParameters(['to' => $to, 'from' => $from])
            ->getQuery()
            ->getOneOrNullResult();
        if($mGroup) {
            //select private message
            $em = $this->getDoctrine()->getManager();
            $entity = $em
                ->getRepository('AppBundle:Message')
                ->createQueryBuilder('e')
                ->where('e.messageGroup = :groupId')
                ->setParameter('groupId', $mGroup->getId())
                ->getQuery()
                ->getResult();
            $serializer = $this->get('jms_serializer');
            $t = $serializer->serialize($entity, 'json');
            $resultArray = json_decode($t, true);
        }
        $i = 1;
        foreach ($resultArray as $k) {
            print_r($i.' '.$k['user_message']);
            echo '<br>';
            $i++;
        }


        die();







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
        //inner join message_group ON  message_group.user_from = message.user_id and message_group.user_to = message_to.user_id 


        /*

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
        */





        




        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
