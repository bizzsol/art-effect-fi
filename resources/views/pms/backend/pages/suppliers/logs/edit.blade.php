<form action="{{ url('pms/supplier/'.$log->id.'/update-supplier-log') }}" method="post" accept-charset="utf-8" id="log-edit-form">
@csrf
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="date"><strong>Date&nbsp;<span class="text-danger">*</span></strong></label>
				<input type="date" name="date" id="date" value="{{ $log->date }}" class="form-control">
			</div>
		</div>
		<div class="col-md-9">
			<div class="form-group">
				<label for="topic"><strong>Topic&nbsp;<span class="text-danger">*</span></strong></label>
				<input type="text" name="topic" id="topic" value="{{ $log->topic }}" class="form-control">
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label for="log"><strong>Log&nbsp;<span class="text-danger">*</span></strong></label>
				<textarea name="log" id="log" class="form-control" style="min-height: 200px;">{{ $log->log }}</textarea>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-success button"><i class="la la-save"></i>&nbsp;Update Log</button>
	<a class="btn btn-dark" data-dismiss="modal"><i class="la la-times"></i>&nbsp;Close</a>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		var form = $('#log-edit-form');
		form.on('submit', function(e){
		    e.preventDefault();
		    form.find('.button').html('Please wait...').prop('disabled', true);
		    console.log(form.attr('action'));

		    $.ajax({
		    	url: form.attr('action'),
		    	type: form.attr('method'),
		    	dataType: 'json',
		    	data: form.serializeArray(),
		    })
		    .done(function(response) {
		    	if(response.success){
		    		window.open("{{ url('pms/supplier/profile/'.$log->supplier_id.'?tab=logs') }}", "_parent");
		    	}else{
		    		$.notify(response.message, 'error');
		    		form.find('.button').html('<i class="la la-save"></i>&nbsp;Update Log').prop('disabled', false);
		    	}
		    })
		    .fail(function(response) {
		    	var errors = '';
		    	$.each(response.responseJSON.errors, function(index, error) {
		    		$.notify(error, 'error');
		    	});

		    	form.find('.button').html('<i class="la la-save"></i>&nbsp;Update Log').prop('disabled', false);
		    });
		});
	});
</script>