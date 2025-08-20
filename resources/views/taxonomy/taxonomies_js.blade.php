<script type="text/javascript">
    $(document).ready(function() {

        function getTaxonomiesIndexPage() {
            var data = {
                category_type: $('#category_type').val()
            };
            $.ajax({
                method: "GET",
                dataType: "html",
                url: '/taxonomies-ajax-index-page',
                data: data,
                async: false,
                success: function(result) {
                    $('.taxonomy_body').html(result);
                }
            });
        }

        function initializeTaxonomyDataTable() {
            //Category table
            if ($('#category_table').length) {
                var category_type = $('#category_type').val();
                category_table = $('#category_table').DataTable({
                    processing: true,
                    serverSide: true,
                    fixedHeader: false,
                    ajax: {
                        url: '/taxonomies?type=' + category_type,
                        dataSrc: function(json){
                            try { if (json && json.data && json.data.length) { console.debug('image sample:', json.data[0].image); } } catch(e) {}
                            return json.data || [];
                        }
                    },
                    columns: [
                        { data: 'name', name: 'name', orderable: false, searchable: false },
                        @if($cat_code_enabled)
                        { data: 'short_code', name: 'short_code', orderable: false, searchable: false },
                        @endif
                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                            searchable: false,
                            width: '56px',
                            // The fix is to not create a new <img> tag.
                            // The data from the server is already the <img> tag.
                            render: function(data, type, row, meta) {
                                if (type === 'display') { return data; }
                                // For sort/filter, strip tags to plain text
                                try { return $('<div>').html(data).text(); } catch(e) { return data; }
                            }
                        },
                        { data: 'description', name: 'description', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    columnDefs: [
                        { targets: 2, className: 'dt-body-center' }
                    ]
                });
            }
        }

        @if(empty(request()->get('type')))
        getTaxonomiesIndexPage();
        @endif

        initializeTaxonomyDataTable();

        // delegated handler to preview image on file select inside modal (create/edit)
        $(document).on('change', '.category_modal input[type="file"][name="image"]', function(e) {
            var input = this;
            var $modal = $(this).closest('.category_modal');
            var $preview = $modal.find('.js-image-preview');
            if (!$preview.length) {
                // fallback: try to insert after file input
                $preview = $('<div class="js-image-preview tw-mt-2"></div>').insertAfter($(this));
            }
            $preview.empty();
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var url = URL.createObjectURL(file);
                var $img = $('<img>', {
                    src: url,
                    css: {
                        maxHeight: '80px',
                        borderRadius: '6px'
                    },
                    alt: 'Preview'
                });
                $preview.append($img);
            }
        });
    });
    $(document).on('submit', 'form#category_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = new FormData(this);

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success === true) {
                    $('div.category_modal').modal('hide');
                    toastr.success(result.msg);
                    if (typeof category_table !== 'undefined') {
                        category_table.ajax.reload();
                    }

                    var evt = new CustomEvent("categoryAdded", {
                        detail: result.data
                    });
                    window.dispatchEvent(evt);

                    //event can be listened as
                    //window.addEventListener("categoryAdded", function(evt) {}
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.edit_category_button', function() {
        $('div.category_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#category_edit_form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(this);

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success === true) {
                            $('div.category_modal').modal('hide');
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_category_button', function() {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
</script>