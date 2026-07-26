@extends('layouts.admin')
@section('title', 'Variants')

@section('content')
<div class="toolbar">
    <div><h1>Product Variants</h1><p class="muted">Manage SKU-level color, storage, price, and stock.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.variants.create') }}">Add variant</a>
</div>

<table class="table">
    <tr><th>Variant</th><th>Product</th><th>SKU</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr>
    @foreach($variants as $variant)
        <tr>
            <td><strong>{{ $variant->name }}</strong><br><span class="muted">{{ trim(($variant->color ?: '').' '.($variant->storage ?: '')) }}</span></td>
            <td>{{ optional($variant->product)->name }}</td>
            <td>{{ $variant->sku }}</td>
            <td>Rs. {{ number_format($variant->price) }}</td>
            <td>{{ $variant->stock }}</td>
            <td>{{ $variant->is_active ? 'Active' : 'Hidden' }}</td>
            <td>
                <a class="btn btn-small" href="{{ route('admin.variants.edit', $variant) }}">Edit</a>
                <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-small">Delete</button></form>
            </td>
        </tr>
    @endforeach
</table>
<div style="margin-top:16px">{{ $variants->links() }}</div>
@endsection
