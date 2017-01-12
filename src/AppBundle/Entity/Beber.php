<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Beber
 *
 * @ORM\Table(name="beber")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BeberRepository")
 */
class Beber
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime $contentChanged
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"location"})
     */
    private $contentChanged;

    /**
     * @var string $createdBy
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")     *
     * @Gedmo\Blameable(on="create")
     */
    private $createdBy;

    /**
     * @var string $updatedBy
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @Gedmo\Blameable(on="update")
     */
    private $updatedBy;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setLocation(Location $location = null)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getContentChanged()
    {
        return $this->contentChanged;
    }
}

