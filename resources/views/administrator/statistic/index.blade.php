@extends('administrator.layouts.main')

@section('content')
    @push('section_header')
        <h1>Systems</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Statistic</div>
        </div>
    @endpush
    @push('section_title')
        Statistic
    @endpush

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-8">
                        <h4>List Data</h4>
                    </div>
                    <div class="col-4" style="display: flex; justify-content: flex-end;">
                    </div>
                </div>
                @include('administrator.logs.filter.main')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th width="15px" class="text-center">
                                        No
                                    </th>
                                    <th width="50%">Ip Address</th>
                                    <th width="50%">Page</th>
                                    <th width="200px">Visit Time</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('administrator.statistic.modal.detail')
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var data_table = $('#datatable').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sFirst": "<i class='ti-angle-left'></i>",
                        "sPrevious": "&#8592;",
                        "sNext": "&#8594;",
                        "sLast": "<i class='ti-angle-right'></i>"
                    }
                },
                processing: true,
                serverSide: true,
                order: [
                    [4, 'desc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.statistic.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                    render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        mRender : function (data, type, row, meta) {
                            let page = '';
                            if (row.url === '') {
                                page = 'home';
                            } else {
                                page = row.url;
                            }

                            return page;
                        },
                    },
                    {
                        data: 'visit_time',
                        name: 'visit_time'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false,
                        class: 'text-center'
                    }
                ],
            });
        });
    </script>
@endpush
