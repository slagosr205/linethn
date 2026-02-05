<?php

namespace Linethhn\HondurasPay\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Linethhn\HondurasPay\Repositories\HondurasPayGatewayRepository;
use Linethhn\HondurasPay\Repositories\HondurasPayTransactionRepository;
use Linethhn\HondurasPay\Services\PaymentGatewayService;

class HondurasPayController extends Controller
{
    public function __construct(
        protected HondurasPayGatewayRepository $gatewayRepository,
        protected HondurasPayTransactionRepository $transactionRepository,
        protected PaymentGatewayService $gatewayService
    ) {
    }

    /**
     * Dashboard / index page.
     */
    public function index()
    {
        $statistics = $this->transactionRepository->getStatistics('month');
        $recentTransactions = $this->transactionRepository->model
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $gateways = $this->gatewayRepository->all();

        return view('honduras-pay::admin.index', compact(
            'statistics',
            'recentTransactions',
            'gateways'
        ));
    }

    /**
     * List all gateways.
     */
    public function gateways()
    {
        $gateways = $this->gatewayRepository->all();

        return view('honduras-pay::admin.gateways.index', compact('gateways'));
    }

    /**
     * Show create gateway form.
     */
    public function createGateway()
    {
        return view('honduras-pay::admin.gateways.create');
    }

    /**
     * Store a new gateway.
     */
    public function storeGateway(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'code'               => 'required|string|max:50|unique:honduras_pay_gateways,code',
            'api_key'            => 'nullable|string|max:500',
            'api_secret'         => 'nullable|string|max:500',
            'merchant_id'        => 'nullable|string|max:100',
            'terminal_id'        => 'nullable|string|max:100',
            'api_url_production' => 'nullable|url|max:500',
            'api_url_sandbox'    => 'nullable|url|max:500',
            'webhook_secret'     => 'nullable|string|max:500',
            'currency'           => 'required|string|in:HNL,USD',
            'sandbox'            => 'boolean',
            'active'             => 'boolean',
            'sort_order'         => 'integer|min:0',
        ]);

        $validated['sandbox'] = $request->boolean('sandbox');
        $validated['active'] = $request->boolean('active');

        $this->gatewayRepository->create($validated);

        session()->flash('success', 'Pasarela de pago creada exitosamente.');

        return redirect()->route('admin.honduras-pay.gateways.index');
    }

    /**
     * Show edit gateway form.
     */
    public function editGateway(int $id)
    {
        $gateway = $this->gatewayRepository->find($id);

        if (! $gateway) {
            session()->flash('error', 'Pasarela no encontrada.');
            return redirect()->route('admin.honduras-pay.gateways.index');
        }

        return view('honduras-pay::admin.gateways.edit', compact('gateway'));
    }

    /**
     * Update a gateway.
     */
    public function updateGateway(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'api_key'            => 'nullable|string|max:500',
            'api_secret'         => 'nullable|string|max:500',
            'merchant_id'        => 'nullable|string|max:100',
            'terminal_id'        => 'nullable|string|max:100',
            'api_url_production' => 'nullable|url|max:500',
            'api_url_sandbox'    => 'nullable|url|max:500',
            'webhook_secret'     => 'nullable|string|max:500',
            'currency'           => 'required|string|in:HNL,USD',
            'sandbox'            => 'boolean',
            'active'             => 'boolean',
            'sort_order'         => 'integer|min:0',
        ]);

        $validated['sandbox'] = $request->boolean('sandbox');
        $validated['active'] = $request->boolean('active');

        $this->gatewayRepository->update($validated, $id);

        session()->flash('success', 'Pasarela actualizada exitosamente.');

        return redirect()->route('admin.honduras-pay.gateways.index');
    }

    /**
     * Delete a gateway.
     */
    public function deleteGateway(int $id)
    {
        $this->gatewayRepository->delete($id);

        session()->flash('success', 'Pasarela eliminada exitosamente.');

        return redirect()->route('admin.honduras-pay.gateways.index');
    }

    /**
     * List all transactions.
     */
    public function transactions(Request $request)
    {
        $query = $this->transactionRepository->model->newQuery();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway')) {
            $query->where('gateway_code', $request->gateway);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('honduras-pay::admin.transactions.index', compact('transactions'));
    }

    /**
     * View transaction details.
     */
    public function transactionDetail(int $id)
    {
        $transaction = $this->transactionRepository->find($id);

        if (! $transaction) {
            session()->flash('error', 'Transacción no encontrada.');
            return redirect()->route('admin.honduras-pay.transactions.index');
        }

        return view('honduras-pay::admin.transactions.detail', compact('transaction'));
    }

    /**
     * Process refund for a transaction.
     */
    public function refund(Request $request, int $id)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        $result = $this->gatewayService->processRefund($id, $request->amount);

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }

        return redirect()->route('admin.honduras-pay.transactions.detail', $id);
    }

    /**
     * Test gateway connection.
     */
    public function testConnection(int $id)
    {
        $gateway = $this->gatewayRepository->find($id);

        if (! $gateway) {
            return response()->json(['success' => false, 'message' => 'Pasarela no encontrada.']);
        }

        try {
            $apiUrl = $gateway->getActiveApiUrl();

            if (empty($apiUrl)) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL de API no configurada.',
                ]);
            }

            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders([
                    'X-Api-Key' => $gateway->api_key,
                ])
                ->get($apiUrl . '/health');

            return response()->json([
                'success' => $response->successful(),
                'message' => $response->successful()
                    ? 'Conexión exitosa con la pasarela.'
                    : 'Error de conexión: HTTP ' . $response->status(),
                'status_code' => $response->status(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage(),
            ]);
        }
    }
}
