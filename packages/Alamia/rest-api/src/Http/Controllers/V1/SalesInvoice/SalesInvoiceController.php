<?php

namespace Alamia\RestApi\Http\Controllers\V1\SalesInvoice;

use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Repositories\SalesInvoiceRepository;
use Alamia\RestApi\Http\Resources\V1\SalesInvoice\SalesInvoiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SalesInvoiceController extends Controller
{
    public function __construct(
        protected SalesInvoiceRepository $salesInvoiceRepository
    ) {}

    public function index(): JsonResource
    {
        $query = $this->salesInvoiceRepository->query();

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        // Use excludeKeys from parent Controller
        foreach (request()->except($this->excludeKeys) as $input => $value) {
            $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $invoices = $query->paginate(request()->input('limit') ?? 10);

        return SalesInvoiceResource::collection($invoices);
    }

    public function show(int $id): JsonResource
    {
        $invoice = $this->salesInvoiceRepository->findOrFail($id);
        return new SalesInvoiceResource($invoice);
    }

    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'invoice_number' => 'required|unique:sales_invoices,invoice_number',
            'total_amount'   => 'required|numeric',
            'amount_received'=> 'required|numeric',
            'status'         => 'required|in:Pending,Partial,Released',
            'category'       => 'nullable|string',
            'issued_at'      => 'nullable|date',
            'customer_name'  => 'nullable|string',
        ]);

        $data = $request->all();
        if (!$request->has('user_id')) {
            $data['user_id'] = auth()->id();
        }

        $invoice = $this->salesInvoiceRepository->create($data);

        return new SalesInvoiceResource($invoice);
    }

    public function update(Request $request, int $id): JsonResource
    {
        $this->validate($request, [
            'invoice_number' => 'unique:sales_invoices,invoice_number,' . $id,
            'total_amount'   => 'numeric',
            'status'         => 'in:Pending,Partial,Released',
        ]);

        $invoice = $this->salesInvoiceRepository->update($request->all(), $id);
        
        return new SalesInvoiceResource($invoice);
    }
    
    public function destroy(int $id)
    {
        try {
            $this->salesInvoiceRepository->delete($id);
            return response()->json(['message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
