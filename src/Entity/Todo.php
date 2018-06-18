<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="TodoRepository")
 * @ORM\Table(name="todos")
 */
class Todo implements JsonSerializable
{
    /**
     * @var UuidInterface
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $task;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $completed = false;

    /**
     * @var UuidInterface
     * @ORM\Column(type="uuid")
     */
    private $applicationId;

    public function __construct(UuidInterface $id, UuidInterface $applicationId, string $task, bool $completed = false)
    {
        $this->id = $id;
        $this->task = $task;
        $this->completed = $completed;
        $this->applicationId = $applicationId;
    }

    public function update(string $task, bool $completed)
    {
        $this->task = $task;
        $this->completed = $completed;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return ['id' => $this->id->toString(), 'task' => $this->task, 'completed' => $this->completed];
    }

    public function task(): string
    {
        return $this->task;
    }

    public function completed(): bool
    {
        return $this->completed;
    }
}