@extends('layouts.app')
@section('title', 'My Invoices')

@section('content')
<div class="tw-p-6">
  <h1 class="tw-text-xl tw-font-semibold tw-mb-4">My Invoices</h1>

  @if(empty($invoices))
    <p class="tw-text-gray-600">No invoices yet. Place an order to see your invoices here.</p>
  @else
    <div class="tw-overflow-x-auto tw-bg-white tw-shadow tw-rounded">
      <table class="tw-min-w-full tw-text-sm">
        <thead class="tw-bg-gray-100">
          <tr>
            <th class="tw-text-left tw-px-4 tw-py-2">Invoice No</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Order No</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Date</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Items</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Subtotal</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Tax</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Shipping</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Total</th>
            <th class="tw-text-left tw-px-4 tw-py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse(($invoices ?? []) as $inv)
            <tr class="tw-border-b">
              <td class="tw-px-4 tw-py-2">{{ $inv['invoice_no'] ?? '' }}</td>
              <td class="tw-px-4 tw-py-2">{{ $inv['order_no'] ?? '' }}</td>
              <td class="tw-px-4 tw-py-2">{{ $inv['date'] ?? '' }}</td>
              <td class="tw-px-4 tw-py-2">{{ $inv['items_count'] ?? 0 }}</td>
              <td class="tw-px-4 tw-py-2">{{ number_format((float)($inv['subtotal'] ?? 0), 2) }}</td>
              <td class="tw-px-4 tw-py-2">{{ number_format((float)($inv['tax'] ?? 0), 2) }}</td>
              <td class="tw-px-4 tw-py-2">{{ number_format((float)($inv['shipping'] ?? 0), 2) }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-semibold">{{ number_format((float)($inv['total'] ?? 0), 2) }}</td>
              <td class="tw-px-4 tw-py-2">
                <a class="tw-text-blue-600 hover:tw-underline" href="{{ url('/order-details?order_no=' . ($inv['order_no'] ?? '')) }}">View Bill</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="tw-px-4 tw-py-6 tw-text-center tw-text-gray-600">No invoices found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
