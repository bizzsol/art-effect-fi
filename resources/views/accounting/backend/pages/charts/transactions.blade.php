@if($view == 'chart')
  <div class="row">
    <div class="col-md-12">
  		<div id="{{ $transaction['slug'] }}-chart" style="height: {{ $transaction['height'] }};"></div>
  	</div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          Carry Forwarded Balance: <strong>{{ ($carryForwarded < 0 ? 'Cr ' : 'Dr ').systemMoneyFormat($carryForwarded < 0 ? $carryForwarded*(-1) : $carryForwarded, ' BDT') }}</strong>
        </div>
        <div class="col-md-6 text-right">
          Consolidated Balance: <strong>{{ ($consolidated < 0 ? 'Cr ' : 'Dr ').systemMoneyFormat($consolidated < 0 ? $consolidated*(-1) : $consolidated, ' BDT') }}</strong>
        </div>
      </div>
    </div>
  	<div class="col-md-12 text-center mt-2">
  		<p>{{ $title }}</p>
  	</div>
  </div>

  <script>
  am5.ready(function() {

      var root = am5.Root.new("{{ $transaction['slug'] }}-chart");

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
        categoryField: "key",
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
        type: "type",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "value",
        sequencedInterpolation: true,
        categoryXField: "key",
        tooltip: am5.Tooltip.new(root, {
          labelText: "{type} {valueY} BDT"
        })
      }));

      series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
      series.columns.template.adapters.add("fill", function (fill, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
      });

      series.columns.template.adapters.add("stroke", function (stroke, target) {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
      });

      var data = <?php echo json_encode($entries) ?>;

      xAxis.data.setAll(data);
      series.data.setAll(data);

      series.appear(1000);
      chart.appear(1000, 100);

      var title = "{{ $title }}";
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

      $('.am5exporting-icon').html('<i class="las la-download"></i>');
  });
  </script>
@else
  <div class="row">
    <div class="col-md-12" style="height: {{ $transaction['height'] }};overflow: auto;">
      <h6 class="bg-primary text-white p-3 text-center"><strong>{{ $title }}</strong></h6>
      <table class="table table-bordered">
        <thead class="bg-dark text-white">
          <tr>
            <th style="width: 10%" class="text-center">SL</th>
            <th style="width: 65%">Description</th>
            <th style="width: 25%" class="text-right">Balance</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($entries[0]))
          @foreach($entries as $key => $entry)
          <tr>
            <td class="text-center">{{ $key+1 }}</td>
            <td>{{ $entry['key'] }}</td>
            <td class="text-right">{{ $entry['type'].' '.systemMoneyFormat($entry['value'], ' BDT') }}</td>
          </tr>
          @endforeach
          @endif

          <tr>
            <td colspan="2" class="text-right"><strong>Carry Forwarded Balance</strong></td>
            <td class="text-right">
              <strong>{{ ($carryForwarded < 0 ? 'Cr ' : 'Dr ').systemMoneyFormat($carryForwarded < 0 ? $carryForwarded*(-1) : $carryForwarded, ' BDT') }}</strong>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-right"><strong>Consolidated Balance</strong></td>
            <td class="text-right">
              <strong>{{ ($consolidated < 0 ? 'Cr ' : 'Dr ').systemMoneyFormat($consolidated < 0 ? $consolidated*(-1) : $consolidated, ' BDT') }}</strong>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endif