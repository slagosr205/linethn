<?php

namespace Webkul\LinethPayment\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class LinethPaymentDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('lineth_payments_module')
            ->select('id','name','button_code','sort_order','status');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('ID'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
        ]);
        
        $this->addColumn([
            'index'      => 'name',
            'label'      => 'Nombre del Modulo',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
        ]);
        $this->addColumn([
            'index'      => 'button_code',
            'label'      => 'Codigo del Boton',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

          $this->addColumn([
            'index'      => 'status',
            'label'      => 'Estado',
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => 'Edit',
            'method' => 'GET',
            'icon '  => 'edit-icon',
            'url'    => function ($row) {
                return route('admin.linethpayment.edit', $row->id);
            },
            
        ]);

       
    }
}