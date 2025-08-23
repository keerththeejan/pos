@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container" style="margin-top:30px;">
    <div class="card">
        <div class="card-header">Customer Dashboard</div>
        <div class="card-body">
            <p>Welcome, {{ auth('customer')->user()->username ?? auth('customer')->user()->email }}!</p>
            <p>This is your customer dashboard.</p>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
