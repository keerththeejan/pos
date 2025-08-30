@extends('layouts.app')
@section('title', 'Brands')

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">
        @lang('brand.brands')
        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('brand.manage_your_brands')</small>
    </h1>
</section>

<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('brand.all_your_brands')])
        @can('brand.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full btn-modal pull-right"
                        data-href="{{action([\App\Http\Controllers\BrandController::class, 'create'])}}"
                        data-container=".brands_modal">
                        <i class="fa fa-plus"></i> @lang('messages.add')
                    </a>
                </div>
            @endslot
        @endcan

        @can('brand.view')
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="brands_table">
                <thead>
                    <tr>
                        <th>@lang('brand.brands')</th>
                        <th>@lang('brand.note')</th>
                        <th>Image</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
            </table>
        </div>
        @endcan
    @endcomponent

    <div class="modal fade brands_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
</section>

@endsection

@section('javascript')
<script>
$(document).ready(function() {
    $('#brands_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ action([\App\Http\Controllers\BrandController::class, "index"]) }}',
        columns: [
            { data: 'brand', name: 'brand' },
            { data: 'note', name: 'note' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
