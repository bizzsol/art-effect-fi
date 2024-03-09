@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #000 !important;
            font-weight: bold !important;
        }

        .bordered {
            border: 1px #ccc solid
        }

        .floating-title {
            position: absolute;
            top: -13px;
            left: 15px;
            background: white;
            padding: 0px 5px 5px 5px;
            font-weight: 500;
        }

        .card-body {
            padding-top: 20px !important;
            padding-bottom: 0px !important;
        }

        .label {
            font-weight: bold !important;
        }

        .tab-pane {
            padding-top: 15px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
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
                    <li class="top-nav-btn">
                        <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-3">
                    <div class="panel-boby p-3">
                        <form action="{{ route('accounting.cheque-books.update', $chequeBook->id) }}" method="post"
                              accept-charset="utf-8">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body bordered">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="bank_account_id"><strong>{{ __('Bank Account') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select class="form-control" name="bank_account_id"
                                                                id="bank_account_id">
                                                            @if(isset($bankAccounts[0]))
                                                                @foreach($bankAccounts as $bankAccount)
                                                                    <option value="{{$bankAccount->id}}" {{ $chequeBook->bank_account_id == $bankAccount->id ? 'selected' : '' }}>{{
                                                                $bankAccount->name.' ('.$bankAccount->number.') ('.($bankAccount->currency ? $bankAccount->currency->code : '').')'
                                                            }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="book_number"><strong>{{ __('Book Number') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="text" name="book_number" id="book_number"
                                                               value="{{ $chequeBook->book_number }}"
                                                               class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="receiving_date"><strong>{{ __('Receiving Date') }}:<span
                                                                    class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="date" name="receiving_date" id="receiving_date"
                                                               value="{{ $chequeBook->receiving_date }}"
                                                               class="form-control rounded">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-2">
                                                    <label for="page_number_from"><strong>{{ __('Starting Page Number') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="number" name="page_number_from" id="page_number_from" value="{{ $chequeBook->pages->min('page_number') }}" class="form-control rounded">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="page_number_to"><strong>{{ __('Ending Page Number') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <input type="number" name="page_number_to" id="page_number_to" value="{{ $chequeBook->pages->max('page_number') }}" class="form-control rounded">
                                                    </div>
                                                </div> --}}
                                            </div>

                                            <div class="col-md-12 pl-0 mb-2">
                                                <button type="submit" class="btn btn-success btn-md"><i
                                                            class="la la-save"></i>&nbsp;Update Cheque Books
                                                </button>
                                                <a class="btn btn-dark btn-md"
                                                   href="{{ url('accounting/cheque-books') }}"><i
                                                            class="la la-times"></i>&nbsp;Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection