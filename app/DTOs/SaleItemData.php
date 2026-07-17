<?php

namespace App\DTOs;

readonly class SaleItemData
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public float $unit_price,
        public float $discount = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            unit_price: (float) $data['unit_price'],
            discount: (float) ($data['discount'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount' => $this->discount,
        ];
    }
}
