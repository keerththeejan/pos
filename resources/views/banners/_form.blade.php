@csrf
@php
  $uploadDir = \App\Banner::uploadDir();
  $current = !empty(optional($banner ?? null)->image) ? asset($uploadDir . '/' . $banner->image) : null;
@endphp

<div class="row">
  <div class="col-md-12">
    <h3>Add New Banner</h3>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div id="banner_preview" style="width:100%; max-height:240px; overflow:hidden; border:1px solid #ddd; border-radius:4px; background:#f5f5f5; display:flex; align-items:center; justify-content:center;">
      @if($current)
        <img id="banner_img" src="{{ $current }}" alt="Banner Preview" style="width:100%; height:240px; object-fit:cover; display:block;">
      @else
        <img id="banner_img" src="" alt="Banner Preview" style="width:100%; height:240px; object-fit:cover; display:none;">
        <span class="text-muted" style="padding:8px;">No banner image selected</span>
      @endif
    </div>
  </div>
</div>
<br>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', optional($banner ?? null)->title) }}" placeholder="Title">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="4" class="form-control" placeholder="Description (optional)">{{ old('description', optional($banner ?? null)->description) }}</textarea>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label>Banner Image</label>
      <input type="file" accept="image/*" class="form-control" name="image" id="image">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Status</label>
      <select name="is_active" class="form-control">
        <option value="1" {{ old('is_active', optional($banner ?? null)->is_active ?? true) ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('is_active', optional($banner ?? null)->is_active ?? true) ? '' : 'selected' }}>Inactive</option>
      </select>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('banners.index') }}" class="btn btn-default">Cancel</a>
  </div>
  </div>

@push('scripts')
<script>
  (function(){
    var img = document.getElementById('banner_img');
    var wrap = document.getElementById('banner_preview');
    var input = document.getElementById('image');
    function show(){ if(img){ img.style.display='block'; } var s=wrap?wrap.querySelector('span.text-muted'):null; if(s){ s.style.display='none'; } }
    if(input){
      input.addEventListener('change', function(){
        if(this.files && this.files[0]){
          var reader = new FileReader();
          reader.onload = function(e){ img.src = e.target.result; show(); };
          reader.readAsDataURL(this.files[0]);
        }
      });
    }
  })();
</script>
@endpush
