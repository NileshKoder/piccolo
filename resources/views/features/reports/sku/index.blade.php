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
                        <!-- <div class="card-tools">
                        <a href="#" data-href="{{ route('sku-report.getExcel') }}">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="fas fa-excel"></i> Excel Export
                            </button>
                        </a>
                    </div> -->
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
                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Sku Codes</label>
                                                    <select id="sku_code_id" class="form-control select2">
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
                                                    <select id="variant_id" class="form-control select2">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover mt-5" id="skuReportDataTable">
                            <thead>
                                <tr>
                                    <th>Sku Code</th>
                                    <th>Variant Code</th>
                                    <th>Total Weight(in warehouse)</th>
                                    <th>Mapped Weight</th>
                                    <th>Unmapped Weight</th>
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
        initSkuReportDataTable("{{ route('sku-report.getSkuReport') }}");
    </script>
@endsection
