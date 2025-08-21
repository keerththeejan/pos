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
      <div class="row" style="margin-bottom:15px;">
        <div class="col-md-12">
          <a href="{{ route('banners.create') }}" class="btn btn-success">Add New Banner</a>
        </div>
      </div>

      @if(isset($banners) && $banners->count())
        <div class="row">
          @foreach($banners as $b)
            <div class="col-md-4">
              <div class="panel panel-default" style="border-radius:4px; overflow:hidden;">
                <div class="panel-body" style="padding:0;">
                  <div style="width:100%; height:180px; overflow:hidden; background:#f5f5f5; display:flex; align-items:center; justify-content:center;">
                    <img src="{{ $b->image_url }}" alt="Banner" style="width:100%; height:180px; object-fit:cover; display:block;">
                  </div>
                  <div style="padding:12px;">
                    <h4 style="margin-top:0;">{{ $b->title ?? '—' }}</h4>
                    @if(!empty($b->description))
                      <p class="text-muted" style="min-height:38px;">{{ \Illuminate\Support\Str::limit($b->description, 120) }}</p>
                    @endif
                    <p style="margin:0 0 10px 0;">ID: {{ $b->id }}</p>
                    <p style="margin:0 0 10px 0;">Status: <span class="label label-{{ $b->is_active ? 'success' : 'default' }}">{{ $b->is_active ? 'Active' : 'Inactive' }}</span></p>
                    <div>
                      <a href="{{ route('banners.show', $b->id) }}" class="btn btn-info btn-sm">View</a>
                      <a href="{{ route('banners.edit', $b->id) }}" class="btn btn-primary btn-sm">Edit</a>
                      <form action="{{ route('banners.destroy', $b->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete banner?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="row">
          <div class="col-md-12 text-center">
            {{ $banners->links() }}
          </div>
        </div>
      @else
        <p>No banners yet.</p>
      @endif
    </div>
  </div>
</section>
@endsection

