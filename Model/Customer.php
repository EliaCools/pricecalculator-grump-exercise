<?php

declare(strict_types=1);

class Customer
{
    private int $id;
    private string $name;
    private int $groupId;
    private ?int $fixDiscount;
    private ?int $varDiscount;

    public function __construct(int $id, string $name, int $groupId, ?int $fixDiscount, ?int $varDiscount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->groupId = $groupId;
        $this->fixDiscount = $fixDiscount;
        $this->varDiscount = $varDiscount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getFixDiscount(): ?int
    {
        return $this->fixDiscount;
    }

    public function getVarDiscount(): ?int
    {
        return $this->varDiscount;
    }
}
