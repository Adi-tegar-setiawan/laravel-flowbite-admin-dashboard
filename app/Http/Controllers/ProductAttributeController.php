<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $attributeRepository
    ) {}

    public function store(Request $request, int $productId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $validated['product_id'] = $productId;

        $this->attributeRepository->create($validated);

        return redirect()
            ->back()
            ->with('success', 'Atribut berhasil ditambahkan.');
    }

    public function edit(int $productId, int $attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);

        if ($attribute->product_id !== $productId) {
            abort(404);
        }

        return view('products.attributes.edit', compact(
            'productId',
            'attribute'
        ));
    }

    public function update(Request $request, int $productId, int $attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);

        if ($attribute->product_id !== $productId) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $this->attributeRepository->update(
            $attributeId,
            $validated
        );

        return redirect()
            ->route('products.show', $productId)
            ->with('success', 'Atribut berhasil diperbarui.');
    }

    public function destroy(int $productId, int $attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);

        if ($attribute->product_id !== $productId) {
            abort(404);
        }

        $this->attributeRepository->delete($attributeId);

        return redirect()
            ->back()
            ->with('success', 'Atribut berhasil dihapus.');
    }
}
