@extends('layouts.app')

@section('title', 'Edit Banner')

@section('content')
<section class="content-header">
  <h1>Edit Banner</h1>
</section>
<section class="content">
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
