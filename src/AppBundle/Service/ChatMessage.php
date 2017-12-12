<?php
namespace AppBundle\Service;

use AppBundle\Entity\MessageGroup;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChatMessage
{
    protected $em;

    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function updateChatMessage($existIds)
    {
        $messageGroupRepository = $this->em->getRepository(MessageGroup::class);
        $qb =$messageGroupRepository->createQueryBuilder('m');
        
        if(!empty($existIds)) {
            $chatPublicMessageEntity = $this->em
                ->getRepository('AppBundle:Message')
                ->createQueryBuilder('e')
                ->where('e.messageGroup is null')
                ->where($qb->expr()->notIn('e.id', $existIds))
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
}