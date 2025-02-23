@extends('backEnd.layouts.master')
@section('title', 'Category Manage')
@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill">Create</a>
                    </div>
                    <h4 class="page-title">Category Manage</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="category-list">
                                @foreach ($data as $key => $value)
                                    <tr data-id="{{ $value->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($value->front_view == 1)
                                                <a target="_blank" href="{{ route('category', $value->slug) }}"
                                                    class="btn btn-dark">{{ $value->name }}</a>
                                            @else
                                                <a target="_blank"
                                                    href="{{ route('category', $value->slug) }}">{{ $value->name }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $value->products->count() ?? 0 }}</td>
                                        <td><img src="{{ asset($value->image) }}" class="backend-image" alt="">
                                        </td>
                                        <td>
                                            @if ($value->status == 1)
                                                <span class="badge bg-soft-success text-success">Active</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Inactive</span>
                                            @endif
                                            <br>
                                            @if ($value->top_category)
                                            <span class="badge bg-soft-success text-success">Top Category</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button-list">
                                                @if ($value->status == 1)
                                                    <form method="post" action="{{ route('categories.inactive') }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                                        <button type="button"
                                                            class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm"><i
                                                                class="fe-thumbs-down"></i></button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{ route('categories.active') }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                                        <button type="button"
                                                            class="btn btn-xs  btn-success waves-effect waves-light change-confirm"><i
                                                                class="fe-thumbs-up"></i></button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('categories.edit', $value->id) }}"
                                                    class="btn btn-xs btn-primary waves-effect waves-light"><i
                                                        class="fe-edit-1"></i></a>

                                                <form method="post" action="{{ route('categories.destroy') }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                                    <button type="submit"
                                                        class="btn btn-xs btn-danger waves-effect waves-light delete-confirm"><i
                                                            class="mdi mdi-close"></i></button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection


@section('script')
    <!-- third party js -->
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <!-- third party js ends -->
    <script>
        // Initialize Sortable
        var sortable = new Sortable(document.getElementById('category-list'), {
            animation: 150,
            onEnd: function(evt) {
                console.log("New order:", sortable.toArray());
                var order = sortable.toArray();

                fetch('{{ route('categories.orderupdate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                            toastr.success('Banner Order Saved Successfully!!');
                        } else {
                            alert('Failed to save order.');
                        }
                    });
            }
        });
    </script>
@endsection
