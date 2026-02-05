<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Pasarelas de Pago
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Pasarelas de Pago Configuradas
        </p>

        <div class="flex items-center gap-x-2.5">
            <a href="{{ route('admin.honduras-pay.gateways.create') }}" class="primary-button">
                Agregar Pasarela
            </a>
        </div>
    </div>

    <div class="mt-7 overflow-x-auto rounded-lg border bg-white dark:border-gray-800 dark:bg-gray-900">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Nombre</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Código</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Moneda</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Modo</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gateways as $gateway)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ $gateway->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $gateway->code }}</td>
                        <td class="px-4 py-3">{{ $gateway->currency }}</td>
                        <td class="px-4 py-3">
                            @if($gateway->sandbox)
                                <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Sandbox</span>
                            @else
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Producción</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($gateway->active)
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Activo</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.honduras-pay.gateways.edit', $gateway->id) }}"
                                   class="cursor-pointer text-blue-600 hover:underline">
                                    Editar
                                </a>

                                <form action="{{ route('admin.honduras-pay.gateways.delete', $gateway->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Está seguro que desea eliminar esta pasarela?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cursor-pointer text-red-600 hover:underline">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            No hay pasarelas configuradas. <a href="{{ route('admin.honduras-pay.gateways.create') }}" class="text-blue-600 hover:underline">Crear una nueva</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin::layouts>
