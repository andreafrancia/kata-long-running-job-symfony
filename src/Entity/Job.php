<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $result = null;

    #[ORM\Column(type: 'blob', length: 255)]
    /** @var resource */
    private $input;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(JobStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): void
    {
        $this->result = $result;
    }

    /**
     * @param resource $input
     * @return void
     */
    public function setInput($input): void
    {
        $this->input = $input;
    }

    public function getInputAsString(): string
    {
        rewind($this->input);
        return stream_get_contents($this->input);
    }
}
