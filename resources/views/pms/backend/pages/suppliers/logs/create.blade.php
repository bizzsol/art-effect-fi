<form action="{{ url('pms/supplier/'.$supplier_id.'/save-supplier-log') }}" method="post" accept-charset="utf-8" id="log-form">
@csrf
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="date"><strong>Date&nbsp;<span class="text-danger">*</span></strong></label>
				<input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
			</div>
		</div>
		<div class="col-md-9">
			<div class="form-group">
				<label for="topic"><strong>Topic&nbsp;<span class="text-danger">*</span></strong></label>
				<input type="text" name="topic" id="topic" class="form-control">
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label for="log"><strong>Log&nbsp;<span class="text-danger">*</span></strong></label>
				<textarea name="log" id="log" class="form-control" style="min-height: 200px;"></textarea>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-success button"><i class="la la-save"></i>&nbsp;Save Log</button>
	<a class="btn btn-dark" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Close</a>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		var form = $('#log-form');
		form.on('submit', function(e){
		    e.preventDefault();
		    form.find('.button').html('Please wait...').prop('disabled', true);

		    $.ajax({
		    	url: form.attr('action'),
		    	type: form.attr('method'),
		    	dataType: 'json',
		    	data: form.serializeArray(),
		    })
		    .done(function(response) {
		    	if(response.success){
		    		window.open("{{ url('pms/supplier/profile/'.$supplier_id.'?tab=logs') }}", "_parent");
		    	}else{
		    		$.notify(response.message, 'error');
		    		form.find('.button').html('<i class="la la-save"></i>&nbsp;Save Log').prop('disabled', false);
		    	}
		    })
		    .fail(function(response) {
		    	var errors = '';
		    	$.each(response.responseJSON.errors, function(index, error) {
		    		$.notify(error, 'error');
		    	});

		    	form.find('.button').html('<i class="la la-save"></i>&nbsp;Save Log').prop('disabled', false);
		    });
		});
	});
</script>