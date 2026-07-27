@extends('layouts.business')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl">
    <div class="glass-card p-6">
        <form method="POST" action="{{ route('business.products.store') }}" enctype="multipart/form-data">
            @include('business.products._form', ['product' => null])
            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">Simpan Produk</button>
                <a href="{{ route('business.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
