<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ParkingSpaceIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dateFrom' => [
                'required',
                'date_format:Y-m-d',
            ],
            'dateTo' => [
                'required',
                'date_format:Y-m-d',
                'after:dateFrom',
            ]
        ];
    }

    public function getDateFrom(): Carbon
    {
        return new Carbon($this->get('dateFrom'));
    }

    public function getDateTo(): Carbon
    {
        return new Carbon($this->get('dateTo'));
    }
}
