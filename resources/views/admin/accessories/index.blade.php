@extends('layouts.admin')
@section('title', 'Accessories')

@section('content')
<div class="toolbar">
    <div><h1>Accessories</h1><p>Manage accessory catalog, stock, and publishing.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.accessories.create') }}">Add accessory</a>
</div>
<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Available</th><th>Visibility</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($accessories as $accessory)
                <tr>
                    <td><strong>{{ $accessory->name }}</strong><br><span class="muted">{{ $accessory->variants->count() }} {{ Str::plural('variant', $accessory->variants->count()) }}</span></td>
                    <td>{{ $accessory->category }}</td>
                    <td>Rs. {{ number_format($accessory->price) }}</td>
                    <td>{{ $accessory->availableStock() }}</td>
                    <td><span class="status-pill status-{{ $accessory->is_active ? 'active' : 'expired' }}">{{ $accessory->is_active ? 'Active' : 'Hidden' }}</span></td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-small" href="{{ route('admin.accessories.edit', $accessory) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.accessories.destroy', $accessory) }}" data-confirm="Delete this accessory permanently? Products used in orders cannot be deleted.">
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
<div class="pagination">{{ $accessories->links() }}</div>
@endsection
