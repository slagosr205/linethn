<x-admin::layouts>

    <!-- Title of the page -->
    <x-slot:title>
        Modulo de Pagos Lineth
    </x-slot>
    <div class="flex justify-end mb-4">
        <a 
            href="{{ route('admin.linethpayment.create') }}" 
            class="primary-button"  
        >
            Crear Nuevo
        </a>
    </div>
    <!-- Page Content -->
    <x-admin::datagrid :src="route('admin.linethpayment.index')" />

</x-admin::layouts>