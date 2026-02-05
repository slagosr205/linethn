<x-admin::layouts>
    <x-slot:title>
        Crear Nuevo Pago
    </x-slot:title>

    <x-admin::form
        :action="route('admin.linethpayment.store')"
        method="POST"
    >
        <div class="flex flex-col gap-6 max-w-3xl mx-auto mt-8">

            {{-- Nombre --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    Nombre
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    name="name"
                    rules="required"
                    :value="old('name')"
                    placeholder="Ej. Pago Banco X"
                />

                <x-admin::form.control-group.error control-name="name" />
            </x-admin::form.control-group>

            {{-- Código del botón --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    Código del Botón
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="textarea"
                    name="buttoncode"
                    rules="required"
                    :value="old('buttoncode')"
                    placeholder="Pega aquí el código del SmartButton"
                />

                <x-admin::form.control-group.error control-name="buttoncode" />
            </x-admin::form.control-group>

            {{-- Orden --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    Orden
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="number"
                    name="sort_order"
                    :value="old('sort_order', 0)"
                />

                <x-admin::form.control-group.error control-name="sort_order" />
            </x-admin::form.control-group>

            {{-- Estado --}}
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    Estado
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    name="status"
                    :value="old('status', 1)"
                >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error control-name="status" />
            </x-admin::form.control-group>

        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('admin.linethpayment.index') }}" class="secondary-button">
                Cancelar
            </a>

            <button type="submit" class="primary-button">
                Guardar
            </button>
        </div>
    </x-admin::form>
</x-admin::layouts>
