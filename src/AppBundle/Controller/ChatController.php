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
     * update chat box
     * @Route("/chat", name="chat")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $to = 18;
        $from = 17; //current user;
        //get existing chat message
        $existMessage = $request->query->get('existIds');
        $chatMessage = $this->get('app.add_chat_message');
        $chatPublicMessageEntity = $chatMessage->updateChatMessage($existMessage);
        $chatPrivateMessage = $chatMessage->getPrivateUsers($to, $from);
        $serializer = $this->get('jms_serializer');
        if($chatPublicMessageEntity) {
            $chatMessageJson = $serializer->serialize($chatPublicMessageEntity, 'json');
            $privateMessageJson = $serializer->serialize($chatPrivateMessage, 'json');
            $message = json_decode($chatMessageJson, true);
            $privateMessage = json_decode($privateMessageJson, true);
            $response['body'] = $message;
            $response['privateMessage'] = $privateMessage;
            $response['error'] = [];
            return new Response($serializer->serialize($response,'json'), 200);
        } else {
            $response['body'] = [];
            $response['error'] = [];
            $response['privateMessage'] = [];
            return new Response($serializer->serialize($response,'json'), 200);
        }
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
        $from = 17; //current user;
        $to = '';//18;
        $userMessage = $params['message'];
        //select group
        
        if(!empty($to)) {
            //check if user have private message group
            $messageGroup = $qb->select  ('m')
                ->from    ('AppBundle\Entity\MessageGroup', 'f')
                ->where   ('m.userFrom = :to and m.userTo = :from')
                ->orWhere('m.userFrom = :from and m.userTo = :to')
                ->setParameters(['to' => $to, 'from' => $from])
                ->getQuery()
                ->getOneOrNullResult();
            if ($messageGroup) {

                $userRepository = $this->getDoctrine()->getRepository(User::class);
                $user = $userRepository->find($from);
                $message = new Message();
                $message->setMessage($userMessage);
                $message->setUser($user);
                $message->setTime();
                $message->setMessageGroup($messageGroup);
                //$userTo = $userRepository->find($from);
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
            } else {

                $userRepository = $this->getDoctrine()->getRepository(User::class);
                $user = $userRepository->find($from);
                $userTo = $userRepository->find($to);
                $message = new Message();
                $message->setMessage($userMessage);
                $message->setUser($user);
                $message->setTime();

                $messageGroup = new MessageGroup();
                $messageGroup->setUserFrom($user);
                $messageGroup->setUserTo($userTo);
                $message->setMessageGroup($messageGroup);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->persist($messageGroup);
                $em->flush();
                
            }
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
