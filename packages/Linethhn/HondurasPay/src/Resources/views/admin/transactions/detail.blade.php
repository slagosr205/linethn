<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Transacción #{{ $transaction->id }}
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Detalle de Transacción #{{ $transaction->id }}
        </p>

        <a href="{{ route('admin.honduras-pay.transactions.index') }}"
           class="rounded-lg border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
            Volver a Transacciones
        </a>
    </div>

    <div class="mt-7 grid gap-6 lg:grid-cols-2">
        <!-- Información General -->
        <div class="rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Información General</h3>

            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">ID Transacción:</span>
                    <span class="font-mono text-sm font-medium text-gray-800 dark:text-white">{{ $transaction->transaction_id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Pasarela:</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $transaction->gateway_code }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Monto:</span>
                    <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Estado:</span>
                    <span>
                        @switch($transaction->status)
                            @case('completed')
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Completado</span>
                                @break
                            @case('pending')
                                <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pendiente</span>
                                @break
                            @case('failed')
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Fallido</span>
                                @break
                            @case('refunded')
                                <span class="rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">Reembolsado</span>
                                @break
                            @default
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">{{ $transaction->status }}</span>
                        @endswitch
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Orden:</span>
                    <span class="text-sm font-medium">
                        @if($transaction->order_id)
                            <a href="{{ route('admin.sales.orders.view', $transaction->order_id) }}" class="text-blue-600 hover:underline">
                                #{{ $transaction->order_id }}
                            </a>
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Código de Autorización:</span>
                    <span class="font-mono text-sm text-gray-800 dark:text-white">{{ $transaction->authorization_code ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Número de Referencia:</span>
                    <span class="font-mono text-sm text-gray-800 dark:text-white">{{ $transaction->reference_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Detalles de Pago</h3>

            <div class="space-y-3">
                @if($transaction->card_last_four)
                    <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                        <span class="text-sm text-gray-500">Tarjeta:</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">**** **** **** {{ $transaction->card_last_four }}</span>
                    </div>
                @endif

                @if($transaction->card_brand)
                    <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                        <span class="text-sm text-gray-500">Marca:</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ strtoupper($transaction->card_brand) }}</span>
                    </div>
                @endif

                @if($transaction->bank_name)
                    <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                        <span class="text-sm text-gray-500">Banco:</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $transaction->bank_name }}</span>
                    </div>
                @endif

                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">IP del Cliente:</span>
                    <span class="font-mono text-sm text-gray-800 dark:text-white">{{ $transaction->ip_address ?? 'N/A' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Fecha de Pago:</span>
                    <span class="text-sm text-gray-800 dark:text-white">{{ $transaction->paid_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                    <span class="text-sm text-gray-500">Fecha de Creación:</span>
                    <span class="text-sm text-gray-800 dark:text-white">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                </div>

                @if($transaction->refunded_at)
                    <div class="flex justify-between border-b pb-2 dark:border-gray-700">
                        <span class="text-sm text-gray-500">Fecha de Reembolso:</span>
                        <span class="text-sm text-gray-800 dark:text-white">{{ $transaction->refunded_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                @endif

                @if($transaction->error_message)
                    <div class="mt-3 rounded-lg bg-red-50 p-3 dark:bg-red-900">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">Error:</p>
                        <p class="text-sm text-red-600 dark:text-red-300">{{ $transaction->error_message }}</p>
                    </div>
                @endif
            </div>

            <!-- Refund Button -->
            @if($transaction->canRefund())
                <div class="mt-6 border-t pt-4 dark:border-gray-700">
                    <form action="{{ route('admin.honduras-pay.transactions.refund', $transaction->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Está seguro que desea procesar este reembolso?')">
                        @csrf
                        <div class="flex items-end gap-3">
                            <div>
                                <label class="mb-1 block text-sm text-gray-500">Monto a Reembolsar</label>
                                <input type="number" name="amount" step="0.01" max="{{ $transaction->amount }}"
                                       value="{{ $transaction->amount }}"
                                       class="w-40 rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            </div>
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">
                                Procesar Reembolso
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Respuesta del Gateway -->
    @if($transaction->gateway_response)
        <div class="mt-6 rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Respuesta del Gateway (Raw)</h3>
            <pre class="overflow-x-auto rounded-lg bg-gray-50 p-4 text-xs text-gray-800 dark:bg-gray-800 dark:text-gray-200">{{ json_encode($transaction->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif

    <!-- Metadata -->
    @if($transaction->metadata)
        <div class="mt-4 rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Metadata</h3>
            <pre class="overflow-x-auto rounded-lg bg-gray-50 p-4 text-xs text-gray-800 dark:bg-gray-800 dark:text-gray-200">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif
</x-admin::layouts>
