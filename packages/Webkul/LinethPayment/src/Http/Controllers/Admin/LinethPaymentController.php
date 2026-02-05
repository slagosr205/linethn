<?php

namespace Webkul\LinethPayment\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\LinethPayment\Repositories\LinethPaymentRepository;
//use Webkul\LinethPayment\dataGrids\LinethPaymentDataGrid;
use Webkul\LinethPayment\DataGrids\LinethPaymentDataGrid as AdminLinethPaymentDataGrid;

class LinethPaymentController extends Controller
{

    public function __construct(
        protected LinethPaymentRepository $linethPaymentrepository
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(AdminLinethPaymentDataGrid::class)->process();
        }

        return view('linethpayment::admin.index');
    }

    public function datagrid()
    {
        return app(AdminLinethPaymentDataGrid::class)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('linethpayment::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        // Add your store logic here
        
        return new JsonResponse([
            'message' => 'Resource created successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $resource = $this->linethPaymentrepository->findOrFail($id);

        return view('linethpayment::admin.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        // Add your update logic here
        
        return new JsonResponse([
            'message' => 'Resource updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        // Add your destroy logic here
        
        return new JsonResponse([
            'message' => 'Resource deleted successfully.',
        ]);
    }
}
