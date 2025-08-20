@extends('layouts.app')

@section('title', 'Create Banner')

@section('content')
<section class="content-header">
  <h1>Create Banner</h1>
</section>
<section class="content">
  <div class="box box-primary">
    <div class="box-body">
      <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @include('banners._form', ['banner' => $banner ?? null])
      </form>
    </div>
  </div>
</section>
@endsection
