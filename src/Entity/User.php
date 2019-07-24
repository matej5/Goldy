<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @Doctrine\ORM\Mapping\Id()
     * @Doctrine\ORM\Mapping\GeneratedValue()
     * @Doctrine\ORM\Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Doctrine\ORM\Mapping\Column(type="json")
     */
    private $roles = [];

    /**
     * @var                       string The hashed password
     * @Doctrine\ORM\Mapping\Column(type="string")
     */
    private $password;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", orphanRemoval=true)
     */
    private $post;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(\App\Entity\Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function createAvatar()
    {
        $fn = $this->firstname;
        $ln = $this->lastname;
        $em = $this->email;
        $siteRoot = __DIR__ . '/../../public/images/';

        $newUserSubfolder = $siteRoot . $em;
        if (!file_exists($newUserSubfolder)) {
            mkdir($newUserSubfolder, 0777, true);
        }

        $fnInt = 0;
        $lnInt = 0;
        $emInt = 0;

        $x = strlen($fn);
        $y = strlen($ln);

        for ($i = 0; $i < $x - 1; $i++) {
            $fnInt += ord($fn[$i]);
        }

        for ($i = 0; $i < $y - 1; $i++) {
            $lnInt += ord($ln[$i]);
        }

        for ($i = 0; $em[$i] != '@'; $i++) {
            $emInt += ord($em[$i]);
        }

        $fnColor = $fnInt;
        $lnColor = $lnInt;
        $emColor = $emInt;

        while ($fnColor > 235) {
            $fnColor = $fnColor / 2 + 40;
        }

        while ($lnColor > 235) {
            $lnColor = $lnColor / 2 + 40;
        }

        while ($emColor > 235) {
            $emColor = $emColor / 2 + 40;
        }

        $total = ($fnInt + $lnInt + $emInt) * 21;
        $im = imagecreate(420, 420);
        $white = ImageColorAllocate($im, 255, 255, 255);
        $color = ImageColorAllocate($im, $fnColor, $lnColor, $emColor);
        ImageFilledRectangle($im, 0, 0, 420, 420, $white);

        $this->draw($im, $total, $color);

        $save = $newUserSubfolder . '/avatar.jpeg';
        imagejpeg($im, $save, 100);   //Saves the image

        imagedestroy($im);
    }

    public function draw($im, $total, $color)
    {
        $startX = 35;
        $startY = 35;
        for ($i = 0; $i < 5; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if (pow(2, $i * 3 + $j) & $total) {
                    ImageFilledRectangle($im, $startX, $startY, $startX + 70, $startY + 70, $color);
                    if ($j != 2) {
                        ImageFilledRectangle($im, 315 - 70 * $j, $startY, 385 - 70 * $j, $startY + 70, $color);
                    }
                }

                $startX += 70;
            }

            $startX = 35;
            $startY += 70;
        }

        return $im;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->email;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(\App\Entity\Post $post): self
    {
        if (!$this->post->contains($post)) {
            $this->post[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(\App\Entity\Post $post): self
    {
        if ($this->post->contains($post)) {
            $this->post->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }
}
