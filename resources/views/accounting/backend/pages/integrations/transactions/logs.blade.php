<div style="overflow: hidden;">
    <hr>
    @if(isset($transaction->logs[0]))
    @foreach($transaction->logs as $log)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-white">{{ $log->logs }} at {{ date('Y-m-d g:i a', strtotime($log->created_at)) }}</h5>
                </div>
                <div class="card-body" style="border: 1px solid #ccc;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-2"><strong>Request</strong></h5>
                            @dump(json_decode($log->request, true))

                            <h5 class="mb-2 mt-2"><strong>Reponse</strong></h5>
                            @dump(json_decode($log->response, true))
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>