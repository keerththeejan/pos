@extends('layouts.app')

@section('title', 'Create Banner')

@section('content')
<section class="content-header">
  <h1>Create Banner</h1>
</section>
<section class="content">
  <div class="box box-primary">
    <div class="box-body">
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @include('banners._form', ['banner' => $banner ?? null])
      </form>
    </div>
  </div>
</section>
@endsection
