<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FlowRepository;
use DateTimeImmutable;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: FlowRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: 'flows', uniqueConstraints: ['(name="flow_record_idx", columns={"datetime", "station_id", "river_id"})'])]
class Flow
{
    public function __construct(
        #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
        private int $riverId,
        #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
        private int $stationId,
        #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $datetime,
        #[\Doctrine\ORM\Mapping\Column(type: 'decimal', precision: 10, scale: 3, nullable: true)]
        private ?float $level,
        #[\Doctrine\ORM\Mapping\Column(type: 'decimal', precision: 10, scale: 3)]
        private float $flow,
        #[\Doctrine\ORM\Mapping\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $createdAt,
        #[\Doctrine\ORM\Mapping\Column(type: 'string', length: 255, nullable: true)]
        private ?string $status = null,
        #[\Doctrine\ORM\Mapping\Id]
        #[\Doctrine\ORM\Mapping\GeneratedValue]
        #[\Doctrine\ORM\Mapping\Column(type: 'integer')]
        private ?int $id = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): DateTimeImmutable
    {
        return $this->datetime;
    }

    public function getLevel(): ?float
    {
        return $this->level;
    }

    public function getFlow(): float
    {
        return $this->flow;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getStationId(): ?int
    {
        return $this->stationId;
    }

    public function getRiverId(): ?int
    {
        return $this->riverId;
    }
}
