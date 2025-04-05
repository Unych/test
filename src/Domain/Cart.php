<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    public function __construct(
        readonly private string   $uuid,
        readonly private Customer $customer,
        readonly private string   $paymentMethod,
        array                     $items = []
    )
    {
        $this->items = $items;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function getTotal(): float
    {
        return array_reduce($this->items, fn($sum, CartItem $item) => $sum + $item->getTotal(), 0.0);
    }
}
