<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageGroup;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ChatController extends Controller
{

    /**
     * @Route("/chat", name="chat")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        // $formData = $request->request->all();
        $to = 23;
        $from = 17;
        $messageGroupRepository = $this->getDoctrine()->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        //select private message
        /*
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
        */
        $em = $this->getDoctrine()->getManager();
        $chatPublicMessageEntity = $em
            ->getRepository('AppBundle:Message')
            ->createQueryBuilder('e')
            ->where('e.messageGroup is null')
            ->getQuery()
            ->getResult();
        $serializer = $this->get('jms_serializer');
        if($chatPublicMessageEntity) {
            $chatMessageJson = $serializer->serialize($chatPublicMessageEntity, 'json');
            $message = json_decode($chatMessageJson, true);
            $response['body'] = $message;
            $response['error'] = [];
            return new Response($serializer->serialize($response,'json'), 200);
        } else {
            $response['body'] = [];
            $response['error'] = [];
            return new Response($serializer->serialize($response,'json'), 404);
        }

        die();
    }

    /**
     * @Route("/chat/message", name="addMessage")
     * @Method({"POST"})
     */
    public function addMesageAction(Request $request)
    {
   //     $formData = $request->request->all();
        $serializer = $this->get('jms_serializer');

        $params = array();
        $content =$request->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true); // 2nd param to get as array
        }
        
        //add chat private message
        $messageGroupRepository = $this->getDoctrine()->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        $to = 23;
        $from = 17;
        $userMessage = $params['message'];
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
            $userTo = $userRepository->find($to);
            $message = new Message();
            $message->setMessage($userMessage);
            $message->setUser($user);
            $message->setTime();
        

            $messageGroup->setUserFrom($user);
            $messageGroup->setUserTo($userTo);
            $message->setMessageGroup($messageGroup);


            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->persist($messageGroup);
            $em->flush();
        } else {
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->find($from);
            $message = new Message();
            $message->setMessage($userMessage);
            $message->setUser($user);
            $message->setTime();
            //$userTo = $userRepository->find($from);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }

        //return last save object
        $messageRepository = $this->getDoctrine()->getRepository(Message::class);
        $lastMessage = $messageRepository->find($message->getId());
        $array = $serializer->toArray($lastMessage);
        $response['body'] = $array;
        $response['status'] = true;
        $response['error'] = [];
        return new Response($serializer->serialize($response,'json'));
        
    }
}
