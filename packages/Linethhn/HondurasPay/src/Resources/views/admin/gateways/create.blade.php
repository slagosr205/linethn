<x-admin::layouts>
    <x-slot:title>
        Honduras Pay - Nueva Pasarela
    </x-slot:title>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Configurar Nueva Pasarela de Pago
        </p>
    </div>

    <div class="mt-7 rounded-lg border bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <form action="{{ route('admin.honduras-pay.gateways.store') }}" method="POST">
            @csrf

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
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Ej: BAC Credomatic" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Código Único *
                        </label>
                        <input type="text" name="code" value="{{ old('code') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Ej: bac_credomatic" required>
                        <p class="mt-1 text-xs text-gray-500">Identificador único sin espacios</p>
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
                        <input type="password" name="api_key" value="{{ old('api_key') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Ingrese su API Key">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            API Secret
                        </label>
                        <input type="password" name="api_secret" value="{{ old('api_secret') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Ingrese su API Secret">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Merchant ID
                        </label>
                        <input type="text" name="merchant_id" value="{{ old('merchant_id') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="ID del comercio">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Terminal ID
                        </label>
                        <input type="text" name="terminal_id" value="{{ old('terminal_id') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="ID de terminal">
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
                        <input type="url" name="api_url_production" value="{{ old('api_url_production') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="https://api.banco.hn/v1">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            URL Sandbox
                        </label>
                        <input type="url" name="api_url_sandbox" value="{{ old('api_url_sandbox') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="https://sandbox.banco.hn/v1">
                    </div>
                </div>
            </div>

            <!-- Webhook & Configuración -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Webhook y Configuración</h3>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Webhook Secret
                        </label>
                        <input type="password" name="webhook_secret" value="{{ old('webhook_secret') }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               placeholder="Secret para verificar webhooks">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Moneda *
                        </label>
                        <select name="currency" class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white" required>
                            <option value="HNL" {{ old('currency') == 'HNL' ? 'selected' : '' }}>Lempira (HNL)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>Dólar (USD)</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Orden de Visualización
                        </label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                               min="0">
                    </div>
                </div>

                <div class="mt-4 flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sandbox" value="1" {{ old('sandbox', true) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Modo Sandbox (pruebas)</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="active" value="1" {{ old('active') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Activo</span>
                    </label>
                </div>
            </div>

            <!-- URL de Webhook para configurar en el banco -->
            <div class="mb-6 rounded-lg bg-blue-50 p-4 dark:bg-blue-900">
                <h4 class="mb-2 font-semibold text-blue-800 dark:text-blue-200">URL de Webhook para configurar en su banco:</h4>
                <code class="block rounded bg-white px-3 py-2 text-sm text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                    {{ route('honduras-pay.webhook') }}
                </code>
                <p class="mt-2 text-xs text-blue-600 dark:text-blue-300">
                    Configure esta URL en el panel de su banco/pasarela para recibir notificaciones de pago automáticas.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="primary-button">
                    Guardar Pasarela
                </button>

                <a href="{{ route('admin.honduras-pay.gateways.index') }}"
                   class="rounded-lg border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-admin::layouts>
