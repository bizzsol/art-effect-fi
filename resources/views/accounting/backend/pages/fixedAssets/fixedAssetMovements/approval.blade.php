<form action="{{ route('accounting.fixed-asset-movements.update', $movement->id) }}" method="post" accept-charset="utf-8" id="approval-form">
@csrf
@method('PUT')
<input type="hidden" name="status" value="{{ $status }}">
<div class="form-group row">
	<div class="col-md-12">
		<table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 15%">Asset</th>
                    <th style="width: 10%">GRN</th>
                    <th style="width: 10%">Batch ID</th>
                    <th style="width: 10%">Asset Code</th>
                    <th style="width: 10%">Requested By</th>
                    <th style="width: 10%">Location</th>
                    <th style="width: 10%">Move to</th>
                    <th style="width: 15%">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ isset($movement->batchItemUser->batchItem->finalAsset->name) ? $movement->batchItemUser->batchItem->finalAsset->name.' '.getProductAttributesFaster($movement->batchItemUser->batchItem->finalAsset) : '' }}</td>
                    <td>{{ isset($movement->batchItemUser->batchItem->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no) ? $movement->batchItemUser->batchItem->batch->goodsReceivedItemsStockIn->relGoodsReceivedItems->relGoodsReceivedNote->grn_reference_no : '' }}</td>
                    <td>{{ $movement->batchItemUser->batchItem->batch->batch }}</td>
                    <td>{{ $movement->batchItemUser->batchItem->asset_code }}</td>
                    <td>
                        {{ $movement->batchItemUser->user->name.' ('.$movement->batchItemUser->user->phone.')' }}
                    </td>
                    <td>
                        {{ $movement->fixedAssetLocation ? $movement->fixedAssetLocation->name : '' }}
                    </td>
                    <td>
                        {{ $movement->user ? $movement->user->name.' ('.$movement->user->phone.')' : '' }}
                    </td>
                    <td>
                        {{ $movement->remarks }}
                    </td>
                </tr>
            </tbody>
        </table>
	</div>
</div>
<div class="form-group">
	<label for="remarks"><strong>Remarks</strong></label>
	<textarea name="remarks" id="remarks" class="form-control" style="height: 120px !important;resize: none"></textarea>
</div>
<button type="submit" class="btn btn-success approval-button"><i class="la la-check"></i>&nbsp;Proceed</button>
</form>
<script type="text/javascript">
	$('.select2').select2();

    $(document).ready(function() {
        var form = $('#approval-form');
        var button = $('.approval-button');
        var content = button.html();
        
        form.submit(function(event) {
            event.preventDefault();

            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Prease wait...').prop('disabled', true);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                button.html(content).prop('disabled', false);
                if(response.success){
                    toastr.success(response.message);
                    $('#sampleModal').modal('hide');
                    reloadDatatable();
                }else{
                    toastr.error(response.message);
                }
            })
            .fail(function(response) {
                var errors = '<ul class="pl-2">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += "<li class='text-white'>"+val[0]+"</li>";
                });
                errors += '</ul>';
                toastr.error(errors);

                button.html(content).prop('disabled', false);
            });
        }); 
    });
</script>