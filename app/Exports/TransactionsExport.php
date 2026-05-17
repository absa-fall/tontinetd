<?php

namespace App\Exports;

use App\Models\Membre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransactionsExport implements FromCollection, WithHeadings, WithTitle
{
    protected $membre;

    public function __construct(Membre $membre)
    {
        $this->membre = $membre;
    }

    public function collection()
    {
        return $this->membre->cotisations->map(function ($c) {
            return [
                'ID'       => '#' . $c->id,
                'Montant'  => $c->montant . ' F CFA',
                'Date'     => \Carbon\Carbon::parse($c->date_cotisation)->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Montant', 'Date'];
    }

    public function title(): string
    {
        return 'Transactions - ' . $this->membre->nom;
    }
}