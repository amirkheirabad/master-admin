<?php

namespace Modules\Ticket\Export;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class TicketExport implements FromCollection, WithHeadings
{
    private $tickets;
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets->map(function ($ticket) {
            return [
                optional($ticket->store)->store_name ?? '-',
                $ticket->title,
                match ((int) $ticket->priority) {
                1 => 'کم',
                2 => 'متوسط',
                3 => 'بالا',
                4 => 'فوری',
                default => '-',
                },
                $ticket->created_at,
                match ((int) $ticket->status) {
                    0 => 'در حال بررسی توسط ایندکس',
                    1 => 'منتظر پاسخ فروشگاه',
                    2 => 'بسته شده',
                    3 => 'ارجاع به واحد فنی',
                    4 => 'ارجاع به واحد گرافیک دیزاین',
                    default => '-',
                },
                $ticket->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'نام فروشگاه',
            'عنوان',
            'اولویت',
            'تاریخ ثبت',
            'وضعیت',
            'تاریخ آخرین پاسخ'
        ];

    }

}
