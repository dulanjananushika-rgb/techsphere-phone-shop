@extends('layouts.admin')
@section('title', 'Brands')
@section('content')
<div class="toolbar"><div><h1>Brands</h1><p class="muted">Manage manufacturers.</p></div><a class="btn btn-primary" href="{{ route('admin.brands.create') }}">Add brand</a></div>
<table class="table"><tr><th>Name</th><th>Phones</th><th></th></tr>@foreach($brands as $brand)<tr><td>{{ $brand->name }}</td><td>{{ $brand->phones_count }}</td><td><a class="btn btn-small" href="{{ route('admin.brands.edit', $brand) }}">Edit</a><form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-small">Delete</button></form></td></tr>@endforeach</table>
<div style="margin-top:16px">{{ $brands->links() }}</div>
@endsection
