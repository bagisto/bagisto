@if ($order->payment->method == 'pagomovil')
    <div class="flex flex-col gap-1.5 mt-4 pt-4 border-t dark:border-gray-800">
        <p class="text-gray-800 font-semibold dark:text-white">
            Detalles de Pago Móvil:
        </p>

        @php
            $additional = $order->payment->additional;
        @endphp

        @if (is_array($additional))
            <p class="text-gray-600 dark:text-gray-300">
                <strong>Referencia:</strong> {{ $additional['reference'] ?? 'N/A' }}
            </p>
            <p class="text-gray-600 dark:text-gray-300">
                <strong>Banco Origen:</strong> {{ $additional['bank_origin'] ?? 'N/A' }}
            </p>
            <p class="text-gray-600 dark:text-gray-300">
                <strong>Teléfono Origen:</strong> {{ $additional['phone_origin'] ?? 'N/A' }}
            </p>
            <p class="text-gray-600 dark:text-gray-300">
                <strong>Cédula Origen:</strong> {{ $additional['id_number_origin'] ?? 'N/A' }}
            </p>
            <p class="text-gray-600 dark:text-gray-300">
                <strong>Fecha Reportada:</strong> {{ $additional['payment_date'] ?? 'N/A' }}
            </p>
        @endif
    </div>
@endif
