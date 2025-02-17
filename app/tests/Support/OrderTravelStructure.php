<?php

namespace Tests\Support;

class OrderTravelStructure
{
    public static function get(): array
    {
        return [
            'current_page',
            'data' => [
                '*' => [
                    'uuid',
                    'origin',
                    'destination',
                    'start_date',
                    'end_date',
                    'status',
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ];
    }
}
