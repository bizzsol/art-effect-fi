@section('page-script')
<script src='{{ asset('assets/js/Chart.bundle.min.js') }}'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.counterup/1.0/jquery.counterup.min.js"></script>

<script>
$(document).ready(function() {
    //Call The Charts
    $.each($('.charts'), function(index, value) {
        loadChart($(this), $(this).attr('data-chart'), $(this).attr('data-labels').split(','), $(this).attr('data-data').split(','), $(this).attr('data-legend-position'), $(this).attr('data-title-text'));
    });

    $.each($('.bar-charts'), function(index, value) {
        loadBarChart($(this), $(this).attr('data-labels'), $(this).attr('data-data'), $(this).attr('data-title-text'), $(this).attr('data-legend-position'));
    });

    //Load The Charts
    function loadChart(element, type, labels, data, legendPosition, titleText, responsive = true, titleDisplay = true) {
        var chart = new Chart(element, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: randomColors(labels.length),
                    borderColor: '#ccc',
                    borderWidth: '1px'
                }]
            },
            options: {
                responsive: responsive,
                plugins: {
                    legend: {
                        position: legendPosition,
                    }
                },
                title: {
                    display: titleDisplay,
                    text: titleText
                },
                animation: {
                  onComplete: function() {
                    addDownloadOptions(chart, element);
                  }
                }
            }
        });
    }

    function loadBarChart(element, labels, data, titleText, legendPosition, responsive = true, beginAtZero = true, titleDisplay = false){

        var titleTextArray = titleText.split(',');
        var dataArray = data.split('|');
        var datasets = [];
        var colors = randomColors(titleTextArray.length);
        Array.from(titleTextArray).map((item, index)=> {
            datasets.push({
                label: item,
                data: dataArray[index].split(','),
                backgroundColor: colors[index],
                borderColor: '#fff',
                borderWidth: 1
            });
        });

        let barChart = document.getElementById(element.attr('id')).getContext('2d');
        var chart = new Chart(barChart, {
            type: 'bar',
            data: {
                labels: labels.split(','),
                datasets: datasets
            },
            options: {
                responsive: responsive,
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero: beginAtZero
                    }
                  }]
                },
                plugins: {
                    legend: {
                        position: legendPosition,
                    }
                },
                title: {
                    display: titleDisplay,
                    text: titleText.split(',')
                },
                animation: {
                  onComplete: function() {
                    addDownloadOptions(chart, element);
                  }
                }
            }
        });
    }

    function addDownloadOptions(chart, element) {
        element.parent().find('img').remove();
        element.parent().append('<img src="'+(chart.toBase64Image())+'" class="d-none"/>');

        if(element.parent().parent().find('.iq-card-header')){
            element.parent().parent().find('.iq-card-header').find('.download-button').remove();
            element.parent().parent().find('.iq-card-header').append('<a class="btn btn-xs btn-primary download-button pull-right" style="margin-top: 0px !important" href="'+(chart.toBase64Image())+'" download="'+(element.parent().parent().find('.card-title').text())+'"><i class="la la-download"></i></a>');
        }

        if(element.parent().parent().find('.project-card-header')){
            element.parent().parent().find('.project-card-header').find('.download-button').remove();
            element.parent().parent().find('.project-card-header').append('<a class="btn btn-xs btn-primary download-button pull-right" style="margin-top: -25px !important" href="'+(chart.toBase64Image())+'" download="'+(element.parent().parent().find('h5').text())+'"><i class="la la-download"></i></a>');
        }
    }

    //Chart Options
    function randomColors(value) {
        let colors = [];
        for(var i=0;i<value;i++){
            if(colorBank().length >= i){
                colors.push(colorBank()[i]);
            }else{
                colors.push(randomBackgroundColor().color);
            }
        }
        return colors;
    }

    function colorBank($key = false) {
        let colors = [
            'rgb(11,173,191)',
            'rgb(60, 179, 113)',
            'rgb(238, 130, 238)',
            'rgb(255, 165, 0)',
            'rgb(106, 90, 205)',
            'rgb(60, 60, 60)',
            'rgba(255, 99, 71, 1)',
        ];

        return ($key ? colors[key] : colors);
    }

    function randomBackgroundColor() {
        var x = Math.floor(Math.random() * 256);
        var y = 100 + Math.floor(Math.random() * 256);
        var z = 50 + Math.floor(Math.random() * 256);
        var bgColor = "rgb(" + x + "," + y + "," + z + ")";
        var opColor = "rgb(" + x + "," + y + "," + z +","+ 0.5+")";
        return {color:bgColor,opacity:opColor};
    }

    $('.counter').counterUp({
      delay: 10,
      time: 1000
    });
});
</script>
@endsection