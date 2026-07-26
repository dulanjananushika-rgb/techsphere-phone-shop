@extends('layouts.admin')
@section('title', 'Accessories')
@section('content')
<div class="toolbar"><div><h1>Accessories</h1><p class="muted">Manage add-on products.</p></div><a class="btn btn-primary" href="{{ route('admin.accessories.create') }}">Add accessory</a></div>
<table class="table"><tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr>@foreach($accessories as $accessory)<tr><td>{{ $accessory->name }}</td><td>{{ $accessory->category }}</td><td>Rs. {{ number_format($accessory->price) }}</td><td>{{ $accessory->stock }}</td><td><a class="btn btn-small" href="{{ route('admin.accessories.edit', $accessory) }}">Edit</a><form method="POST" action="{{ route('admin.accessories.destroy', $accessory) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-small">Delete</button></form></td></tr>@endforeach</table>
<div style="margin-top:16px">{{ $accessories->links() }}</div>
@endsection
