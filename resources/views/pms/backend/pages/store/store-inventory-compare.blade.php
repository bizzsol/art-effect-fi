<div class="col-md-12">
    <div class="panel panel-info">
        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">
                <div class="well col-6">
                    <ul class="list-unstyled mb0">
                        <li><strong>{{__('Name') }} :</strong>
                            {{isset($requisition->relUsersList->name)?$requisition->relUsersList->name:''}}
                        </li>

                        <li><strong>{{__('Unit')}} :</strong>
                            {{ $requisition->unit->hr_unit_short_name }}
                        </li>
                        <li><strong>{{__('Location')}} :</strong>
                            {{isset($requisition->relUsersList->employee->location->hr_location_name)?$requisition->relUsersList->employee->location->hr_location_name:''}}
                        </li>
                        <li><strong>{{__('Department')}} :</strong>
                            {{isset($requisition->relUsersList->employee->department->hr_department_name)?$requisition->relUsersList->employee->department->hr_department_name:''}}
                        </li>
                        <li>
                            <strong>Department Head:</strong> {{getDepartmentHeadName($requisition->author_id)}}
                        </li>
                    </ul>
                </div>
                <div class="col-6">
                    <ul class="list-unstyled mb0 pull-right">
                        <li><strong>{{__('Date')}} :</strong>
                            {{date('d-m-Y', strtotime($requisition->requisition_date))}}
                        </li>
                        <li><strong>{{__('Reference No')}}:</strong> {{$requisition->reference_no}}</li>
                        <li><strong>Saleable:</strong> {{ ucwords($requisition->saleable) }}</li>
                        <li><strong>Assigned To (For Finance Approval):</strong> {{ ucwords
                        ($requisition->assignedFinanceUser->name?? '')
                        }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            @if($requisition->projectTask)
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Project</th>
                        <th>Phase</th>
                        <th>Milestone</th>
                        <th>Task</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ isset($requisition->projectTask->subDeliverable->deliverable->project)?$requisition->projectTask->subDeliverable->deliverable->project->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask->subDeliverable->deliverable)?$requisition->projectTask->subDeliverable->deliverable->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask->subDeliverable)?$requisition->projectTask->subDeliverable->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask)?$requisition->projectTask->name:'' }}</td>

                    </tr>
                </tbody>
            </table>
            @endif
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>SL</th>
                        <th>Product</th>
                        <th>Attributes</th>
                        <th>UOM</th>
                        <th>Stock Qty</th>
                        <th>Requisition Qty</th>
                        @if($requisition->status==1)
                        <th>Approved Qty</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($requisition))
                    @php
                    $totalStockQty = 0;
                    $totalRequisitionQty = 0;
                    $totalApprovedQty = 0;
                    @endphp
                    @foreach($requisition->items as $key=>$item)
                    @php
                    $stockQty = \App\Models\PmsModels\InventoryModels\InventoryLogs::whereIn('warehouse_id', auth()->user()->relUsersWarehouse->pluck('id')->toArray())
                    ->whereHas('stockIn.relGoodsReceivedItems.attributes', function($query) use ($item){
                    $query->select(\DB::raw('count(distinct id)'))
                    ->whereIn('attribute_option_id', $item->attributes->pluck('attribute_option_id')->toArray());
                    }, '=', count($item->attributes->pluck('attribute_option_id')->toArray()))
                    ->where([
                    'category_id' => $item->product->category_id,
                    'product_id' => $item->product_id,
                    'hr_unit_id' => (isset(auth()->user()->employee->as_unit_id) ? auth()->user()->employee->as_unit_id : 0)
                    ])
                    ->sum('available');
                    @endphp
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{isset($item->product->name)?$item->product->name:''}} {{ getProductAttributesFaster(isset($item->product)?$item->product:'') }}</td>
                        <td>{{ getProductAttributesFaster($item) }}</td>
                        <td>{{isset($item->product->productUnit->unit_name)?$item->product->productUnit->unit_name:''}}</td>
                        <td class="text-center">{{$stockQty}}</td>
                        <td class="text-center">{{number_format($item->requisition_qty,0)}}</td>
                        @if($requisition->status==1)
                        <td class="text-center">{{$item->qty}}</td>
                        @endif
                    </tr>

                    {{-- @php
                        $totalStockQty += $stockQty;
                        $totalRequisitionQty += $item->requisition_qty;
                        $totalApprovedQty += $item->qty;
                    @endphp --}}
                    @endforeach
                    @endif

                    {{-- <tr>
                        <td colspan="4" class="text-right">Total</td>
                        <td class="text-center">{{$totalStockQty}}</td>
                    <td class="text-center">{{$totalRequisitionQty}}</td>
                    <td class="text-center">{{$totalApprovedQty}}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        <div class="row">
            @if(isset($requisition->attachments) && count($requisition->attachments) > 0)
            <div class="col-6">
                <h4>Attachment List:</h4>
                <ol type="number">
                    @foreach($requisition->attachments as $key => $value)
                    <li><a href="{{ url($value->file_location) }}" target="_blank">Attachment{{++$key}}</a></li>
                    @endforeach
                </ol>
            </div>
            @endif
        </div>
    </div>
</div>