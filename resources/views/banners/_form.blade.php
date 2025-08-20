@csrf
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', optional($banner ?? null)->title) }}" placeholder="Optional title">
    </div>
  </div>
  <div class="col-md-6">
    <div class="checkbox" style="margin-top:25px;">
      <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', optional($banner ?? null)->is_active) ? 'checked' : '' }}> Active
      </label>
    </div>
  </div>
</div>

<div class="row">
  @php
    $previews = [
      'image1' => optional($banner ?? null)->image1_url ?? asset('img/default.png'),
      'image2' => optional($banner ?? null)->image2_url ?? asset('img/default.png'),
      'image3' => optional($banner ?? null)->image3_url ?? asset('img/default.png'),
      'image4' => optional($banner ?? null)->image4_url ?? asset('img/default.png'),
    ];
  @endphp

  @foreach (['image1','image2','image3','image4'] as $img)
    <div class="col-md-3">
      <div class="form-group">
        <label>{{ strtoupper($img) }}</label>
        <input type="file" accept="image/*" class="form-control" name="{{ $img }}" id="{{ $img }}">
      </div>
      <img id="preview_{{ $img }}" src="{{ $previews[$img] }}" class="img-responsive" style="max-height:150px; border:1px solid #ddd; padding:4px;"/>
    </div>
  @endforeach
</div>

<div class="row">
  <div class="col-md-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('banners.index') }}" class="btn btn-default">Cancel</a>
  </div>
</div>

@push('scripts')
<script>
  ['image1','image2','image3','image4'].forEach(function(name){
    var input = document.getElementById(name);
    if(!input) return;
    input.addEventListener('change', function(e){
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(ev){
          var img = document.getElementById('preview_'+name);
          if(img) img.src = ev.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });
</script>
@endpush
