<?php

namespace App\Exports;

use App\Models\Hardware;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventarioExport implements FromCollection, WithHeadings
{
    protected ?int $statusId;
    protected ?int $departmentId;
    protected ?int $supplierId;
    protected ?int $locationId;
    protected ?string $desde;
    protected ?string $hasta;

    public function __construct(
        ?int $statusId = null,
        ?int $departmentId = null,
        ?int $supplierId = null,
        ?int $locationId = null,
        ?string $desde = null,
        ?string $hasta = null,
    ) {
        $this->statusId = $statusId;
        $this->departmentId = $departmentId;
        $this->supplierId = $supplierId;
        $this->locationId = $locationId;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function collection()
    {
        $q = Hardware::with(['hardware_status', 'department', 'location', 'supplier', 'hardware_model']);

        if ($this->statusId)     $q->where('hardware_status_id', $this->statusId);
        if ($this->departmentId) $q->where('department_id', $this->departmentId);
        if ($this->supplierId)   $q->where('supplier_id', $this->supplierId);
        if ($this->locationId)   $q->where('location_id', $this->locationId);
        if ($this->desde)        $q->whereDate('purchase_date', '>=', $this->desde);
        if ($this->hasta)        $q->whereDate('purchase_date', '<=', $this->hasta);

        return $q->get([
            'id',
            'name',
            'serial_number',
            'purchase_date',
            'purchase_cost',
            'order_number',
            'quantity',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Equipo',
            'N° de Serie',
            'Fecha de Compra',
            'Costo (S/)',
            'N° de Orden',
            'Cantidad',
        ];
    }
}
