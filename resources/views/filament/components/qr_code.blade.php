@php
    $qrValue = $this->cachedMountedTableActionRecord ?? $this->record ?? null;
@endphp

@if($qrValue)
    <div class="flex items-center rounded-lg space-x-3 p-2 bg-white/80">
        <div>
            {!! QrCode::size(76)->color(0, 0, 0)->backgroundColor(209, 209, 209)->generate($qrValue->getUniqueIdentifier()) !!}
        </div>

        <div class="text-black/90 text-sm font-medium">
            Usa el siguiente código QR para identificar este registro.
        </div>
    </div>
@else
    <div class="text-gray-600 text-sm font-medium">
        Un código QR aparecerá aquí una vez que se guarde el registro.
    </div>
@endif