<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\FoodRepository")
 */
class Food
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
    private $name;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=1000)
     */
    private $content;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $price;

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="App\Entity\Category", inversedBy="foods")
     */
    private $category;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Comment",
     *     mappedBy="food", cascade={"persist", "remove"})
     * @Doctrine\ORM\Mapping\OrderBy({"createdAt"="DESC"})
     */
    private $comments;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(\App\Entity\Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(\App\Entity\Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(\App\Entity\Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFood($this);
        }

        return $this;
    }

    public function removeComment(\App\Entity\Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFood() === $this) {
                $comment->setFood(null);
            }
        }

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
}
