@extends('layouts.app')
@section('title', 'Customer Orders')

@section('content')
<div class="tw-p-6">
  <h1 class="tw-text-xl tw-font-semibold tw-mb-4">Customer Orders</h1>

  <div class="tw-overflow-x-auto tw-bg-white tw-shadow tw-rounded">
    <table class="tw-min-w-full tw-text-sm">
      <thead class="tw-bg-gray-100">
        <tr>
          <th class="tw-text-left tw-px-4 tw-py-2">Order No</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Customer</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Payment</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Date</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Items</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Subtotal</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Tax</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Total</th>
          <th class="tw-text-left tw-px-4 tw-py-2">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
          <tr class="tw-border-b">
            <td class="tw-px-4 tw-py-2">{{ $o->order_no }}</td>
            <td class="tw-px-4 tw-py-2">{{ $o->customer_name }}</td>
            <td class="tw-px-4 tw-py-2">{{ $o->payment_method }}</td>
            <td class="tw-px-4 tw-py-2">{{ $o->date }}</td>
            <td class="tw-px-4 tw-py-2">{{ $o->items_count }}</td>
            <td class="tw-px-4 tw-py-2">{{ number_format((float)($o->subtotal ?? 0), 2) }}</td>
            <td class="tw-px-4 tw-py-2">{{ number_format((float)($o->tax ?? 0), 2) }}</td>
            <td class="tw-px-4 tw-py-2 tw-font-semibold">{{ number_format((float)($o->total ?? 0), 2) }}</td>
            <td class="tw-px-4 tw-py-2">
              <a class="tw-text-blue-600 hover:tw-underline" href="{{ route('admin.customer_orders.show', $o->order_no) }}">View</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="tw-px-4 tw-py-6 tw-text-center tw-text-gray-600">No orders found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="tw-mt-4">
    {{ $orders->links() }}
  </div>
</div>
@endsection
