@extends('layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="tw-p-6">
  <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
    <h1 class="tw-text-xl tw-font-semibold">Order {{ $order['order_no'] }}</h1>
    <div class="tw-flex tw-gap-4">
      <a href="{{ route('admin.customer_orders.index') }}" class="tw-text-blue-600 hover:tw-underline">Back to Orders</a>
      @if(!empty($order['order_no']))
        <a href="{{ url('/order-details?order_no='.$order['order_no']) }}" class="tw-text-blue-600 hover:tw-underline" target="_blank" rel="noopener">Open in Customer</a>
      @endif
    </div>
  </div>

  @include('partials.order_lines_table', [
    'items' => $rows,
    'subtotal' => $order['subtotal'] ?? 0,
    'tax_rate' => $order['tax_rate'] ?? 0,
    'tax' => $order['tax'] ?? 0,
    'shipping' => $order['shipping'] ?? 0,
    'total' => $order['total'] ?? 0,
  ])
</div>
@endsection
