<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExternalFeedRepository")
 */
class ExternalFeed
{


    const SOURCE_THE_REGISTER = 'theregister';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=8000)
     */
    private $body;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $external_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExternalFeedKeyWord", mappedBy="externalFeed")
     */
    private $externalFeedKeyWords;

    public function __construct()
    {
        $this->externalFeedKeyWords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        if (!in_array($source, array(self::SOURCE_THE_REGISTER))) {
            throw new \InvalidArgumentException("Invalid source");
        }
        $this->source = $source;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(string $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getUpdated(): ?int
    {
        return $this->updated;
    }

    public function setUpdated(int $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|ExternalFeedKeyWord[]
     */
    public function getExternalFeedKeyWords(): Collection
    {
        return $this->externalFeedKeyWords;
    }
}
