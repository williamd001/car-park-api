<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Booking implements Arrayable
{
    public function __construct(
        private int    $id,
        private int    $customerId,
        private int    $parkingSpaceId,
        private Carbon $dateFrom,
        private Carbon $dateTo,
        private float  $priceGbp,
        private Carbon $createdAt,
        private Carbon $updatedAt
    )
    {
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'parking_space_id' => $this->parkingSpaceId,
            'date_from' => $this->dateFrom->toDateString(),
            'date_to' => $this->dateTo->toDateString(),
            'price_gbp' => $this->priceGbp,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString()
        ];
    }
}
