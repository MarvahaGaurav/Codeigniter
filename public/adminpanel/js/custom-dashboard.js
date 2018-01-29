$(function() {

    // =========== Chart ===========
    if($('#chart1').length){
    Highcharts.chart('chart1', {

        chart: {
            type: 'line',
             backgroundColor: 'transparent'
        },

        title: {
            text: false
        },

        xAxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: false
            }
        },

        // legend: {
        //     layout: 'vertical',
        //     align: 'right',
        //     verticalAlign: 'top'
        // },

        credits: {
            enabled: false
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                this.series.name + ': ' + this.y + '<br/>' +
                'Total: ' + this.point.stackTotal;
            }
        },

        exporting: {
               enabled: false
        },

        series: [{
            // showInLegend: false,
            //name: '10am - 12pm (Morning)',
            data: [2000, 8000, 8000, 800, 1000, 3000, 2500],     
            color: '#53c4ce',   
        }, {
            // showInLegend: false,
            //name: '12pm - 4pm (Afternoon)',
            data: [400, 2000, 800, 4000, 500, 5000, 300],  
            color: '#ff4942',
        }, {
            // showInLegend: false,
            //name: '4pm < (Evening)',
            data: [400, 2000, 800, 4000, 500, 5000, 300],  
            color: '#ff4942',    
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
 }  
    
    // =========== Chart ===========
    
     if($('#chart2').length){
    Highcharts.chart('chart2', {

        chart: {
            type: 'line',
             backgroundColor: 'transparent'
        },

        title: {
            text: false
        },

        xAxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: false
            }
        },

        // legend: {
        //     layout: 'vertical',
        //     align: 'right',
        //     verticalAlign: 'top'
        // },

        credits: {
            enabled: false
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                this.series.name + ': ' + this.y + '<br/>' +
                'Total: ' + this.point.stackTotal;
            }
        },

        exporting: {
               enabled: false
        },

        series: [{
            // showInLegend: false,
            //name: '10am - 12pm (Morning)',
            data: [2000, 8000, 8000, 800, 1000, 3000, 2500],     
            color: '#53c4ce',   
        }, {
            // showInLegend: false,
            //name: '12pm - 4pm (Afternoon)',
            data: [400, 2000, 800, 4000, 500, 5000, 300],  
            color: '#ff4942',
        }, {
            // showInLegend: false,
            //name: '4pm < (Evening)',
            data: [400, 2000, 800, 4000, 500, 5000, 300],  
            color: '#ff4942',    
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
    }
});


$(document).ready(function(){

    // Date Picker
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    // =============== Linked Datepicker =============== //
      if($('#dpd1').length){
    var add_start = $('#dpd1').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() < add_end.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            add_end.setValue(newDate);
            add_start.hide();
        }
        add_start.hide();
        $('#dpd2')[0].focus();
    }).data('datepicker');
    }
    
      if($('#dpd2').length){
    var add_end = $('#dpd2').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() < add_start.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        add_end.hide();
    }).data('datepicker');
  }

    // =============== Linked Datepicker =============== //
    
     if($('#dpd3').length){
    var add_start = $('#dpd3').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() < add_end.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            add_end.setValue(newDate);
            add_start.hide();
        }
        add_start.hide();
        $('#dpd4')[0].focus();
    }).data('datepicker');
  }
      if($('#dpd4').length){
    var add_end = $('#dpd4').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() < add_start.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        add_end.hide();
    }).data('datepicker');
    }
});