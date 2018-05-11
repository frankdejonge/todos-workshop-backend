<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="ApplicationRepository")
 * @ORM\Table(name="applications")
 */
class Application implements JsonSerializable
{
    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid")
     */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
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
        return ['id' => $this->id->toString()];
    }
}