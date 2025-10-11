<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de Inventario</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; }
    h2 { text-align:center; margin: 0 0 10px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ccc; padding:6px; text-align:left; }
    th { background:#f4f4f4; }
  </style>
</head>
<body>
  <h2>Reporte General de Inventario TIC</h2>
  <table>
    <thead>
      <tr>
        <th>Equipo</th><th>Estado</th><th>Área</th><th>Ubicación</th>
        <th>Proveedor</th><th>Modelo</th><th>Fecha compra</th><th>Costo</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $item)
      <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->hardwareStatus->name ?? '-' }}</td>
        <td>{{ $item->department->name ?? '-' }}</td>
        <td>{{ $item->location->name ?? '-' }}</td>
        <td>{{ $item->supplier->name ?? '-' }}</td>
        <td>{{ $item->hardwareModel->name ?? '-' }}</td>
        <td>{{ $item->purchase_date }}</td>
        <td>S/. {{ number_format($item->purchase_cost, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
