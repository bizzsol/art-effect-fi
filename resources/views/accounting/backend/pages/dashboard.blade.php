@extends('accounting.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .am5exporting-menu.am5exporting-valign-top{
        top: -60px !important;
    }
    .am5exporting-icon{
        width: 75px !important;
        color: #fff !important;
        background-color: black !important;
        border-color: #1e7e34 !important;
        text-decoration: none !important;
    }
    .am5exporting-list{
        margin-top: 30px !important;
        margin-right: 0px !important;
    }
    .am5exporting-type-separator{
        display: none;
    }
    .am5exporting-item a {
        text-decoration: none !important;
    }
</style>
@endsection
@section('main-content')
<div class="row pt-4">
    <div class="col-md-12 mb-4">
        <form action="{{ url('accounting') }}" method="get">
            <div class="form-group row">
                <div class="col-md-2">
                    <label for="fiscal_year_id"><strong>Fiscal Year</strong></label>
                    <select class="form-control" name="fiscal_year_id" id="fiscal_year_id" onchange="printDate()">
                        @if(isset($fiscalYears[0]))
                        @foreach($fiscalYears as $fy)
                        <option value="{{ $fy->id }}" data-start="{{ $fy->start }}" data-end="{{ $fy->end }}" {{ $fiscal_year_id == $fy->id ? 'selected' : '' }}>{{ $fy->title }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="from"><strong>From</strong></label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $from }}">
                </div>
                <div class="col-md-2">
                    <label for="to"><strong>To</strong></label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                </div>
                <div class="col-md-2 pt-4">
                    <button class="btn btn-success mt-2 btn-md btn-block text-white"><i class="las la-search"></i>&nbsp;Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <div class="row">
            @if(isset($companies[0]))
            @foreach($companies as $company)
            @php
                $chart_title = $company->code.' Transactions ('.$fiscalYears->where('id', $fiscal_year_id)->first()->title.' | '.$from.' to '.$to.')';
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 text-white" id="company-entries-title-{{ $company->id }}"><strong>{{ $chart_title }}</strong></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="company-entries-{{ $company->id }}" style="height: 375px;"></div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <p class="text-center">
                                    {{ $chart_title }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="//cdn.amcharts.com/lib/5/plugins/exporting.js"></script>
<script type="text/javascript">
    function printDate(){
        $('#from').val($('#fiscal_year_id').find(':selected').attr('data-start'));
        $('#to').val($('#fiscal_year_id').find(':selected').attr('data-end'));
    }
</script>
@if(isset($companies[0]))
@foreach($companies as $company)
<script>
am5.ready(function() {

    var root = am5.Root.new("company-entries-{{ $company->id }}");

    root.setThemes([
      am5themes_Animated.new(root)
    ]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
      panX: true,
      panY: true,
      wheelX: "panX",
      wheelY: "zoomX",
      pinchZoomX: true,
      paddingLeft:0,
      paddingRight:1
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    var xRenderer = am5xy.AxisRendererX.new(root, { 
      minGridDistance: 30, 
      minorGridEnabled: true
    });

    xRenderer.labels.template.setAll({
      rotation: -90,
      centerY: am5.p50,
      centerX: am5.p100,
      paddingRight: 15
    });

    xRenderer.grid.template.setAll({
      location: 1
    })

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
      maxDeviation: 0.3,
      categoryField: "entry_type",
      renderer: xRenderer,
      tooltip: am5.Tooltip.new(root, {})
    }));

    var yRenderer = am5xy.AxisRendererY.new(root, {
      strokeOpacity: 0.1
    })

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
      maxDeviation: 0.3,
      renderer: yRenderer
    }));

    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
      name: "Series 1",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value",
      sequencedInterpolation: true,
      categoryXField: "entry_type",
      tooltip: am5.Tooltip.new(root, {
        labelText: "{valueY}"
      })
    }));

    series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
    series.columns.template.adapters.add("fill", function (fill, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    series.columns.template.adapters.add("stroke", function (stroke, target) {
      return chart.get("colors").getIndex(series.columns.indexOf(target));
    });

    var data = <?php echo json_encode($entries[$company->id]) ?>;

    xAxis.data.setAll(data);
    series.data.setAll(data);

    series.appear(1000);
    chart.appear(1000, 100);

    var title = $('#company-entries-title-{{ $company->id }}').text();
    var exporting = am5plugins_exporting.Exporting.new(root, {
      menu: am5plugins_exporting.ExportingMenu.new(root, {}),
      filePrefix: title,
      dataSource: data,
      pdfOptions: {
        pageSize: "A4",
        pageOrientation: "landscape",
      }
    });

    exporting.events.on("pdfdocready", function(event) {
      // Add title to the beginning
      event.doc.content.unshift({
        text: title,
        margin: [0, 10],
        style: {
          fontSize: 14,
          bold: true,
        }
      });

      // Add a two-column intro
      event.doc.content.push({
        alignment: 'justify',
        columns: [{
          text: title
        }],
        columnGap: 0,
        margin: [0, 10]
      });
    });

    $('.am5exporting-icon').html('<i class="las la-file-export"></i>&nbsp;&nbsp;Export');

});
</script>
@endforeach
@endif
@endsection
