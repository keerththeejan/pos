<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">@lang('sale.add_payment')</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            {!! Form::open(['url' => action('SellPosController@updatePayment', [$transaction->id]), 'method' => 'post', 'id' => 'add_payment_form' ]) !!}
                <div class="form-group">
                    {!! Form::label('amount', __('sale.amount') . ':*') !!}
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        {!! Form::text('amount', $total_payable, ['class' => 'form-control input_number', 'required', 'id' => 'amount']); !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('method', __('lang_v1.payment_method') . ':*') !!}
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        {!! Form::select('method', $payment_types, 'cash', ['class' => 'form-control', 'required']); !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('note', __('sale.payment_note') . ':') !!}
                    {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('sale.payment_note_placeholder')]); !!}
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit_payment_form">@lang('messages.save')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#add_payment_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            
            $.ajax({
                method: 'POST',
                url: form.attr('action'),
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.msg);
                        $('#add_payment_modal').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        });
    });
</script>