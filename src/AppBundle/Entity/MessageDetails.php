<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="message_details")
 * @ORM\Entity()
 *
 */
class MessageDetails
{
    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->message = new ArrayCollection();
    }
    
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    public $id;


    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $user;

    //related with entity User
    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="message")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * @JMS\Expose
     */
    private $message;

    /**
     *  @ORM\OneToMany(targetEntity="MessageDetails", mappedBy="message")
     *  @JMS\Expose
     *  @JMS\MaxDepth(0)
     *
     */
    public $messageGroup;

    
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }
   
    public function getUser()
    {
        return $this->user;
    }
    
    public function setMessage(\AppBundle\Entity\Message $mesage = null)
    {
        return $this->message = $mesage;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    

}
