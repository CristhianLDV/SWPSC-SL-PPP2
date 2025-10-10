<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Equipo</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte Detallado del Equipo</h2>

    <h3>Datos Generales</h3>
    <table>
        <tr><th>Nombre</th><td>{{ $data->name }}</td></tr>
        <tr><th>Modelo</th><td>{{ $data->hardware_model->name ?? 'N/A' }}</td></tr>
        <tr><th>Estado</th><td>{{ $data->hardware_status->name ?? 'N/A' }}</td></tr>
        <tr><th>Departamento</th><td>{{ $data->department->name ?? 'N/A' }}</td></tr>
        <tr><th>Ubicación</th><td>{{ $data->location->name ?? 'N/A' }}</td></tr>
        <tr><th>Proveedor</th><td>{{ $data->supplier->name ?? 'N/A' }}</td></tr>
        <tr><th>Fecha de compra</th><td>{{ $data->purchase_date }}</td></tr>
        <tr><th>Costo</th><td>S/. {{ number_format($data->purchase_cost, 2) }}</td></tr>
    </table>

    <h3>Componentes</h3>
    <table>
        <thead><tr><th>Nombre</th><th>Modelo</th><th>Cantidad</th></tr></thead>
        <tbody>
        @forelse($data->components as $comp)
            <tr>
                <td>{{ $comp->name }}</td>
                <td>{{ $comp->model_number ?? 'N/A' }}</td>
                <td>{{ $comp->quantity }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Sin componentes asociados</td></tr>
        @endforelse
        </tbody>
    </table>

    <h3>Licencias</h3>
    <table>
        <thead><tr><th>Nombre</th><th>Clave</th><th>Expira</th></tr></thead>
        <tbody>
        @forelse($data->licences as $lic)
            <tr>
                <td>{{ $lic->name }}</td>
                <td>{{ $lic->product_key }}</td>
                <td>{{ $lic->expiration_date ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Sin licencias vinculadas</td></tr>
        @endforelse
        </tbody>
    </table>

    <h3>Mantenimientos</h3>
    <table>
        <thead><tr><th>Fecha</th><th>Tipo</th><th>Costo</th><th>Realizado por</th></tr></thead>
        <tbody>
        @forelse($data->maintenances as $m)
            <tr>
                <td>{{ $m->maintenance_date }}</td>
                <td>{{ $m->maintenance_type }}</td>
                <td>S/. {{ number_format($m->cost, 2) }}</td>
                <td>{{ $m->performed_by }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Sin registros de mantenimiento</td></tr>
        @endforelse
        </tbody>
    </table>

    <h3>Historial de Asignaciones</h3>
    <table>
        <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Fecha entrega</th></tr></thead>
        <tbody>
        @forelse($data->people as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->phone }}</td>
                <td>{{ $p->pivot->checked_out_at ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Sin historial de asignaciones</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
