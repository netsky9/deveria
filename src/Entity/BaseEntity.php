<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdOn;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     */
    protected $modifiedOn;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return static
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $now = new DateTime("now");

        if (!$this->getCreatedOn()) {
            $this->setCreatedOn($now);
        }

        if (!$this->getModifiedOn()) {
            $this->modifiedOn = $now;
        }
    }

    /**
     * @return DateTime
     */
    public function getCreatedOn(): ?DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param DateTime $createdOn
     *
     * @return static
     */
    public function setCreatedOn(DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedOn(): ?DateTime
    {
        return $this->modifiedOn;
    }

    /**
     * @param DateTime|null $modifiedOn
     *
     * @return static
     */
    public function setModifiedOn(?DateTime $modifiedOn)
    {
        $this->modifiedOn = $modifiedOn;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->modifiedOn = (new DateTime("now"));
    }
}
