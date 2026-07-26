@extends('layouts.admin')
@section('title', 'Phones')

@section('content')
<div class="toolbar"><div><h1>Phones</h1><p class="muted">Manage smartphone inventory.</p></div><a class="btn btn-primary" href="{{ route('admin.phones.create') }}">Add phone</a></div>
<table class="table">
    <tr><th>Phone</th><th>Brand</th><th>Price</th><th>Featured</th><th></th></tr>
    @foreach($phones as $phone)
        <tr>
            <td>{{ $phone->name }}</td><td>{{ $phone->brand->name }}</td><td>Rs. {{ number_format($phone->price) }}</td><td>{{ $phone->is_featured ? 'Yes' : 'No' }}</td>
            <td><a class="btn btn-small" href="{{ route('admin.phones.edit', $phone) }}">Edit</a><form method="POST" action="{{ route('admin.phones.destroy', $phone) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-small">Delete</button></form></td>
        </tr>
    @endforeach
</table>
<div style="margin-top:16px">{{ $phones->links() }}</div>
@endsection
