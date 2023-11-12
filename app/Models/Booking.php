<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Booking implements Arrayable
{
    public function __construct(
        private int    $id,
        private int    $userId,
        private int    $parkingSpaceId,
        private Carbon $dateFrom,
        private Carbon $dateTo,
        private float  $priceGbp,
        private Carbon $createdAt,
        private Carbon $updatedAt
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'parking_space_id' => $this->parkingSpaceId,
            'date_from' => $this->dateFrom->toDateString(),
            'date_to' => $this->dateTo->toDateString(),
            'price_gbp' => $this->priceGbp,
            'created_at' => $this->createdAt->toDateTimeString(),
            'updated_at' => $this->updatedAt->toDateTimeString()
        ];
    }
}
