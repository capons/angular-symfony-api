<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

//Exclusion -> display only fields with this tag -> @JMS\Expose
/**
 * User
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table(name="message_group",options={"collate"="utf8_general_ci"})
 * @ORM\Entity()
 */
class MessageGroup
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    public $id;
    
    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="user_from_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $userFrom;

    public function setUserFrom(\AppBundle\Entity\User $user)
    {
        $this->userFrom = $user;
        return $this->userFrom;
    }

    public function getUserFrom()
    {
        return $this->userFrom;
    }

    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="user_to_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $userTo;

    public function setUserTo(\AppBundle\Entity\User $user)
    {
        $this->userTo = $user;
        return $this->userTo;
    }

    public function getUserTo()
    {
        return $this->userTo;
    }

    public function getId()
    {
        return $this->id;
    }


}
