<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MessageGroup;
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
        $from = 24;
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
            $response['status'] = true;
            $response['error'] = [];
            return new Response($serializer->serialize($response,'json'), 200);
        } else {
            $response['body'] = [];
            $response['status'] = true;
            $response['error'] = [];
            return new Response($serializer->serialize($response,'json'), 404);
        }

        die();
    }
}
