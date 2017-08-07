<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
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

    public function __construct()
    {
        $this->messageDetailsGroup = new ArrayCollection();
    }
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    public $id;
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
     *  @ORM\OneToMany(targetEntity="MessageDetails", mappedBy="message")
     *  @JMS\MaxDepth(1)
     *
     */
    public $messageDetails;

    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="MessageDetails", inversedBy="message_details")
     * @ORM\JoinColumn(name="message_details_id", referencedColumnName="id")
     * @JMS\Expose
     * @JMS\MaxDepth(1)
     */
    private $messageDetailsGroup;

    public function setMessageGroup(\AppBundle\Entity\MessageDetails $group)
    {
        $this->messageDetailsGroup = $group;
        return $this->messageDetailsGroup;
    }

    public function getMessageGroup()
    {
        return $this->messageDetailsGroup;
    }
    
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
}
