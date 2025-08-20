@extends('layouts.app')

@section('title', 'Banners')

@section('content')
<section class="content-header">
  <h1>Banners</h1>
</section>
<section class="content">
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="box box-primary">
    <div class="box-body">
      @if($banner)
        <div class="row">
          <div class="col-md-12">
            <h4>Title: {{ $banner->title ?? '-' }} | Active: {{ $banner->is_active ? 'Yes' : 'No' }}</h4>
          </div>
        </div>
        <div class="row" style="gap:20px;">
          <div class="col-md-3 text-center">
            <img src="{{ $banner->image1_url }}" class="img-responsive" style="max-height:150px;" alt="Image 1"/>
            <p>Image 1</p>
          </div>
          <div class="col-md-3 text-center">
            <img src="{{ $banner->image2_url }}" class="img-responsive" style="max-height:150px;" alt="Image 2"/>
            <p>Image 2</p>
          </div>
          <div class="col-md-3 text-center">
            <img src="{{ $banner->image3_url }}" class="img-responsive" style="max-height:150px;" alt="Image 3"/>
            <p>Image 3</p>
          </div>
          <div class="col-md-3 text-center">
            <img src="{{ $banner->image4_url }}" class="img-responsive" style="max-height:150px;" alt="Image 4"/>
            <p>Image 4</p>
          </div>
        </div>
        <br>
        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete banner?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      @else
        <p>No banner created yet.</p>
        <a href="{{ route('banners.create') }}" class="btn btn-success">Create Banner</a>
      @endif
    </div>
  </div>
</section>
@endsection
