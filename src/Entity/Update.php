<?php

namespace App\Entity;

use App\Repository\UpdateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UpdateRepository::class)]
#[ORM\Table(name: '`update`')]
class Update
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $moyenne = null;

    #[ORM\Column]
    private ?float $max = null;

    #[ORM\Column]
    private ?float $min = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): self
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }

    public function setMax(float $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function setMin(float $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getDatime(): ?\DateTimeInterface
    {
        return $this->datime;
    }

    public function setDatime(\DateTimeInterface $datime): self
    {
        $this->datime = $datime;

        return $this;
    }
}
