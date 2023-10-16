<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default"="GENERATE_TOKEN"})
     */
    private ?string $activationKey;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * Constructor for the class.
     *
     * @param string $email The email parameter.
     */
    public function __construct(string $email)
    {
        $this->id = null;
        $this->email = $email;
        $this->activationKey = bin2hex(random_bytes(32));
    }

    /**
     * Retrieves the email associated with this object.
     *
     * @return string The email associated with this object.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the value of isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Sets the value of the isActive property.
     *
     * @param bool $isActive The new value of the isActive property.
     * @return $this The current object.
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of activationKey
     */ 
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Sets the activation key for the object.
     *
     * @param string $activationKey The activation key to be set.
     * @return $this The current object.
     */
    public function setActivationKey(string $activationKey)
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }
}
