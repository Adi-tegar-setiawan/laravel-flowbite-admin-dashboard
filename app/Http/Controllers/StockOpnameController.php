<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StockOpnameService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\StockOpnameRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    public function __construct(
        protected StockOpnameRepositoryInterface $opnameRepository,
        protected ProductRepositoryInterface $productRepository,
        protected StockOpnameService $opnameService
    ) {
    }

    /**
     * Menampilkan daftar stock opname.
     */
    public function index(Request $request)
    {
        $filters = [
            'search'     => $request->get('search'),
            'start_date' => $request->get('start_date'),
            'end_date'   => $request->get('end_date'),
        ];

        // UBAH $this->stockOpnameService MENJADI $this->opnameService
        $opnames = $this->opnameService->getPaginatedOpnames($filters, 10);

        return view('stock-opnames.index', compact('opnames'));
    }

    /**
     * Menampilkan form tambah stock opname.
     */
    public function create()
    {
        return view('stock-opnames.create', [
            'products' => $this->productRepository->all(),
        ]);
    }

    /**
     * Menyimpan stock opname.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'physical_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $validated['user_id'] = Auth::id();

        $this->opnameService->create($validated);

        return redirect()
            ->route('stock-opnames.index')
            ->with(
                'success',
                'Stock opname berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit stock opname.
     */
    public function edit(int $id)
    {
        return view('stock-opnames.edit', [
            'opname' => $this->opnameRepository->find($id),
            'products' => $this->productRepository->all(),
        ]);
    }

    /**
     * Mengupdate stock opname.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'physical_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $this->opnameService->update(
            $id,
            $validated
        );

        return redirect()
            ->route('stock-opnames.index')
            ->with(
                'success',
                'Stock opname berhasil diperbarui.'
            );
    }

    /**
     * Menghapus stock opname.
     */
    public function destroy(int $id)
    {
        $this->opnameService->delete($id);

        return redirect()
            ->route('stock-opnames.index')
            ->with(
                'success',
                'Stock opname berhasil dihapus.'
            );
    }
}