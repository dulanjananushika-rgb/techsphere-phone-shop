@extends('layouts.admin')
@section('title', 'Brands')

@section('content')
<div class="toolbar">
    <div><h1>Brands</h1><p>Manage manufacturers used by the phone catalog.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.brands.create') }}">Add brand</a>
</div>
<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Name</th><th>Phones</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($brands as $brand)
                <tr>
                    <td><strong>{{ $brand->name }}</strong><br><span class="muted">{{ Str::limit($brand->description, 80) }}</span></td>
                    <td>{{ $brand->phones_count }}</td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-small" href="{{ route('admin.brands.edit', $brand) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" data-confirm="Delete this brand? A brand with phones cannot be deleted.">
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
<div class="pagination">{{ $brands->links() }}</div>
@endsection
