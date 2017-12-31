<?php
namespace AppBundle\Service;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageGroup;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChatMessage
{
    protected $em;
    protected $jms;

    
    public function __construct(\Doctrine\ORM\EntityManager $em, Serializer $jms)
    {
        $this->em = $em;
        $this->jms = $jms;
    }

    public function updateChatMessage($existIds)
    {
        $messageGroupRepository = $this->em->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        
        if(!empty($existIds)) {
            $chatPublicMessageEntity = $this->em
                ->getRepository('AppBundle:Message')
                ->createQueryBuilder('e')
                ->where($qb->expr()->notIn('e.id', $existIds))
                ->andWhere('e.messageGroup IS NULL')
                ->andWhere('e.id >'.max($existIds))
                ->orderBy('e.created', 'DESC')
                ->setMaxResults(5)
                //   ->setFirstResult(10)
                ->getQuery()
                ->getResult();
        } else {
            $chatPublicMessageEntity = $this->em
                ->getRepository('AppBundle:Message')
                ->createQueryBuilder('e')
                ->where('e.messageGroup is null')
                //  ->where($qb->expr()->notIn('e.id', $subQuery))
                ->orderBy('e.created', 'DESC')
                ->setMaxResults(5)
                //   ->setFirstResult(10)
                ->getQuery()
                ->getResult();
        }
        return $chatPublicMessageEntity;
    }

    /**
     * @param $to
     * @param $from
     * @return array
     * get all private users
     */
    public function getPrivateUsers($to, $from)
    {
        $messageGroupRepository = $this->em->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        $privateUsers = [];
        $messageGroup = $qb->select('m')
            ->where   ('m.userFrom = :from')
            ->orWhere('m.userTo = :to')
            ->setParameters(['to' => $to, 'from' => $from])
            ->getQuery()
            ->getResult();
        
        if(!empty($messageGroup)) {
            foreach ($messageGroup as $group) {
                //need return all users in with we have private message
                $groupJson =  $privateMessageJson = $this->jms->serialize($group, 'json');
                $userGroup = json_decode($groupJson, true);
                if($userGroup['user_from']['id'] != $from) {
                    $privateUsers[] = $group->getUserFrom();
                }
                if($userGroup['user_to']['id'] != $from) {
                    $privateUsers[] = $group->getUserTo();
                }
            }
        }
        return $privateUsers;
    }
}