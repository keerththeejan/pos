@extends('layouts.app')

@section('title', 'View Banner')

@section('content')
<section class="content-header">
  <h1>Banner Details</h1>
</section>
<section class="content">
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <div class="box box-primary">
    <div class="box-body">
      <div class="row">
        <div class="col-md-6">
          <div style="width:100%; max-height:300px; overflow:hidden; background:#f5f5f5; display:flex; align-items:center; justify-content:center;">
            <img src="{{ $banner->image_url }}" alt="Banner" style="width:100%; height:300px; object-fit:cover; display:block;">
          </div>
        </div>
        <div class="col-md-6">
          <h3 style="margin-top:0;">{{ $banner->title ?? '—' }}</h3>
          @if(!empty($banner->description))
            <p class="text-muted">{{ $banner->description }}</p>
          @endif
          <p><strong>Status:</strong> {{ $banner->is_active ? 'Active' : 'Inactive' }}</p>
          <p><strong>ID:</strong> {{ $banner->id }}</p>

          <div style="margin-top:15px;">
            <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('banners.index') }}" class="btn btn-default">Back</a>
            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete banner?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
