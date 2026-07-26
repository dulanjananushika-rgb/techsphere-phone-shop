@extends('layouts.admin')
@section('title', 'Variants and Stock')

@section('content')
<div class="toolbar">
    <div><h1>Variants and stock</h1><p>Manage SKU-level color, storage, price, availability, and publishing.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.variants.create') }}">Add variant</a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Variant</th><th>Product</th><th>SKU</th><th>Price</th><th>Stock</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($variants as $variant)
                <tr>
                    <td><strong>{{ $variant->name }}</strong><br><span class="muted">{{ trim(($variant->color ?: '').' '.($variant->storage ?: '')) }}</span></td>
                    <td>{{ optional($variant->product)->name ?: 'Missing product' }}</td>
                    <td>{{ $variant->sku }}</td>
                    <td>Rs. {{ number_format($variant->price) }}</td>
                    <td>{{ $variant->stock }}</td>
                    <td><span class="status-pill status-{{ $variant->is_active ? 'active' : 'expired' }}">{{ $variant->is_active ? 'Active' : 'Hidden' }}</span></td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-small" href="{{ route('admin.variants.edit', $variant) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}" data-confirm="Delete this SKU? Variants used in orders cannot be deleted.">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-small" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pagination">{{ $variants->links() }}</div>
@endsection
