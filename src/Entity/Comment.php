<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="comments")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $commentDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reported;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommentDate(): ?\DateTimeInterface
    {
        return $this->commentDate;
    }

    public function setCommentDate(?\DateTimeInterface $commentDate): self
    {
        $this->commentDate = $commentDate;

        return $this;
    }

    public function getReported(): ?bool
    {
        return $this->reported;
    }

    public function setReported(?bool $reported): self
    {
        $this->reported = $reported;

        return $this;
    }
}
