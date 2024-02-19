@extends('layouts.app')

@section('title', 'Edit Pallet')

@section('page-header', 'Edit Pallet')

@section('styles')

@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Pallet Management</li>
<li class="breadcrumb-item active">Edit Pallet</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        All Pallets
                    </h3>
                </div>
                <form action="{{ route('pallets.update', $pallet->id) }}" method="post" id="createFillPallets" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('features.process.pallet-management.partials._from')
                        <div class="row mb-2">
                            <div class="col-md-12 text-center">
                                <button type="button" id="add_sku" class="btn btn-warning"><i class="fas fa-plus"></i> Add Sku</button>
                            </div>
                        </div>
                        @include('features.process.pallet-management.partials._pallet-details-table')
                    </div>
                    <div class="card-footer clearfix">
                        <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
                        @if($pallet->masterPallet->last_locationable_type != "App\Features\Masters\Warehouses\Domains\Models\Warehouse")
                        <button type="sumbit" name="request_for_warehouse" value="true" class="btn btn-warning"><i class="fas fa-disk"></i> Request for Warehouse</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/process/pallet-management/form.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    $('.dateinputpicker').on('change', function () {
        let date = $(this).find("input").val();
        let warehouse = $(this).parent().find('.batch_prefix').text();

        date = date.replace("-", '');
        date = date.replace("-", '');

        $(this).parent().parent().parent().find('.batchName').val(warehouse+date);
    })
</script>
@endsection
