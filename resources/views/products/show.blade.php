@extends('layouts.app')

@section('content')

    <h1>Detail Produk</h1>

    <p>Nama: {{ $product->name }}</p>

    <p>SKU: {{ $product->sku }}</p>

    <p>Kategori: {{ $product->category->name }}</p>

    <p>Supplier: {{ $product->supplier->name }}</p>

@endsection