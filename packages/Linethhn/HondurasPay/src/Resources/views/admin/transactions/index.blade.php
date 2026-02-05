<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Transacciones
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Historial de Transacciones
        </p>
    </div>

    <!-- Filtros -->
    <div class="mt-4 rounded-lg border bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="mb-1 block text-xs text-gray-500">Estado</label>
                <select name="status" class="rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-500">Desde</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-500">Hasta</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>

            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                Filtrar
            </button>

            <a href="{{ route('admin.honduras-pay.transactions.index') }}" class="rounded-lg border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                Limpiar
            </a>
        </form>
    </div>

    <!-- Tabla de Transacciones -->
    <div class="mt-4 overflow-x-auto rounded-lg border bg-white dark:border-gray-800 dark:bg-gray-900">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">ID</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Transacción</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Orden</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Monto</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Pasarela</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Tarjeta</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Fecha</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3">#{{ $transaction->id }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $transaction->transaction_id ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($transaction->order_id)
                                <a href="{{ route('admin.sales.orders.view', $transaction->order_id) }}" class="text-blue-600 hover:underline">
                                    #{{ $transaction->order_id }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $transaction->gateway_code }}</td>
                        <td class="px-4 py-3">
                            @switch($transaction->status)
                                @case('completed')
                                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Completado</span>
                                    @break
                                @case('pending')
                                    <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pendiente</span>
                                    @break
                                @case('processing')
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Procesando</span>
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
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            @if($transaction->card_last_four)
                                **** {{ $transaction->card_last_four }}
                                @if($transaction->card_brand)
                                    ({{ $transaction->card_brand }})
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.honduras-pay.transactions.detail', $transaction->id) }}"
                               class="text-blue-600 hover:underline">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            No hay transacciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($transactions->hasPages())
            <div class="border-t p-4 dark:border-gray-700">
                {{ $transactions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin::layouts>
