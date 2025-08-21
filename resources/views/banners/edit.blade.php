@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
<section class="content-header">
  <h1>Edit Banner</h1>
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
      <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('banners._form', ['banner' => $banner])
      </form>
    </div>
  </div>
</section>
@endsection
