@extends('layouts.app')

@section('title', 'Sku Report')

@section('page-header', 'Sku Report')

@section('breadcrumb')
    <li class="breadcrumb-item active">Report</li>
    <li class="breadcrumb-item active">Sku Report</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('features.common.alerts')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Sku Report
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="accordion">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                            <i class="fa fa-search"></i> Search Sku
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">
                                        <form id="skuFilter">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Sku Codes</label>
                                                        <select id="sku_code_id" name="sku_code_id" class="form-control select2">
                                                            <option value="">Select Sku Code</option>
                                                            @foreach ($data['skuCodes'] as $skuCode)
                                                                <option value="{{ $skuCode->id }}">{{ $skuCode->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email">Variant</label>
                                                        <select id="variant_id" name="variant_id" class="form-control select2">
                                                            <option value="">Select Variant</option>
                                                            @foreach ($data['variants'] as $variant)
                                                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary" id="filter">Search</button>
                                                    <button type="button" class="btn btn-default" id="clear">Clear</button>
                                                    <button type="button" class="btn btn-warning" id="getExcel" data-route="{{ route('sku-report.getExcel') }}">Excel Export</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover mt-5" id="skuReportDataTable">
                            <thead>
                                <tr>
                                    <th>Sku Code</th>
                                    <th>Variant Code</th>
                                    <th>Total Weight</th>
                                    <th>Total Weight(in warehouse)</th>
                                    <th>Total Weight(in Assembly Line)</th>
                                    <th>Total Weight(in Pallet Management)</th>
                                    <th>Mapped Weight</th>
                                    <th>Required Weight</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/features/reports/sku/index.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $('#filter').on('click', function () {
            if($('#sku_code_id').val() === '' || $('#sku_code_id').val() === undefined) {
                toastr.warning("Please select SKU Code");
                return false;
            }
            initSkuReportDataTable("{{ route('sku-report.getSkuReport') }}")
        });

        $(document).on('click', '#getExcel', function() {
            if($('#sku_code_id').val() === '' || $('#sku_code_id').val() === undefined) {
                toastr.warning("Please select SKU Code");
                return false;
            }
            var formData = $('#skuFilter').serialize();

            window.location.href = $(this).attr('data-route') + "?" + formData;
        });
    </script>
@endsection
