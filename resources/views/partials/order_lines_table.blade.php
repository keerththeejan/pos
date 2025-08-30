<div class="tw-overflow-x-auto tw-bg-white tw-shadow tw-rounded">
  <table class="tw-min-w-full tw-text-sm">
    <thead class="tw-bg-gray-100">
      <tr>
        <th class="tw-text-left tw-px-4 tw-py-2">Product</th>
        <th class="tw-text-left tw-px-4 tw-py-2">SKU</th>
        <th class="tw-text-right tw-px-4 tw-py-2">Unit Price</th>
        <th class="tw-text-right tw-px-4 tw-py-2">Qty</th>
        <th class="tw-text-right tw-px-4 tw-py-2">Line Total</th>
      </tr>
    </thead>
    <tbody>
      @php
        $__items = $items ?? [];
      @endphp
      @forelse($__items as $r)
        @php
          $pName = $r->product_name ?? ($r['product_name'] ?? '');
          $sku = $r->sku ?? ($r['sku'] ?? '');
          $unit = (float) ($r->unit_price ?? ($r['unit_price'] ?? 0));
          $qty = $r->quantity ?? ($r['quantity'] ?? 0);
          $lt = (float) ($r->line_total ?? ($r['line_total'] ?? 0));
        @endphp
        <tr class="tw-border-b">
          <td class="tw-px-4 tw-py-2">{{ $pName }}</td>
          <td class="tw-px-4 tw-py-2">{{ $sku }}</td>
          <td class="tw-text-right tw-px-4 tw-py-2">{{ number_format($unit, 2) }}</td>
          <td class="tw-text-right tw-px-4 tw-py-2">{{ $qty }}</td>
          <td class="tw-text-right tw-px-4 tw-py-2">{{ number_format($lt, 2) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="tw-px-4 tw-py-6 tw-text-center tw-text-gray-600">No items</td>
        </tr>
      @endforelse
      <tr>
        <td colspan="4" class="tw-text-right tw-px-4 tw-py-2">Subtotal</td>
        <td class="tw-text-right tw-px-4 tw-py-2">{{ number_format((float)($subtotal ?? 0), 2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="tw-text-right tw-px-4 tw-py-2">Tax ({{ number_format((float)($tax_rate ?? 0), 0) }}%)</td>
        <td class="tw-text-right tw-px-4 tw-py-2">{{ number_format((float)($tax ?? 0), 2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="tw-text-right tw-px-4 tw-py-2">Shipping</td>
        <td class="tw-text-right tw-px-4 tw-py-2">{{ number_format((float)($shipping ?? 0), 2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="tw-text-right tw-px-4 tw-py-2 tw-font-semibold">Total</td>
        <td class="tw-text-right tw-px-4 tw-py-2 tw-font-semibold">{{ number_format((float)($total ?? 0), 2) }}</td>
      </tr>
    </tbody>
  </table>
</div>
