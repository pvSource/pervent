<?php

namespace App\Entity;

use App\EventListener\ContestEntityListener;
use App\Repository\ContestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContestRepository::class)]
#[UniqueEntity(
    fields: ['slug'],
    message: 'This slug is already use in other contest.'
)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: true)]
#[ORM\HasLifecycleCallbacks]
class Contest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishAt = null;

    /**
     * @var Collection<int, Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'contest')]
    private Collection $works;

    #[ORM\ManyToOne(inversedBy: 'contests')]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\PrePersist]
    public function setDefaultValues(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        if (!$this->beginAt) {
            $this->beginAt = new \DateTimeImmutable();
        }
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug($this->name . '-' . ((new \DateTimeImmutable())->format('YmdHis')))->toString();
        }
    }

    public function __construct()
    {
        $this->works = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeImmutable $beginAt): static
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeImmutable
    {
        return $this->finishAt;
    }

    public function setFinishAt(?\DateTimeImmutable $finishAt): static
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    /**
     * @return Collection<int, Work>
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Work $work): static
    {
        if (!$this->works->contains($work)) {
            $this->works->add($work);
            $work->setContest($this);
        }

        return $this;
    }

    public function removeWork(Work $work): static
    {
        if ($this->works->removeElement($work)) {
            // set the owning side to null (unless already changed)
            if ($work->getContest() === $this) {
                $work->setContest(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
//
//    #[ORM\PrePersist]
//    public function setAuthorValue(
//        Security $security
//    ): void
//    {
//        $this->author = $security->getUser();
//    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
