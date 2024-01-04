<div class="col-md-6 col-12 pt-3">
    <div class="form-group fv-row">
        <label for="inputUserGroup" class="form-label">UserGroup</label>
        <div class="input-group">
            <!-- Menggunakan input-group untuk menyatukan input dan tombol -->
            <input type="text" class="form-control" id="inputUserGroupName" readonly>
            <input type="text" class="d-none" name="user" id="inputUserGroupId">
            <div class="input-group-append">
                <!-- Menggunakan input-group-append untuk menambahkan elemen setelah input -->
                <a href="#" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#filterUserGroup">
                    Search
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail UserGroup -->
<div class="modal fade" tabindex="-1" role="dialog" id="filterUserGroup" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterUserGroupLabel">Filter UserGroup</h5>
                <button type="button" class="close m-1" id="buttonCloseUserGroup" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="filterUserGroupBody">
                <table class="table" id="datatableUserGroupModal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Nama</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectData-UserGroup">Pilih</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Function to add 'selected' class to the row based on the user ID
        function addSelectedClassByUserGroupId(userGroupId) {
            var table = $('#datatableUserGroupModal').DataTable();
            table.rows().deselect(); // Deselect all rows first
            table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows

            console.log(userGroupId);
            if (userGroupId) {
                table.rows().every(function() {
                    var rowData = this.data();
                    if (rowData.id === parseInt(userGroupId)) {
                        this.select(); // Select the row
                        $(this.node()).addClass('selected'); // Add 'selected' class
                        return false; // Break the loop
                    }
                });
            }
        }

        $('#filterUserGroup').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Now, you can initialize a new DataTable on the same table.
            $("#datatableUserGroupModal").DataTable().destroy();
            $('#datatableUserGroupModal tbody').remove();
            var data_table_user = $('#datatableUserGroupModal').DataTable({
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
                ajax: {
                    url: '{{ route('admin.users.getDataUserGroup') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var userGroupId = $("#inputUserGroupId").val();
                    addSelectedClassByUserGroupId(userGroupId);
                },
            });

            // click di baris tabel user
            $('#datatableUserGroupModal tbody').on('click', 'tr', function() {
                // Remove the 'selected' class from all rows
                $('#datatableUserGroupModal tbody tr').removeClass('selected');

                // Add the 'selected' class to the clicked row
                $(this).addClass('selected');

                var data = data_table_user.row(this).data();
            });

            // click di tombol Pilih UserGroup
            $('#selectData-UserGroup').on('click', function() {
                // Get the selected row data
                var selectedRowData = data_table_user.rows('.selected').data()[0];

                // Check if any row is selected
                if (selectedRowData) {
                    // Use the selected row data
                    $("#inputUserGroupName").val(selectedRowData.name);
                    $("#inputUserGroupId").val(selectedRowData.id);

                    // Close the modal
                    $('#buttonCloseUserGroup').click();
                } else {
                    // Handle the case where no row is selected
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success mx-4',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'Please select a data first.',
                        icon: 'error',
                        // timer: 1500, // 2 detik
                        showConfirmButton: true
                    });
                }
            });
            // end click di tombol Pilih UserGroup
        });
    </script>
@endpush
