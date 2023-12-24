<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <form id="form" class="form d-flex flex-column flex-lg-row" action="" method="POST" enctype="multipart/form-data">
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header"style="clear:both; position:relative;">
                        <div class="col-md-3" style="position:absolute; left:25pt; width:292pt;">
                            <img src="" width="25%" height="10%">
                        </div>
                        <div class="card-title" style="margin-left:150pt; margin-bottom:50pt;">
                            <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Log Systems {{ $settings['nama_app_admin'] }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        <div class="mb-10 fv-row" style="margin-bottom: 30px !important">
                            <!--begin::Label-->
                            {{-- <label class="form-label">Detail Item Variants</label><br/><br/> --}}
                            <!--end::Label-->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table detail gy-3 fs-7">
                                        <thead style="vertical-align: top;">
                                            <tr class="fw-bolder bg-light detail">
                                                <th width="min-w-25" class="text-center align-middle">No</th>
                                                <th width="min-w-25" class="text-center align-middle">ID</th>
                                                <th width="min-w-150" class="text-center align-middle">User</th>
                                                <th width="min-w-100" class="text-center align-middle">Module</th>
                                                <th width="min-w-100" class="text-center align-middle">Action</th>
                                                <th width="min-w-100" class="text-center align-middle">Tanggal</th>
                                                <th width="min-w-100" class="text-center align-middle">Ip Address</th>
                                                <th width="min-w-100" class="text-center align-middle">Device</th>
                                                <th width="min-w-100" class="text-center align-middle">Browser Name</th>
                                                <th width="min-w-100" class="text-center align-middle">Browser Version</th>
                                                <th width="min-w-100" class="text-center align-middle">Data ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $col)
                                                <tr>
                                                    <td>{{$key + 1}}</td>
                                                    <td>{{$col->id}}</td>
                                                    <td>{{$col->user->name}}</td>
                                                    <td>{{$col->module}}</td>
                                                    <td>{{$col->action}}</td>
                                                    <td>{{$col->created_at ? date('d-m-Y H:i:s', strtotime($col->created_at)) : '' }}</td>
                                                    <td>{{$col->ip_address}}</td>
                                                    <td>{{$col->device}}</td>
                                                    <td>{{$col->browser_name}}</td>
                                                    <td>{{$col->browser_version}}</td>
                                                    <td>{{$col->data_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="11">{{($col->data)}}</td>
                                                </tr>
                                            @endforeach
                                            @if (empty($data))
                                                <tr>
                                                    <td colspan="14" class="text-center align-middle">No Data Available</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::General options-->
            </div>
            <!--end::Main column-->
        </form>
    </div>
    <!--end::Container-->
</div>
<style>
    html,
    @page { margin: 0px; }
    body {
        margin: 10px;
        padding: 10px;
        font-family: sans-serif;
    }
    h1,h2,h3,h4,h5,h6,p,span,label {
        font-family: sans-serif;
    }
    table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0px !important;
    }

    table#putih > td {
        border: 1px solid white !important;
    }

    table thead th {
        height: 10px;
        text-align: left;
        font-size: 12px;
        font-family: sans-serif;
    }

    table, th, td {
        border: 1px solid black;
        padding: 8px;
        font-size: 10px;
        word-wrap: break-word; /* Menambahkan properti word-wrap di sini */
    }


    .heading {
        font-size: 24px;
        margin-top: 12px;
        margin-bottom: 12px;
        font-family: sans-serif;
    }
    .small-heading {
        font-size: 16px;
        font-family: sans-serif;
    }
    .form-label {
        font-size: 14px;
        font-family: sans-serif;
        font-weight: bold;
    }
    .form-rekening {
        font-size: 11px;
        font-family: sans-serif;
        margin-top: 8px;
    }
    .form-permohonan {
        font-size: 12px;
        font-family: sans-serif;
        margin-top: 9px;
    }
    .form-check-input{
        font-family: sans-serif;
        margin-top: 10px;
    }
    .total-heading {
        font-size: 18px;
        font-weight: 700;
        font-family: sans-serif;
    }
    .responsive {
        width: 100%;
        height: auto;
    }

    .text-start {
        text-align: left;
    }
    .text-end {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .company-data span {
        margin-bottom: 4px;
        display: inline-block;
        font-family: sans-serif;
        font-size: 14px;
        font-weight: 400;
    }
    .no-border {
        border: 1px solid #fff !important;
    }
    .no-left-border{
        border-left: 1px solid #fff !important;
    }
    .no-bottom-border{
        border-bottom:  1px solid #fff !important;
    }
    .bg-blue {
        background-color:darkgray;
        color: black;
    }
    .form-check-input[type="checkbox"] {
        width: 16px; /* Set the desired width */
        height: 16px; /* Set the desired height */
    }
</style>

