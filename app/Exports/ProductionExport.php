<?php

namespace App\Exports;

use App\Models\Production as ProductionModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithProperties;
use Illuminate\Support\Facades\Auth;

class ProductionExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithProperties
{
    protected $startDate;
    protected $endDate;
    protected $user;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->user = Auth::user();
    }

    public function collection()
    {
        $query = ProductionModel::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Transaction Number',
            'Date',
            'SP Number',
            'Vehicle Number',
            'TBS Quantity (KG)',
            'KG Quantity',
            'Division',
            'PKS',
            'Created At',
            'Updated At',
        ];
    }

    public function map($production): array
    {
        return [
            $production->id,
            $production->transaction_number,
            $production->date->format('Y-m-d'),
            $production->sp_number,
            $production->vehicle_number,
            $production->tbs_quantity,
            $production->kg_quantity,
            $production->divisionRel ? $production->divisionRel->name : $production->division,
            $production->pks,
            $production->created_at->format('Y-m-d H:i:s'),
            $production->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function properties(): array
    {
        return [
            'creator' => $this->user ? $this->user->name : 'System',
            'lastModifiedBy' => $this->user ? $this->user->name : 'System',
            'title' => 'Production Data Export',
            'description' => 'Exported by ' . ($this->user ? $this->user->name : 'System') . ' on ' . now()->format('Y-m-d H:i:s'),
            'subject' => 'Production Data',
        ];
    }
}