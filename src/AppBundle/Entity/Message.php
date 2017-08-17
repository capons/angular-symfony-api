<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

//Exclusion -> display only fields with this tag -> @JMS\Expose
/**
 * User
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table(name="message",options={"collate"="utf8_general_ci"})
 * @ORM\Entity()
 */
class Message
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    public $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    
    public function setTime()
    {
        $this->created =  new \DateTime("now");
    }
    
    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotNull(message="You have to choose a username (this is my custom validation message).")
     *  @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     * @JMS\Expose
     */
    private $userMessage;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->userMessage;
    }
    /**
     * @param $password
     * @return mixed
     */
    public function setMessage($message)
    {
        $this->userMessage = $message;
        return $this->userMessage;
    }
    
    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $user;

    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this->user;
    }

    public function getUser()
    {
        return $this->user;
    }

    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="MessageGroup", inversedBy="message_group")
     * @ORM\JoinColumn(name="message_group_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $messageGroup;

    public function setMessageGroup(\AppBundle\Entity\MessageGroup $messageGroup)
    {
        $this->messageGroup = $messageGroup;
        return $this->messageGroup;
    }

    public function getMessageGroup()
    {
        return $this->messageGroup;
    }
}
