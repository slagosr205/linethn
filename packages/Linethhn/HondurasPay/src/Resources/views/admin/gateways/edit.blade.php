<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Editar {{ $gateway->name }}
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Editar Pasarela: {{ $gateway->name }}
        </p>
    </div>

    <div class="mt-7 rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.honduras-pay.gateways.update', $gateway->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-300">
                    <ul class="list-inside list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información Básica -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Información Básica</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nombre del Banco/Pasarela *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $gateway->name) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Código Único
                        </label>
                        <input type="text" value="{{ $gateway->code }}"
                               class="w-full rounded-lg border bg-gray-100 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-700 dark:text-gray-400"
                               disabled>
                        <p class="mt-1 text-xs text-gray-500">El código no puede ser modificado</p>
                    </div>
                </div>
            </div>

            <!-- Credenciales de API -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Credenciales de API</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            API Key
                        </label>
                        <input type="password" name="api_key" value="{{ old('api_key', $gateway->api_key) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Actualizar API Key">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            API Secret
                        </label>
                        <input type="password" name="api_secret"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Dejar vacío para mantener el actual">
                        <p class="mt-1 text-xs text-gray-500">Dejar vacío si no desea cambiar el secret actual</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Merchant ID
                        </label>
                        <input type="text" name="merchant_id" value="{{ old('merchant_id', $gateway->merchant_id) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Terminal ID
                        </label>
                        <input type="text" name="terminal_id" value="{{ old('terminal_id', $gateway->terminal_id) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- URLs de API -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">URLs de la API</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            URL Producción
                        </label>
                        <input type="url" name="api_url_production" value="{{ old('api_url_production', $gateway->api_url_production) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            URL Sandbox
                        </label>
                        <input type="url" name="api_url_sandbox" value="{{ old('api_url_sandbox', $gateway->api_url_sandbox) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Configuración</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Webhook Secret
                        </label>
                        <input type="password" name="webhook_secret"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Dejar vacío para mantener el actual">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Moneda *
                        </label>
                        <select name="currency" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white" required>
                            <option value="HNL" {{ old('currency', $gateway->currency) == 'HNL' ? 'selected' : '' }}>Lempira (HNL)</option>
                            <option value="USD" {{ old('currency', $gateway->currency) == 'USD' ? 'selected' : '' }}>Dólar (USD)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Orden de Visualización
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $gateway->sort_order) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               min="0">
                    </div>
                </div>

                <div class="mt-4 flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sandbox" value="1" {{ old('sandbox', $gateway->sandbox) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Modo Sandbox (pruebas)</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="active" value="1" {{ old('active', $gateway->active) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Activo</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="primary-button">
                    Actualizar Pasarela
                </button>

                <a href="{{ route('admin.honduras-pay.gateways.index') }}"
                   class="rounded-lg border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </a>

                <button type="button" id="test-connection-btn"
                        class="rounded-lg border border-blue-600 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900"
                        onclick="testConnection()">
                    Probar Conexión
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function testConnection() {
                const btn = document.getElementById('test-connection-btn');
                btn.disabled = true;
                btn.textContent = 'Probando...';

                fetch('{{ route("admin.honduras-pay.gateways.test", $gateway->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    btn.disabled = false;
                    btn.textContent = 'Probar Conexión';
                })
                .catch(error => {
                    alert('Error al probar la conexión');
                    btn.disabled = false;
                    btn.textContent = 'Probar Conexión';
                });
            }
        </script>
    @endpush
</x-admin::layouts>
