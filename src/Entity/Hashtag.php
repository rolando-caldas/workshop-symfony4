<?php

namespace App\Entity;

use App\Entity\Traits\Uuidable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HashtagRepository")
 */
class Hashtag
{

    use Uuidable;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tweet", mappedBy="hashtag", orphanRemoval=true)
     */
    private $tweet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lastTweet;

    public function __construct()
    {
        $this->generateId();
        $this->tweet = new ArrayCollection();
    }

    public static function fromName(string $string) : self
    {
        $hashtag = new self();
        $hashtag->setName($string);
        return $hashtag;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        if (!empty($this->name)) {
            throw new \Exception("The Hashtag can't be changed");
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|Tweet[]
     */
    public function getTweet(): Collection
    {
        return $this->tweet;
    }

    public function addTweet(Tweet $name): self
    {
        if (!$this->tweet->contains($name)) {
            $this->tweet[] = $name;
            $name->setHashtag($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $name): self
    {
        if ($this->tweet->contains($name)) {
            $this->tweet->removeElement($name);
            // set the owning side to null (unless already changed)
            if ($name->getHashtag() === $this) {
                $name->setHashtag(null);
            }
        }

        return $this;
    }

    public function getLastTweet(): ?int
    {
        return $this->lastTweet;
    }

    public function setLastTweet(?int $lastTweet): self
    {
        $this->lastTweet = $lastTweet;

        return $this;
    }
}
