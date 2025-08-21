@php
  $banner = \App\Banner::first();
@endphp
@if($banner && $banner->is_active)
  <div class="tw-w-full" style="width:100%; max-height:340px; overflow:hidden; border-radius:4px;">
    <img src="{{ $banner->image_url }}" alt="Banner" style="width:100%; height:340px; object-fit:cover; display:block;">
  </div>
@endif
