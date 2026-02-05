<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Panel de Pagos
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Honduras Pay - Panel de Control
        </p>

        <div class="flex items-center gap-x-2.5">
            <a href="{{ route('admin.honduras-pay.gateways.index') }}"
               class="primary-button">
                Gestionar Pasarelas
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="mt-7 grid gap-4 sm:grid-cols-3">
        <!-- Total Ventas del Mes -->
        <div class="rounded-lg border bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                    <span class="text-xl text-green-600">L</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ventas del Mes</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">
                        L {{ number_format($statistics['total_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Transacciones -->
        <div class="rounded-lg border bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                    <span class="text-xl text-blue-600">#</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Transacciones</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $statistics['total_transactions'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Promedio -->
        <div class="rounded-lg border bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                    <span class="text-xl text-purple-600">~</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Promedio por Transacción</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">
                        L {{ number_format($statistics['average_amount'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pasarelas Configuradas -->
    <div class="mt-7 rounded-lg border bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b p-4 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                Pasarelas Configuradas
            </p>
            <a href="{{ route('admin.honduras-pay.gateways.create') }}" class="text-sm text-blue-600 hover:underline">
                + Agregar Nueva
            </a>
        </div>

        <div class="p-4">
            @forelse($gateways as $gateway)
                <div class="mb-3 flex items-center justify-between rounded-lg border p-3 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $gateway->active ? 'bg-green-100' : 'bg-gray-100' }}">
                            <span class="{{ $gateway->active ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $gateway->active ? '✓' : '✗' }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $gateway->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $gateway->code }} |
                                {{ $gateway->sandbox ? 'Sandbox' : 'Producción' }} |
                                {{ $gateway->currency }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.honduras-pay.gateways.edit', $gateway->id) }}"
                       class="text-sm text-blue-600 hover:underline">
                        Editar
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400">
                    No hay pasarelas configuradas. <a href="{{ route('admin.honduras-pay.gateways.create') }}" class="text-blue-600 hover:underline">Crear una</a>
                </p>
            @endforelse
        </div>
    </div>

    <!-- Transacciones Recientes -->
    <div class="mt-7 rounded-lg border bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b p-4 dark:border-gray-800">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                Transacciones Recientes
            </p>
            <a href="{{ route('admin.honduras-pay.transactions.index') }}" class="text-sm text-blue-600 hover:underline">
                Ver Todas
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">ID</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Orden</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Monto</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Estado</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Pasarela</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.honduras-pay.transactions.detail', $transaction->id) }}" class="text-blue-600 hover:underline">
                                    #{{ $transaction->id }}
                                </a>
                            </td>
                            <td class="px-4 py-3">#{{ $transaction->order_id }}</td>
                            <td class="px-4 py-3 font-medium">
                                {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-4 py-3">
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
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $transaction->gateway_code }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                No hay transacciones recientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin::layouts>
