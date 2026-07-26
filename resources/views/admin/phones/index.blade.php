@extends('layouts.admin')
@section('title', 'Phones')

@section('content')
<div class="toolbar">
    <div><h1>Phones</h1><p>Manage product publishing, pricing, and SKU-level stock.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.phones.create') }}">Add phone</a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Phone</th><th>Brand</th><th>Base price</th><th>Available</th><th>Visibility</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($phones as $phone)
                <tr>
                    <td><strong>{{ $phone->name }}</strong><br><span class="muted">{{ $phone->variants->count() }} {{ Str::plural('variant', $phone->variants->count()) }}</span></td>
                    <td>{{ $phone->brand->name }}</td>
                    <td>Rs. {{ number_format($phone->price) }}</td>
                    <td>{{ $phone->availableStock() }}</td>
                    <td><span class="status-pill status-{{ $phone->is_active ? 'active' : 'expired' }}">{{ $phone->is_active ? 'Active' : 'Hidden' }}</span></td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-small" href="{{ route('admin.phones.edit', $phone) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.phones.destroy', $phone) }}" data-confirm="Delete this phone permanently? Products used in orders cannot be deleted.">
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
<div class="pagination">{{ $phones->links() }}</div>
@endsection
