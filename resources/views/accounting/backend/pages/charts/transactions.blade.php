<div class="row">
	<div class="col-md-12">
		<div id="{{ $transaction['slug'] }}-chart" style="height: {{ $transaction['height'] }};"></div>
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
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value",
      sequencedInterpolation: true,
      categoryXField: "key",
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