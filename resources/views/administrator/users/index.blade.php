@extends('administrator.layouts.main')

@section('content')
    @push('section_header')
        <h1>Users</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Users</div>
        </div>
    @endpush
    @push('section_title')
        User
    @endpush

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-8">
                        <h4>List Data</h4>
                    </div>
                    <div class="col-4" style="display: flex; justify-content: flex-end;">
                        @if (isallowed('user', 'add'))
                            <a href="{{ route('admin.users.add') }}" class="btn btn-primary">Tambah Data</a>
                        @endif
                        @if (isallowed('user', 'arsip'))
                            <a href="{{ route('admin.users.arsip') }}" class="btn btn-primary mx-3">Arsip</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-primary" id="filterButton">Filter</a>
                    </div>

                </div>
                @include('administrator.users.filter.main')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th width="15px">No</th>
                                    <th width="200px">User Group</th>
                                    <th width="50%">Nama</th>
                                    <th width="50%">Email</th>
                                    <th width="15px">Status</th>
                                    <th width="200px">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('administrator.users.modal.detail')
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
                    [0, 'asc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.users.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                    data: function(d) {
                        d.status = getStatus();
                        d.usergroup = getUserGroup();
                    }

                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'user_group.name',
                        name: 'user_group.name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'status',
                        name: 'status'
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


            $(document).on('click', '.delete', function(event) {
                var id = $(this).data('id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Apakah anda yakin ingin menghapus data ini',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.users.delete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.users.getData') }}')
                                //     .load();
                                data_table.ajax.reload(null, false);
                                swalWithBootstrapButtons.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1500, // 2 detik
                                    showConfirmButton: false
                                });

                                // Remove the deleted row from the DataTable without reloading the page
                                // data_table.row($(this).parents('tr')).remove().draw();
                            }
                        });
                    }
                });
            });


            //Change Status Confirmation
            $(document).on('click', '.changeStatus', function(event) {
                var ix = $(this).data('ix');
                if ($(this).is(':checked')) {
                    var status = "Tidak Aktif";
                    var changeto = "Aktif";
                    var message = "";
                } else {
                    var status = "Aktif"
                    var changeto = "Tidak Aktif";
                    var message = "";
                }

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    html: 'Apakah anda yakin ingin mengubah status ke ' + changeto + '?' + message,
                    icon: "info",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "Ya, saya yakin!",
                    cancelButtonText: 'Tidak, batalkan',
                    reverseButtons: true

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.users.changeStatus') }}",
                            data: ({
                                "_token": "{{ csrf_token() }}",
                                "_method": "POST",
                                ix: ix,
                                status: changeto,

                            }),
                            success: function() {
                                data_table.ajax.reload(null, false);
                                swalWithBootstrapButtons.fire({
                                    title: 'Berhasil!',
                                    text: 'Status berhasil diubah ke ' +
                                        changeto,
                                    icon: 'success',
                                    timer: 1500, // 2 detik
                                    showConfirmButton: false
                                });
                            }
                        });

                    } else {
                        if (status == "Aktif") {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    }
                });
            });

            $('#filterButton').on('click', function() {
                $('#filter_section').slideToggle();

            });

            $('#filter_submit').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Get the filter value using the getStatus() function
                var filterStatus = getStatus();
                var filterUserGroup = getUserGroup();

                // Update the DataTable with the filtered data
                data_table.ajax.url('{{ route('admin.users.getData') }}?status=' + filterStatus +
                        '|usergroup=' + filterUserGroup)
                    .load();
            });

            $('#reset-btn').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Reset all input fields
                $('#inputUserGroupName').val('');
                $('#inputUserGroupId').val('');
                $('#filterstatus').val('');

                // Update the DataTable with the filtered data
                data_table.ajax.url('{{ route('admin.users.getData') }}').load();
            });


            function getStatus() {
                return $("#filterstatus").val();
            }

            function getUserGroup() {
                return $("#filterusergroup").val();
            }
        });
    </script>
@endpush
