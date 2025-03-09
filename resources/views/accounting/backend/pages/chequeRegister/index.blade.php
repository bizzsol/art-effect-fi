@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }
    </style>
    @include('yajra.css')
@endsection
@section('main-content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                    </li>
                    <li><a href="#">PMS</a></li>
                    <li class="active">Accounts</li>
                    <li class="active">{{__($title)}}</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-2 p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bank_account_id"><strong>Choose Bank Account</strong></label>
                                <select name="bank_account_id" id="bank_account_id"
                                        class="form-control this-page-select2"
                                        onchange="window.open('{{ url('accounting/cheque-register') }}?bank_account_id='+$('#bank_account_id').val(), '_parent')">
                                    <option value="0">Choose a Bank Account</option>
                                    @isset($bankAccounts[0])
                                        @foreach($bankAccounts as $key => $bankAccount)
                                            <option value="{{ $bankAccount->id }}" {{ request()->get('bank_account_id') == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name }}
                                                ({{$bankAccount->number}})
                                                ({{ $bankAccount->currency ? $bankAccount->currency->code : '' }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info mt-2 p-3">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    @include('yajra.js')
    <script type="text/javascript">
        function getShortDetails(element) {
            $.dialog({
                title: (element.attr('data-entry-type')) + " Voucher #" + (element.attr('data-code')),
                content: "url:{{ url('accounting/cheque-register') }}/" + (element.attr('data-id')) + "?short-details",
                animation: 'scale',
                columnClass: 'col-md-12',
                closeAnimation: 'scale',
                backgroundDismiss: true
            });
        }

        function editNarration(element) {
            var content = element.html();
            $.confirm({
                title: 'Update Narration',
                content: '<hr class="mt-0 pt-0">' +
                    '<form action="" class="formName">' +
                        '<div class="form-group">' +
                    '<label><strong>Payee Name</strong></label>' +
                    '<input type="text" placeholder="Write Payee Name Here..." class="payee_name form-control" value="' + (element.attr('data-payee-name')) + '">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label><strong>Narration</strong></label>' +
                    '<textarea placeholder="Write Narration Here..." class="narration form-control" style="min-height: 200px;max-height: 400px">' + (element.attr('data-narration')) + '</textarea>' +
                    '</div>' +
                    '</form>',
                buttons: {
                    cancel: function () {
                        //close
                    },
                    update: {
                        text: 'Update',
                        btnClass: 'btn-success',
                        action: function () {
                            var narration = this.$content.find('.narration').val();
                            var payee_name = this.$content.find('.payee_name').val();

                            element.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

                            $.ajax({
                                url: "{{ url('accounting/cheque-register') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: element.attr('data-cheque-id'),
                                    narration: narration,
                                    payee_name: payee_name,
                                },
                            })
                                .done(function (response) {
                                    if (response.success) {
                                        toastr.success(response.message);
                                        reloadDatatable();
                                    } else {
                                        toastr.error(response.message);
                                    }

                                    element.html(content).prop('disabled', false);
                                });
                        }
                    }
                }
            });
        }
    </script>
    @include('accounting.backend.pages.approval-scripts')
@endsection