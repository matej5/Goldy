<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="text")
     */
    private $content;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true)
     */
    private $post;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Food", inversedBy="comments")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=true)
     */
    private $food;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @Doctrine\ORM\Mapping\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPost(): \App\Entity\Post
    {
        return $this->post;
    }

    public function setPost(\App\Entity\Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): \App\Entity\User
    {
        return $this->user;
    }

    public function setUser(\App\Entity\User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFood(): ?Food
    {
        return $this->food;
    }

    public function setFood(?Food $food): self
    {
        $this->food = $food;

        return $this;
    }
}
