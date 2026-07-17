<?php

namespace App\DTOs;

class PurchaseItemData
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public float $unit_price,
        public ?float $selling_price,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: (int) $data['product_id'],
            quantity: (int) $data['quantity'],
            unit_price: (float) $data['unit_price'],
            selling_price: isset($data['selling_price']) && $data['selling_price'] !== '' && $data['selling_price'] !== null
                ? (float) $data['selling_price']
                : null,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'selling_price' => $this->selling_price,
        ];
    }
}
