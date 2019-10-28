<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Post
 *
 * @package                                                     App\Entity
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\PostRepository")
 * @Doctrine\ORM\Mapping\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $content;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Comment",
     *     mappedBy="post", cascade={"persist", "remove"})
     * @Doctrine\ORM\Mapping\OrderBy({"createdAt"="DESC"})
     */
    private $comments;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string",       length=255, nullable=true)
     * @Symfony\Component\Validator\Constraints\NotBlank(message="Upload your image")
     * @Symfony\Component\Validator\Constraints\File(mimeTypes={         "image/png", "image/jpeg" })
     */
    private $image;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\User", inversedBy="post")
     * @Doctrine\ORM\Mapping\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @Doctrine\ORM\Mapping\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime('now');
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param  Comment $comment
     * @return $this
     */
    public function addComment(\App\Entity\Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(\App\Entity\Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?\App\Entity\User
    {
        return $this->user;
    }

    public function setUser(?\App\Entity\User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
