<x-shop::layouts>
    <div class="container px-5 py-10 mx-auto">
        <div class="flex flex-col items-center">
            <h1 class="text-3xl font-bold mb-5">Reporte de Pago Móvil</h1>
            
            <div class="w-full max-w-lg bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-navyBlue">Datos para realizar el pago:</h2>
                    <div class="space-y-2 text-gray-700">
                        <p><strong>Banco:</strong> {{ $bank }}</p>
                        <p><strong>Teléfono:</strong> {{ $phone }}</p>
                        <p><strong>Cédula / RIF:</strong> {{ $id_number }}</p>
                        <p class="mt-4 p-3 bg-blue-50 text-blue-800 rounded">
                            <strong>Monto a transferir:</strong> 
                            <span class="text-lg font-bold">
                                {{ core()->currency($cart->grand_total) }}
                                @if(core()->getCurrentCurrencyCode() != 'VES')
                                    <br>
                                    <small class="text-sm font-normal">
                                        (Equivalente en Bolívares según la tasa configurada)
                                    </small>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                <form action="{{ route('pagomovil.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referencia del Pago (Últimos 4-6 dígitos)</label>
                        <input type="text" name="reference" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Banco desde el que pagaste</label>
                        <input type="text" name="bank_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tu número de teléfono</label>
                        <input type="text" name="phone_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tu Cédula de Identidad</label>
                        <input type="text" name="id_number_origin" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Pago</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full p-2 border border-gray-300 rounded focus:ring-navyBlue focus:border-navyBlue" required>
                    </div>

                    <button type="submit" class="w-full bg-navyBlue text-white font-bold py-3 px-4 rounded-xl hover:bg-black transition-all">
                        Confirmar y Finalizar Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-shop::layouts>
