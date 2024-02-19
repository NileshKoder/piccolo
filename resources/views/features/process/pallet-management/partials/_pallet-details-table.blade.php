<div class="row  mr-3">
    <div class="col-md-12 mr-1">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sku Code</th>
                    <th>Varinat</th>
                    <th>Batch</th>
                    <th>Weight</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody id="skuTableBody">
                @if(!empty($pallet))
                @if(!empty($pallet->palletDetails))
                @foreach($pallet->palletDetails as $key => $palletDetail)
                <tr>
                    <input type="hidden" name="pallet_details[{{ $key }}][id]" value="{{ $palletDetail->id }}">
                    <td>
                        <input type="hidden" class="selected_sku_id" name="pallet_details[{{ $key }}][sku_code_id]" value="{{ $palletDetail->sku_code_id }}">
                        <input type="text" class="form-control" value="{{ $palletDetail->skuCode->name }}" disabled>
                    </td>
                    <td>
                        <input type="hidden" class="selected_variant_id" name="pallet_details[{{ $key }}][variant_id]" value="{{ $palletDetail->variant_id }}">
                        <input type="text" class="form-control" value="{{ $palletDetail->variant->name }}" disabled>
                    </td>
                    <td>
                        @php
                            if (preg_match('/^([A-Za-z]+)([0-9]+)$/', $palletDetail->batch, $matches)) {
                                $alphabets = $matches[1];
                                $numbers = $matches[2];
                            } else {
                                $alphabets = "";
                                $numbers = date('dmY',strtotime($palletDetail->batch_date));
                            }
                        @endphp
                        <input type="hidden" class="batchName" name="pallet_details[{{ $key }}][batch]" value="{{ $palletDetail->batch }}">
                        <input type="hidden"  value="{{ $palletDetail->batch_date }}">
{{--                        <input type="text" class="form-control" value="{{ $palletDetail->batch }}" disabled>--}}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text batch_prefix" id="batch_prefix_{{$key}}">{{ $alphabets }}</span>
                                <div class="input-group dateinputpicker" id="reservationdate{{$key}}" data-target-input="nearest">
                                    <input type="text" name="pallet_details[{{ $key }}][batch_date]"
                                           class="form-control datetimepicker-input datechange" required
                                           data-target="#reservationdate{{$key}}"placeholder="dd-mm-yyyy"
                                           value="{{ date('d-m-Y',strtotime($palletDetail->batch_date)) }}"
                                           id="batch_date_{{$key}}">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if(!empty($palletDetail->orderItemPallet))
                            @if($palletDetail->orderItemPallet->orderItem->state != "CANCELLED")
                                <input type="hidden" name="pallet_details[{{ $key }}][weight]" class="weight" value="{{ $palletDetail->weight }}">
                                <input type="text" class="form-control" value="{{ $palletDetail->weight }}" disabled>
                                <span class="text-danger">
                                    Mapped {{ $palletDetail->orderItemPallet->weight }} weight for Order : {{ $palletDetail->orderItemPallet->orderItem->order->order_number }}
                                </span>
                           @endif
                        @else
                            <input type="text" class="form-control weight" name="pallet_details[{{ $key }}][weight]" value="{{ $palletDetail->weight }}">
                        @endif
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="deleteSku text-danger">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @endif
                @endif
            </tbody>
            <tfoot>
                <thead>
                    <tr>
                        <th colspan="3">Total Weight</th>
                        <th>
                            <input type="text" id="total_weight" class="form-control" disabled value="0">
                        </th>
                    </tr>
                </thead>
            </tfoot>
        </table>
        <span>
            <b>Note</b> : Maximum Weight of pallet is 900 KG
        </span>
    </div>
</div>
