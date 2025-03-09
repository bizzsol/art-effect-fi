@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .col-form-label {
            font-size: 14px;
            font-weight: 600;
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
                        <form action="{{ route('accounting.bank-branches.update', $bankBranch->id) }}"
                              method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row pr-3">
                                <div class="col-md-4">
                                    <label for="bank_id"><strong>{{ __('Select Bank') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="bank_id" id="bank_id"
                                                class="form-control rounded">
                                            @if(isset($banks[0]))
                                                @foreach($banks as $key => $bank)
                                                    <option value="{{ $bank->id }}" {{$bank->id===$bankBranch->bank_id?'selected':''}}>
                                                        &nbsp;&nbsp;{{ $bank->name }}
                                                        ({{ $bank->code }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="code"><strong>{{ __('Code') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="code" id="code" value="{{ $bankBranch->code }}"
                                               readonly class="form-control rounded">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="short_name"><strong>{{ __('Branch Short Name') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="short_name" id="short_name"
                                               value="{{ old('short_name', $bankBranch->short_name) }}"
                                               class="form-control rounded">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="name"><strong>{{ __('Branch Name') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="name" id="name"
                                               value="{{ old('name', $bankBranch->name) }}"
                                               class="form-control rounded">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="swift_code"><strong>{{ __('Swift Code') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="swift_code" id="swift_code"
                                               value="{{ old('swift_code',$bankBranch->swift_code) }}"
                                               class="form-control rounded" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="routing_no"><strong>{{ __('Routing Number') }}:<span
                                                    class="text-danger">&nbsp;*</span></strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="routing_no" id="routing_no"
                                               value="{{ old('routing_no',$bankBranch->routing_no) }}"
                                               class="form-control rounded" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="address"><strong>{{ __('Address') }}</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                            <textarea name="address" id="address" class="form-control rounded"
                                                      style="min-height: 80px">{{ old('address',$bankBranch->address) }}
                                            </textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="description"><strong>{{ __('Description') }}</strong></label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <textarea name="description" id="description" class="form-control rounded"
                                                  style="min-height: 80px">{{ old('description', $bankBranch->description) }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-dark btn-md" href="{{ url('accounting/bank-branches') }}"><i
                                                class="la la-times"></i>&nbsp;Cancel</a>
                                    <button type="submit" class="btn btn-success btn-md"><i class="la la-save"></i>&nbsp;Update
                                        Branch
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection