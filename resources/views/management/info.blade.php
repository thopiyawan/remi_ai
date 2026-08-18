@extends('layouts.app')

@section('script')
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>

<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="//www.amcharts.com/lib/3/plugins/responsive/responsive.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />


<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
<link rel="stylesheet" href="{{URL::asset('css/stylecss_test.css')}}">

<!-- blood page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<!-- edit -->
<link rel="stylesheet" href="<?php echo asset('css/redesign.css')?>" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">  
<script src="<?php echo asset('js/redesign.js')?>"></script>

<!-- bar -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.11/flatpickr.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.11/flatpickr.min.js"></script>
    
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<style>
	.chartdiv {
	width: 100%;
	height: 500px;
	}
  .chart2div {
	width		: 100%;
	height		: 300px;
	font-size	: 11px;
  }

	g.amcharts-category-axis tspan {
	cursor: pointer;
	}

	g.amcharts-category-axis text.amcharts-axis-label tspan:hover,
	g.amcharts-graph-label-only text tspan {
	text-decoration: underline;
	fill: red;
	}

	text.amcharts-axis-title {
	font-size: 13px;
	}

  .flex{
    display:flex;
    justify-content:center;
    align-items:center;

    }

    .btn{
      /* padding: 10px 18px;   */
      margin: 10px 5px;   
    }
   

        h1 {
            margin-bottom: 10px;
        }

        #dateRange {
            width: 200px;
            padding: 8px;
        }

        canvas {
            max-width: 800px;
            margin-top: 20px;
        }

</style>
<script type="text/javascript">

var bmi_under = '[{"week":1,"fromValue":0,"toValue":0 },{"week":2,"fromValue":0.07,"toValue":0.105 },'+
      '{"week":3,"fromValue":0.14,"toValue":0.21 },{"week":4,"fromValue":0.21,"toValue":0.315 },'+
      '{"week":5,"fromValue":0.28,"toValue":0.42 },{"week":6,"fromValue":0.35,"toValue":0.525 },'+
      '{"week":7,"fromValue":0.42,"toValue":0.63 },{"week":8,"fromValue":0.49,"toValue":0.735 },'+
      '{"week":9,"fromValue":0.56,"toValue":0.84 },{"week":10,"fromValue":0.63,"toValue":0.945 },'+
      '{"week":11,"fromValue":0.7,"toValue":1.05 },{"week":12,"fromValue":1.06,"toValue":1.565 },'+
      '{"week":13,"fromValue":1.42,"toValue":2.08 },{"week":14,"fromValue":1.78,"toValue":2.595 },'+
      '{"week":15,"fromValue":2.14,"toValue":3.11 },{"week":16,"fromValue":2.5,"toValue":3.625 },'+
      '{"week":17,"fromValue":2.86,"toValue":4.14 },{"week":18,"fromValue":3.22,"toValue":4.655 },'+
      '{"week":19,"fromValue":3.58,"toValue":5.17 },{"week":20,"fromValue":4.06,"toValue":5.85 },'+
      '{"week":21,"fromValue":4.54,"toValue":6.53 },{"week":22,"fromValue":5.02,"toValue":7.21 },'+
      '{"week":23,"fromValue":5.5,"toValue":7.89 },{"week":24,"fromValue":5.98,"toValue":8.57 },'+
      '{"week":25,"fromValue":6.46,"toValue":9.25 },{"week":26,"fromValue":6.94,"toValue":9.93 },'+
      '{"week":27,"fromValue":7.42,"toValue":10.61 },{"week":28,"fromValue":7.9,"toValue":11.29 },'+
      '{"week":29,"fromValue":8.38,"toValue":11.97 },{"week":30,"fromValue":8.74,"toValue":12.485 },'+
      '{"week":31,"fromValue":9.1,"toValue":13 },{"week":32,"fromValue":9.46,"toValue":13.515 },'+
      '{"week":33,"fromValue":9.82,"toValue":14.03 },{"week":34,"fromValue":10.18,"toValue":14.545 },'+
      '{"week":35,"fromValue":10.54,"toValue":15.06 },{"week":36,"fromValue":10.89,"toValue":15.58 },'+
      '{"week":37,"fromValue":11.24,"toValue":16.1 },{"week":38,"fromValue":11.62,"toValue":16.62 },'+
      '{"week":39,"fromValue":12,"toValue":17.14 },{"week":40 }]';

var bmi_normal = '[{"week":1,"fromValue":0,"toValue":0},{"week":2,"fromValue":0.065,"toValue":0.09 },'+
        '{"week":3,"fromValue":0.13,"toValue":0.18 },{"week":4,"fromValue":0.195,"toValue":0.27 },'+
        '{"week":5,"fromValue":0.26,"toValue":0.36 },{"week":6,"fromValue":0.325,"toValue":0.45},'+
        '{"week":7,"fromValue":0.39,"toValue":0.54 },{"week":8,"fromValue":0.455,"toValue":0.63 },'+
        '{"week":9,"fromValue":0.52,"toValue":0.72 },{"week":10,"fromValue":0.585,"toValue":0.81 },'+
        '{"week":11,"fromValue":0.65,"toValue":0.9 },{"week":12,"fromValue":0.97,"toValue":1.35 },'+
        '{"week":13,"fromValue":1.29,"toValue":1.8 },{"week":14,"fromValue":1.61,"toValue":2.25 },'+
        '{"week":15,"fromValue":1.93,"toValue":2.7 },{"week":16,"fromValue":2.25,"toValue":3.15 },'+
        '{"week":17,"fromValue":2.57,"toValue":3.6 },{"week":18,"fromValue":2.89,"toValue":4.1  },'+
        '{"week":19,"fromValue":3.21,"toValue":4.60 },{"week":20,"fromValue":3.635,"toValue":5.15 },'+
        '{"week":21,"fromValue":4.06,"toValue":5.7 },{"week":22,"fromValue":4.485,"toValue":6.3 },'+
        '{"week":23,"fromValue":4.91,"toValue":6.9 },{"week":24,"fromValue":5.335,"toValue":7.5 },'+
        '{"week":25,"fromValue":5.76,"toValue":8.1 },{"week":26,"fromValue":6.185,"toValue":8.7 },'+
        '{"week":27,"fromValue":6.61,"toValue":9.3 },{"week":28,"fromValue":7.035,"toValue":9.9},'+
        '{"week":29,"fromValue":7.46,"toValue":10.5 },{"week":30,"fromValue":7.785,"toValue":10.95 },'+
        '{"week":31,"fromValue":8.11,"toValue":11.4 },{"week":32,"fromValue":8.435,"toValue":11.85 },'+
        '{"week":33,"fromValue":8.76,"toValue":12.3 },{"week":34,"fromValue":8.76,"toValue":12.75 },'+
        '{"week":35,"fromValue":9.41,"toValue":13.2 },{"week":36,"fromValue":9.735,"toValue":13.65 },'+
        '{"week":37,"fromValue":10.06,"toValue":14.1 },{"week":38,"fromValue":10.385,"toValue":14.55},'+
        '{"week":39,"fromValue":10.71,"toValue":15 },{"week":40,"fromValue":10.71,"toValue":15 }]';
    
var bmi_over = '[{"week":1,"fromValue":0,"toValue":0},{"week":2,"fromValue":0.04,"toValue":0.065},'+
        '{"week":3,"fromValue":0.08,"toValue":0.13 },{"week":4,"fromValue":0.12,"toValue":0.195 },'+
        '{"week":5,"fromValue":0.16,"toValue":0.26 },{"week":6,"fromValue":0.2,"toValue":0.325 },'+
        '{"week":7,"fromValue":0.24,"toValue":0.39 },{"week":8,"fromValue":0.28,"toValue":0.4 },'+
        '{"week":9,"fromValue":0.32,"toValue":0.41 },{"week":10,"fromValue":0.36,"toValue":0.465 },'+
        '{"week":11,"fromValue":0.4,"toValue":0.52 },{"week":12,"fromValue":0.595,"toValue":0.845 },'+
        '{"week":13,"fromValue":0.79,"toValue":1.17 },{"week":14,"fromValue":0.985,"toValue":1.495 },'+
        '{"week":15,"fromValue":1.18,"toValue":1.82 },{"week":16,"fromValue":1.375,"toValue":2.145 },'+
        '{"week":17,"fromValue":1.57,"toValue":2.47 },{"week":18,"fromValue":1.765,"toValue":2.795 },'+
        '{"week":19,"fromValue":1.96,"toValue":3.12 },{"week":20,"fromValue":2.21,"toValue":3.55 },'+
        '{"week":21,"fromValue":2.46,"toValue":3.98 },{"week":22,"fromValue":2.71,"toValue":4.41 },'+
        '{"week":23,"fromValue":2.96,"toValue":4.84 },{"week":24,"fromValue":3.21,"toValue":5.27 },'+
        '{"week":25,"fromValue":3.46,"toValue":5.7 },{"week":26,"fromValue":3.71,"toValue":6.12 },'+
        '{"week":27,"fromValue":3.96,"toValue":6.56 },{"week":28,"fromValue":4.21,"toValue":6.99 },'+
        '{"week":29,"fromValue":4.46,"toValue":7.42 },{"week":30,"fromValue":4.655,"toValue":7.745 },'+
        '{"week":31,"fromValue":4.85,"toValue":8.07 },{"week":32,"fromValue":5.045,"toValue":8.395 },'+
        '{"week":33,"fromValue":5.24,"toValue":8.72 },{"week":34,"fromValue":5.435,"toValue":9.05 },'+
        '{"week":35,"fromValue":5.63,"toValue":9.38 },{"week":36,"fromValue":5.825,"toValue":9.71 },'+
        '{"week":37,"fromValue":6.02,"toValue":10.04 },{"week":38,"fromValue":6.22,"toValue":10.37 },'+
        '{"week":39,"fromValue":6.42,"toValue":10.7 },{"week":40,"fromValue":6.42,"toValue":10.7 }]';
          

      var bmi = {{$bmi}};

      if(this.bmi < 18.5){
          var bmis = JSON.parse(bmi_under);
      }
      if(this.bmi >= 18.5 && this.bmi <= 24.9){
        var bmis = JSON.parse(bmi_normal);
      }
      if(this.bmi > 24.9){
        var bmis = JSON.parse(bmi_over);
      }
   @foreach ($record1 as $records1)
       var preg_week = <?php echo $preg_week; ?>;
       var all_weight = <?php echo $preg_weight; ?>;
       var weight_pregnancy = {{ $records1->user_Pre_weight}};
      
  
         
        for(i=0; i<bmis.length ; i++){
          var b = bmis[i];
        
          for(var w in this.all_weight){
            
            if(b.week == this.preg_week[w]  ){
              // b.value = Math.abs(this.all_weight[w]-this.weight_pregnancy);
              b.value = this.all_weight[w]-this.weight_pregnancy;
              b.value = b.value.toFixed(2);
            }
          } 
       }
    
    @endforeach
     // console.log(JSON.stringify(bmis));     
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "autoMarginOffset":20,
    "marginRight":80,
    "dataProvider":  bmis,
    // "valueAxes": [{
    //     "axisAlpha": 0,
    //     "position": "left",
    //      "title": "สัดส่วนน้ำหนัก"

    // }],
    "graphs": [{
        "id": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": false,
        "valueField": "fromValue",
        "fillAlphas": 0
    }, {
        "fillAlphas": 0.2,
        "fillToGraph": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": false,
        "valueField": "toValue"
    }, {
        "valueField": "value",
        "balloonText":"<div style='margin:5px; text-align:left'><span style='font-size:18px'>Value:[[value]]</span></div>",
        "fillAlphas": 0,
        "bullet": "round",
        "bulletSize": 8,

    }],
    "chartCursor": {
        "fullWidth": true,
        "cursorAlpha": 0.05,
        "valueLineEnabled":true,
        "valueLineAlpha":0.5,
        "valueLineBalloonEnabled":true
    },
    "dataDateFormat": "YYYY-MM-DD",
    "categoryField": "week",
    "chartScrollbar":{


    },
		"allLabels": [{
			  "text": "สัดส่วนน้ำหนัก (กิโลกรัม)",
			  "bold": false,
			  "x": 10,
			  "y": "50%",
			  "rotation": 270,
			  "width": "100%",
			  "align": "middle"
			},{
			  "text": "อายุครรภ์ (สัปดาห์)",
			  "bold": false,
			  "x": '50%',
			  "y":480,
			  "rotation": 0,
			  "width": "100%",
			  "align": "middle"
			}],
      "export": {
        "enabled": true,
        "fileName": "บันทึกน้ำหนัก "+moment().format("DD-MM-YYYY"),
        "exportFields": [
            "week",
            "fromValue",
            "toValue",
            "value"
        ],
        "columnNames": {
            "week": "สัปดาห์",
            "fromValue": "fromValue",
            "toValue": "toValue",
            "value": "น้ำหนัก",
        },
        "menuReviver": function(item,li) {
          if (item.format == "XLSX") {
            item.name = moment().format("DD-MM-YYYY");
          }
          return li;
        }
      }
});

//blood sugar-----------------------------------------
var data_blood_level =[
     @foreach ($graphdata as $graphdatas)
		  {"เวลา": "{{date('Y-m-d H:i', strtotime($graphdatas->datetime));}}",
		  "ระดับน้ำตาล": "{{$graphdatas->blood_sugar}}",
      "ช่วงการวัด" : "{{$graphdatas->time_of_day}}",
      "มื้อ" : "{{$graphdatas->meal}}"},
     @endforeach];
        
// console.log(data_blood_level);
var colors = [  @foreach ($graphdata as $graphdatas)
             <?php 
             $a=array();

             switch ($graphdatas->meal) {
              case "4":
                if($graphdatas->meal=='4'&& $graphdatas->blood_sugar>120){
                  $color = "rgb(250, 128, 114)";
                }else{
                  $color = "rgba(64, 224, 208)";
                }  
                break;
              default:
              if(($graphdatas->time_of_day =='1' && $graphdatas->blood_sugar>95)||($graphdatas->time_of_day== '3' && $graphdatas->blood_sugar>140)||($graphdatas->time_of_day=='4' && $graphdatas->blood_sugar>120)){
                $color = "rgb(250, 128, 114)";
              }else{
                $color = "rgba(64, 224, 208)";
              }  
            }            
                   echo $a[] = '"'.$color.'"'.',';
              // echo json_encode(array_push($a,$color));
              ?>
              @endforeach  ]
// console.log(colors);

var bullet = ["diamond","square","xError", "round", "triangleUp"];
var bullet_des = ["-","ก่อนอาหาร", "ก่อนนอน", "หลังอาหาร 1 ชม.", "หลังอาหาร 2 ชม."];
var bullet_meal = ["-","เช้า","กลางวัน", "เย็น", "ก่อนนอน"];

var chart1 = AmCharts.makeChart("chart1div", {
    "type": "serial",
    "marginTop":0,
    "marginRight": 80,
    "dataProvider": [
		@foreach ($graphdata as $graphdatas)
   		  { 
          "date": "{{date('Y-m-d H:i', strtotime($graphdatas->datetime));}}",
          "glucose_level": "{{$graphdatas->blood_sugar}}",
          "bullet": bullet["{{$graphdatas->time_of_day}}"], 
          "bullet_des": bullet_des["{{$graphdatas->time_of_day}}"],
          "bullet_meal": bullet_meal["{{$graphdatas->time_of_day}}"],
          "color":(("{{$graphdatas->meal}}"=='4'&& "{{$graphdatas->blood_sugar}}">120)|| (("{{$graphdatas->time_of_day}}" =='1' && "{{$graphdatas->blood_sugar>95}}")||("{{$graphdatas->time_of_day}}"== '3' && "{{$graphdatas->blood_sugar>140}}")||("{{$graphdatas->time_of_day}}"=='4' && "{{$graphdatas->blood_sugar>120}}"))) ? '#FF3131' : (("{{$graphdatas->blood_sugar}}"<60) ? '#FADA5E' : '#50C878'),
          "status":(("{{$graphdatas->meal}}"=='4'&& "{{$graphdatas->blood_sugar}}">120)|| (("{{$graphdatas->time_of_day}}" =='1' && "{{$graphdatas->blood_sugar>95}}")||("{{$graphdatas->time_of_day}}"== '3' && "{{$graphdatas->blood_sugar>140}}")||("{{$graphdatas->time_of_day}}"=='4' && "{{$graphdatas->blood_sugar>120}}"))) ? "เกินเกณฑ์" : (("{{$graphdatas->blood_sugar}}"<60) ? "ต่ำกว่าเกณฑ์" : "ปกติ")
        }, 
    @endforeach ],
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left",
        "guides": [{
            "dashLength": 6,
            "inside": true,
            "label": "140 หลัง 1 ชม.",
            "lineAlpha": 1,
            "value": 140
        },
        {
            "dashLength": 6,
            "inside": true,
            "label": "120 หลัง 2 ชม.",
            "lineAlpha": 1,
            "value": 120
        },
        {
            "dashLength": 6,
            "inside": true,
            "label": "95 ก่อนอาหาร",
            "lineAlpha": 1,
            "value": 95
        }],
    }],
    "graphs": [{
        "id":"g1",
        "balloonText": "</b><span style='font-size:14px;'>[[bullet_des]]</span></b><br>[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
        "bullet": "round",
        "fillColors": "#6BCB77",
        "valueField": "glucose_level",
        "bulletSize": 15,
        "lineColor": "#D3D3D3",
        "lineThickness": 2,
        // "negativeBase": 100, 
        // "type": "smoothedLine",
        "visibleInLegend": false,
        "bulletField": "bullet",
        "bulletSizeField": "bulletSize",
        "fillColorsField": "fillColors",
        "colorField":"color"
    }],
    "chartScrollbar": {
        "graph":"g1",
        "gridAlpha":0,
        "color":"#888888",
        "scrollbarHeight":20,
        "backgroundAlpha":0,
        "selectedBackgroundAlpha":0.1,
        "selectedBackgroundColor":"#888888",
        "graphFillAlpha":0,
        "autoGridCount":false,
        "selectedGraphFillAlpha":0,
        "graphLineAlpha":0.2,
        "graphLineColor":"#c2c2c2",
        "selectedGraphLineColor":"#888888",
        "selectedGraphLineAlpha":15
    },
    "chartCursor": {
        "categoryBalloonDateFormat": "YYYY-MM-DD JJ:NN",
        "cursorAlpha": 0,
        "valueLineEnabled":true,
        "valueLineBalloonEnabled":true,
        "valueLineAlpha":0.5,
        "fullWidth":true
    },
    "dataDateFormat": "YYYY-MM-DD JJ:NN",
    "categoryField": "date",
    "categoryAxis": {
        "minPeriod": "mm",
        "parseDates": true,
        "minorGridAlpha": 0.1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true,
        "fileName": "บันทึกน้ำตาลในเลือด"+moment().format("DD-MM-YYYY"),
        "menuReviver": function(item,li) {
          if (item.format == "XLSX") {
            item.name = moment().format("DD-MM-YYYY");
          }
          return li;
        },
        "exportFields": [
            "date",
            "glucose_level",
            "bullet_des",
            "bullet_meal",
            "status"
        ],
        "columnNames": {
            "date": "วันที่บันทึก",
            "glucose_level": "ค่าระดับน้ำตาล",
            "bullet_meal": "มื้ออาหาร",
            "bullet_des": "ช่วงการวัด",
            "status": "สถานะการประเมิน"
        }
    },
});
chart1.addListener("rendered", zoomChart);
if(chart1.zoomChart){
	chart1.zoomChart();
}

function zoomChart(){
  chart1.zoom(moment(new Date(2017, 2, 10) , "YYYY-MM-DDTHH:mm:ss.SSS[Z]").subtract(2,'d'),moment(new Date(), "YYYY-MM-DDTHH:mm:ss.SSS[Z]"));
}
function getFirstDayOfWeek(d) {
  const date = new Date(d);
  const day = date.getDay(); // 👉️ get day of week
  const diff = date.getDate() - day + (day === 0 ? -6 : 1);
  return new Date(date.setDate(diff));
}
  var a =  [
    @foreach ($blood_sugar as $bs)
   		  { 
          "date": "{{$bs->datetime}}",
          "status_lable": "{{$bs->status_lable}}",
          "week": moment(getFirstDayOfWeek(new Date("{{$bs->datetime}}"))).format('D/MMM/YYYY')+' to '+moment(new Date(getFirstDayOfWeek("{{$bs->datetime}}").setDate(getFirstDayOfWeek("{{$bs->datetime}}").getDate() + 6))).format('D/MMM/YYYY'),        
        },  
    @endforeach 
  
  ]

  const res = a.reduce((acc, obj) => {
  const existingIndex = acc.findIndex(
    el => el.status_lable === obj.status_lable && el.week === obj.week
  )
  if (existingIndex > -1) {
    acc[existingIndex].count += 1
  } else {
    acc.push({
      status_lable: obj.status_lable,
      week: obj.week ,
      count: 1
    })
  }
  return acc
}, [])
// console.log(res);

var arr=[];  

for (var i =1; i < res.length; ++ i) {
const data2 = res.map(item => {
  let predefined = {};
  
  if (item.week === res[i]['week']) predefined.week = item.week;
  if (item.week === res[i]['week']){
    // predefined.week = item.week
    if (item.status_lable  === 'LOWต่ำกว่าเกณฑ์') predefined.LOW = item.count;
    if (item.status_lable  === 'NORMALปกติ') predefined.NORMAL = item.count;
    if (item.status_lable  === 'HIGHเกินเกณฑ์') predefined.HIGH = item.count;
  }
  arr.push(predefined);
})}

let resarr = {};
arr.forEach(a => resarr[a.week] = {...resarr[a.week], ...a});
resarr = Object.values(resarr);
const result = resarr.filter(element => {
  if (Object.keys(element).length !== 0) {
    return true;
  }
  return false;
});

var chart3 = AmCharts.makeChart("chart2div", {
  "type": "serial",
	"theme": "none",
    "legend": {
      "horizontalGap": 10,
      "maxColumns": 1,
      "position": "right",
      "useGraphSettings": true,
      "markerSize": 10

    },
    "dataProvider": result,
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "labelsEnabled": false,
        "position": "left"
    }],
    "chartScrollbar": {
        "graph":"g1",
        "gridAlpha":0,
        "color":"#888888",
        "scrollbarHeight":20,
        "backgroundAlpha":0,
        "selectedBackgroundAlpha":0.1,
        "selectedBackgroundColor":"#888888",
        "graphFillAlpha":0,
        "autoGridCount":false,
        "selectedGraphFillAlpha":0,
        "graphLineAlpha":0.2,
        "graphLineColor":"#c2c2c2",
        "selectedGraphLineColor":"#888888",
        "selectedGraphLineAlpha":15
    },
    "graphs": [
      {
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "LOW",
        "type": "column",
		    "color": "#000000",
        "fillColors": "#FADA5E",
        "valueField": "LOW",
      }, 
      {
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "NORMAL",
        "type": "column",
        "color": "#000000",
        "fillColors": "#50C878",
        "valueField": "NORMAL"
    }, {
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.1,
        "title": "HIGH",
        "type": "column",
        "color": "#000000",
        "fillColors": "#FF3131",
        "valueField": "HIGH"
    }
  ],
    "categoryField": "week",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "position": "left"
    },
    "export": {
        "enabled": true,
        "fileName": "สรุปค่าระดับน้ำตาลในเลือดรายสัปดาห์"+moment().format("DD-MM-YYYY"),
        "exportFields": [
            "week",
            "LOW",
            "HIGH",
            "NORMAL"
        ],
        "columnNames": {
            "week": "สัปดาห์",
            "LOW": "ต่ำกว่าเกณฑ์",
            "NORMAL": "ปกติ",
            "HIGH": "เกินเกณฑ์",
        },
        "menuReviver": function(item,li) {
          if (item.format == "XLSX") {
            item.name = moment().format("DD-MM-YYYY");
          }
          return li;
        }
    },
});

// end-----------


document.addEventListener('DOMContentLoaded', function() {
      function filteredcateg(item,categ,group) {

        var category = filteredData.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})

        return res;
        
    }

    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
            sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }
    // Sample data
    var data = [
        @foreach ($graphbar as $graphbars)
   		{ 
          "date": "{{$graphbars['date']}}",
          "category": "{{$graphbars['category']}}",
          "status": "{{$graphbars['status']}}", 
        }, 
       @endforeach
    ];

    
    // console.log(data);
    // Initialize date range picker
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            // When dates are selected, update the chart
            updateChart(selectedDates);
        }
    });

    // Initialize the chart
    var chart = am4core.create("barchart", am4charts.XYChart);

    function filteredcateg(item,categ,group) {
        var category = data.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})
        return res;
    }

    function sum( obj ) {
            var sum = 0;
            for( var el in obj ) {
                if( obj.hasOwnProperty( el ) ) {
                sum += parseFloat( obj[el] );
                }
            }
            return sum;
    }

    for (var i = 0; i < data.length; i++) {
            var item = data[i];
            var categ = item['category'];
            res1 = filteredcateg(item, categ ='เช้า-ก่อน');
            res2 = filteredcateg(item, categ ='เช้า-หลัง');
            res3 = filteredcateg(item, categ ='กลางวัน-ก่อน');
            res4 = filteredcateg(item, categ ='กลางวัน-หลัง');
            res5 = filteredcateg(item, categ ='เย็น-ก่อน');
            res6 = filteredcateg(item, categ ='เย็น-หลัง');
            res7 = filteredcateg(item, categ ='รวม');
            var summed_res1 = sum(res1);
            var summed_res2 = sum(res2);
            var summed_res3 = sum(res3);
            var summed_res4 = sum(res4);
            var summed_res5 = sum(res5);
            var summed_res6 = sum(res6);
            var sum_total = summed_res1+summed_res2+summed_res3+summed_res4+summed_res5+summed_res6;
            var cate_sum = data.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
            }, {})
            // console.log(cate_sum);
            chart.data = [{
                "category": "เช้า-ก่อน",
                "group1": ((res1['low']/summed_res1)*100).toFixed(2),
                "group2": ((res1['normal']/summed_res1)*100).toFixed(2),
                "group3": ((res1['high']/summed_res1)*100).toFixed(2),

            },
            {
                "category": "เช้า-หลัง",
                "group1": ((res2['low']/summed_res2)*100).toFixed(2) ,
                "group2": ((res2['normal']/summed_res2)*100).toFixed(2),
                "group3": ((res2['high']/summed_res2)*100).toFixed(2),
 
            },
            {
                "category": "กลางวัน-ก่อน",
                "group1": ((res3['low']/summed_res3)*100).toFixed(2) ,
                "group2": ((res3['normal']/summed_res3)*100).toFixed(2),
                "group3": ((res3['high']/summed_res3)*100).toFixed(2),
     
            },
            {
                "category": "กลางวัน-หลัง",
                "group1": ((res4['low']/summed_res4)*100).toFixed(2) ,
                "group2": ((res4['normal']/summed_res4)*100).toFixed(2),
                "group3": ((res4['high']/summed_res4)*100).toFixed(2),
            },
            {
                "category": "เย็น-ก่อน",
                "group1": ((res5['low']/summed_res5)*100).toFixed(2) ,
                "group2": ((res5['normal']/summed_res5)*100).toFixed(2) ,
                "group3": ((res5['high']/summed_res5)*100).toFixed(2),
            },
            {
                "category": "เย็น-หลัง",
                "group1": ((res6['low']/summed_res6)*100).toFixed(2) ,
                "group2": ((res6['normal']/summed_res6)*100).toFixed(2),
                "group3": ((res6['high']/summed_res6)*100).toFixed(2),
            },
            {
                "category": "รวม",
                "group1": ((cate_sum['low']/sum_total)*100).toFixed(2) ,
                "group2": ((cate_sum['normal']/sum_total)*100).toFixed(2),
                "group3": ((cate_sum['high']/sum_total)*100).toFixed(2),
            },
            
            
        ]; };

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "category";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.line.strokeOpacity = 1;
    categoryAxis.renderer.minGridDistance = 30;
    categoryAxis.renderer.cellStartLocation = 0.2;
    categoryAxis.renderer.cellEndLocation = 0.8;

    categoryAxis.renderer.inside = true;
    categoryAxis.renderer.labels.template.valign = "top";
    categoryAxis.renderer.labels.template.fontSize = 13;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.grid.template.disabled = true;
        valueAxis.renderer.labels.template.disabled = true;
    chart.colors.list = [
            am4core.color("#ffde7b"),
            am4core.color("#1DC9A0"),
            am4core.color("#ff5162"),
            ];
           
    var createSeries = function(field, name, stacked) {
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.valueY = field;
        series.dataFields.categoryX = "category";
        series.name = name;
        series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/] %";
        series.stacked = stacked;
        series.columns.template.width = am4core.percent(95);

        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.label.text = "{valueY} %";
        labelBullet.label.fill = am4core.color("#fff");
        labelBullet.locationY = 0.5;
        labelBullet.label.hideOversized = true;

        return series;
    };

    var group1Series = createSeries("group1", "low", true);
    var group2Series = createSeries("group2", "normal", true);
    var group3Series = createSeries("group3", "high", true);

    // Add legend
    chart.legend = new am4charts.Legend();

    // Update function to handle date range selection
    function updateChart(selectedDates) {
        var startDate = new Date(selectedDates[0]);
        var endDate = new Date(selectedDates[1]);

        var filteredData = data.filter(function(item) {
            var date = new Date(item.date);
            return date >= startDate && date <= endDate;
        });
    
    function filteredcateg(item,categ,group) {
        var category = filteredData.filter(function(item) {
            var category = item.category;
            return category == categ;
        });
        var res = category.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
        }, {})
        return res;
    }
    function sum( obj ) {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
            sum += parseFloat( obj[el] );
            }
        }
        return sum;
    }
        var newData = [];
        for (var i = 0; i < filteredData.length; i++) {
            var item = filteredData[i];
            var categ = item['category'];
            
           
            res1 = filteredcateg(item, categ ='เช้า-ก่อน');
            res2 = filteredcateg(item, categ ='เช้า-หลัง');
            res3 = filteredcateg(item, categ ='กลางวัน-ก่อน');
            res4 = filteredcateg(item, categ ='กลางวัน-หลัง');
            res5 = filteredcateg(item, categ ='เย็น-ก่อน');
            res6 = filteredcateg(item, categ ='เย็น-หลัง');
            res7 = filteredcateg(item, categ ='รวม');
           
            var summed_res1 = sum(res1);
            var summed_res2 = sum(res2);
            var summed_res3 = sum(res3);
            var summed_res4 = sum(res4);
            var summed_res5 = sum(res5);
            var summed_res6 = sum(res6);
            var sum_total = summed_res1+summed_res2+summed_res3+summed_res4+summed_res5+summed_res6;
            // var sum_total_g2 = sum(res6);
            // var sum_total_g3 = sum(res6);

            var cate_sum = filteredData.reduce(function(obj, v) {
            obj[v.status] = (obj[v.status] || 0) + 1;
            return obj;
            }, {})
            // console.log(cate_sum);
                
 

            newData.push({
                "category": "เช้า-ก่อน",
                "group1": ((res1['low']/summed_res1)*100).toFixed(2),
                "group2": ((res1['normal']/summed_res1)*100).toFixed(2),
                "group3": ((res1['high']/summed_res1)*100).toFixed(2),

            },
            {
                "category": "เช้า-หลัง",
                "group1": ((res2['low']/summed_res2)*100).toFixed(2) ,
                "group2": ((res2['normal']/summed_res2)*100).toFixed(2),
                "group3": ((res2['high']/summed_res2)*100).toFixed(2),
 
            },
            {
                "category": "กลางวัน-ก่อน",
                "group1": ((res3['low']/summed_res3)*100).toFixed(2) ,
                "group2": ((res3['normal']/summed_res3)*100).toFixed(2),
                "group3": ((res3['high']/summed_res3)*100).toFixed(2),
     
            },
            {
                "category": "กลางวัน-หลัง",
                "group1": ((res4['low']/summed_res4)*100).toFixed(2) ,
                "group2": ((res4['normal']/summed_res4)*100).toFixed(2),
                "group3": ((res4['high']/summed_res4)*100).toFixed(2),
            },
            {
                "category": "เย็น-ก่อน",
                "group1": ((res5['low']/summed_res5)*100).toFixed(2) ,
                "group2": ((res5['normal']/summed_res5)*100).toFixed(2) ,
                "group3": ((res5['high']/summed_res5)*100).toFixed(2),
            },
            {
                "category": "เย็น-หลัง",
                "group1": ((res6['low']/summed_res6)*100).toFixed(2) ,
                "group2": ((res6['normal']/summed_res6)*100).toFixed(2),
                "group3": ((res6['high']/summed_res6)*100).toFixed(2),
            },
            {
                "category": "รวม",
                "group1": ((cate_sum['low']/sum_total)*100).toFixed(2) ,
                "group2": ((cate_sum['normal']/sum_total)*100).toFixed(2),
                "group3": ((cate_sum['high']/sum_total)*100).toFixed(2),
            },
            
            );
        }
        chart.data = newData;
    }
});

    </script>
</script>
   
@endsection
@section('content')

<div class="container info">
  <div class="row justify-content-center">
    <div class="col-md-4">
			<?php 
        if($mom_info->compli_diabete == 1 ){
          $compli_diabete = 'มีภาวะแทรกซ้อน';
        }else{
          $compli_diabete ='-';
        }

        if($mom_info->compli_hypertension == 1 ){
            $compli_hypertension = 'มีภาวะแทรกซ้อน';
        }else{
            $compli_hypertension ='-';
        }

        if($mom_info->compli_preterm_birth== 1 ){
            $compli_preterm_birth = 'มีภาวะแทรกซ้อน';
        }else{
            $compli_preterm_birth ='-';
        } 
      ?>
      <div class="card web bg-primary">
        <div class="wrap-profile">
          <img class="profile" src="<?php echo asset("image/ava1.png")?>" />
          <h3>{{ $mom_info->user_name }}</h3>
          <p>เลขประจำตัว(HN) : {{ $mom_info->hospital_num }} </p>
          <p>อายุ : {{ $mom_info->user_age }} ปี | อายุครรภ์ : {{ $mom_info->preg_week }} สัปดาห์</p>
          <p>กำหนดการคลอด : {{ $mom_info->due_date }}</p>
        </div>
        <div class="sub-head"> 
          <h5>>> ข้อมูลทั่วไป</h5>
        </div>
        <p><i class="fa-solid fa-arrows-up-down"></i> : {{ $mom_info->user_height }} เซนติเมตร </p>
        <p><i class="fa-solid fa-weight-scale"></i> : (ก่อน) {{ $mom_info->user_Pre_weight }} กิโลกรัม | (ปัจจุบัน) {{ $mom_info->user_weight }} กิโลกรัม</p>
        <p><i class="fa-solid fa-phone"></i> : {{  $mom_info->phone_number}}</p>
        <p><i class="fa-solid fa-envelope"></i> : {{  $mom_info->email}}</p>
        <p><i class="fa-solid fa-house-chimney-medical"></i> : {{  $mom_info->hospital_name}}</p>
        <p><i class="fa-solid fa-wheat-awn-circle-exclamation"></i> : {{ $mom_info->history_food }} </p>

        <div class="sub-head"> 
          <h5>>> ภาวะแทรกซ้อนระหว่างตั้งครรภ์</h5>
        </div>
        <p>เบาหวาน : {{ $compli_diabete }} </p>
        <p>ความดันสูง : {{ $compli_hypertension }} </p>
        <p>เจ็บครรภ์คลอดก่อนกำหนด : {{ $compli_preterm_birth }} </p>
        </div>
        <div class="card add-hn">
        <form method="post" action="{{url('hnnumber_save')}}">
          {{ csrf_field() }}
          <h4 class="txt-primary">เพิ่มเลขประจำตัว (HN)</h4>
          <input type="text" name="hn_number" value="{{ $mom_info->hospital_num }}" />
          <input type="hidden" name="user_id" value="{{ $mom_info->user_id }}" />
          <button type="submit" class="btn btn-primary w-100">บันทึก HN Number</button>
        </form>
      </div>
      

			</div>
        <div class="col-md-8">
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">น้ำหนัก</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">อาหาร</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">วิตามิน</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-exercise-tab" data-toggle="pill" href="#pills-exercise" role="tab" aria-controls="pills-exercise" aria-selected="false">ออกกำลังกาย</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-exercise-tab" data-toggle="pill" href="#pills-blood" role="tab" aria-controls="pills-blood" aria-selected="false">ค่าน้ำตาล</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-exercise-tab" data-toggle="pill" href="#pills-fetalmovement" role="tab" aria-controls="pills-fetalmovement" aria-selected="false">นับลูกดิ้น</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="pills-message-tab" data-toggle="pill" href="#pills-message" role="tab" aria-controls="pills-message" aria-selected="false">ข้อความ</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link" id="pills-chat-tab" data-toggle="pill" href="#pills-chat" role="tab" aria-controls="pills-chat" aria-selected="false">บทสนทนา</a>
            </li> -->
          </ul>
			  <div class="tab-content" id="pills-tabContent">
				<!--		////////// 	Content Weight   //////////   -->			  
				<div class="tab-pane  fade in active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
					<div class="content card">
          <h3>บันทึกน้ำหนักตัว</h3>
          <hr/>
						<div class="title m-b-md">
							<div id="chartdiv" class="chartdiv"></div>
						</div>
					</div>
					<div class="card ">
                <div style="text-align:center;"><h4>น้ำหนักก่อนตั้งครรภ์</h4></div>
					      <div class="wrap-weight txt-primary">
					        <img src="{{URL::asset('image/weight.png')}}" />
					          <h1>{{ $records1->user_Pre_weight}}</h1>
					      </div>
					</div>
					
					<div class="card">
          <div style="text-align:center;"><h4>น้ำหนักระหว่างการตั้งครรภ์</h4></div>
						<div>
							<table>
								<thead>
									<tr class="table-header">
										<th>สัปดาห์</th>
										<th>น้ำหนัก (กิโลกรัม)</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($record as $records)
									<tr>
										<td>{{ $records->preg_week}}</td>
										<?php
					               			if ($records->preg_weight == 'NULL'){
					                			$preg_weight = 'ไม่มีการบันทึก';
					              			}else{
					                			$preg_weight = $records->preg_weight ;
					              			} 
					              		?>
								  		<td>{{$preg_weight}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>			        
					</div>
				</div>
				
				<!--		////////// 	Content Food   //////////   -->				
				<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="content card">
          <h3>ประวัติการทานอาหาร</h3>
          <hr/>
<!-- ------------------------------------------------------------ -->
<table class="" cellpadding="1" cellspacing="1" id="">
  <thead>
    <tr class="table-header">
          <th  >วันที่</th>
          <th class="txt-left">เวลา</th>
          <th class="txt-left">มื้อ</th>
          <th class="txt-left">อาหาร</th>
          <th class="txt-left">ส่วนประกอบ</th>
          <th class="txt-left">ปริมาณ</th>
          <th class="txt-left">หน่วย</th>
          <th class="txt-left">แคลอรี่</th>
          <th class="txt-left"></th>
        </tr>
    </tr>
  </thead>
  <tbody id="">
  @foreach ($array3 as $tkact )
  <?php 

  $Date = date("d", strtotime($tkact->date));
  $year = date("Y", strtotime($tkact->date));  
  $strMonth= date("n",strtotime( $tkact->date));
  $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  $DateThai = $strMonthThai;

  $time = date("h:i",strtotime($tkact->time));

  
  $strunit = Array(" ","ทัพพี","ช้อน","ช้อนโต๊ะ","ลูก","ฟอง","ตัว","มล.","ชิ้น","อื่นๆ");
  $strmeal = Array(" ","เช้า","กลางวัน","เย็น","ว่างเช้า","ว่างบ่าย");
  $unit = $strunit[$tkact->unit];
  // $meal = $strmeal[$tkact->meal];
  $meal = "";
  switch ($tkact->meal) {
    case 1:
      $meal = "image/sunrise.png";
      break;
    case 2:
      $meal = "image/sun.png";
      break;
    case 3:
      $meal = "image/cloudy-night.png";
      break;
    case 4:
      $meal = "image/coffee-break.png";
      break;
    case 5:
      $meal = "image/afternoon-tea.png";
      break;
  }

  ?>
    <tr class="" id="table">
    <td style="display:none;"><label>{{$tkact->date}}</label></td>
      <td class="txt-left">{{ $Date  }} {{$strMonthThai}} {{$year}}</td>
      <td class="txt-left">{{$time}}</td>
      <td><img class="icon" src="<?php echo asset("{$meal}")?>"/></td>
      <td class="txt-left">{{$tkact->food_name}}</td>
      <td class="txt-left">{{$tkact->ingredient_name}}</td>
      <td class="txt-left">{{$tkact->portion}}</td>
      <td class="txt-left">{{$unit}}</td>
      <!-- <td>{{$tkact->id}}</td> -->
      <td class="txt-left">{{$tkact->calorie}}</td>
      <td class="txt-left">
      <form method="post" action="{{url('submitcal')}}">
          {{ csrf_field() }} 
          <input type="hidden" name="id" value=" {{$tkact->id}}" />
   
          <div class="flex">
            <input type="number" id="calorie" name="calorie" min="0" max="3000" class="float-left">
            <button type="submit" class="btn btn-primary w-50"><i class="fa fa-save"></i></button>
          </div>
      </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<!-- ------------------------------------------------------------ --> 	
				</div>
				</div>
				<!--		////////// 	Content Vitamin   //////////   -->
				<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">			  	
			  	<div class="content card">
            <h3>บันทึกการทานวิตามิน</h3>
            <hr/>
			  	      <div>
			  	  <table >
            <thead>
              <tr class="table-header">
                <th>วันที่</th>
                <th>วิตามิน</th>
              </tr>
            </thead>
			  	    <tbody>
			  	          @foreach ($record_vitamin as $records)
							<?php 
									$Date = date("d", strtotime($records->date));
									$year = date("Y", strtotime($records->date));  
									$strMonth= date("n",strtotime( $records->date));
									$strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
									$strMonthThai=$strMonthCut[$strMonth];
									$DateThai = $strMonthThai;

									$vitamin_a = Array("ไม่ได้ทาน","ทาน");
									$vitamin =$vitamin_a[$records->vitamin];

                  switch ($records->vitamin) {
                    case 0:
                      $vitamin_ = "image/traffic-signal.png";
                      break;
                    case 1:
                      $vitamin_ = "image/medicine.png";
                      break;
                  }

							?>
			  	          <tr>
                      <td >{{ $Date  }}  {{$strMonthThai}} {{$year}}</td> 
                      <td><img class="icon" src="<?php echo asset("{$vitamin_}")?>"/> {{$vitamin}}</td>
			  	          
			  	          </tr>
			  	         @endforeach
			  	   </tbody>
			  	</table>
			  	</div>
        </div>
			</div>
			
			<!--		////////// 	Content Exercise   //////////   -->		
			<div class="tab-pane fade" id="pills-exercise" role="tabpanel" aria-labelledby="pills-exercise-tab">				
				<div class="content card">
          <h3>บันทึกการออกกำลังกาย</h3>
          <hr/>
					<div>
						<table >	
            <thead>
              <tr class="table-header">
                <th>วันที่</th>
                <th>ออกกำลังกาย</th>
              </tr>
            </thead>		  
							<tbody>
					          @foreach ($record_exercise as $records)
							  <?php 
									$Date = date("d", strtotime($records->date));
									$year = date("Y", strtotime($records->date));  
									$strMonth= date("n",strtotime( $records->date));
									$strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
									$strMonthThai=$strMonthCut[$strMonth];
									$DateThai = $strMonthThai;
							?>
					          <tr>
                      <td >{{ $Date  }}  {{$strMonthThai}} {{$year}}</td>  
                      <td>					             
                          <p><img class="food" src="<?php echo asset('image/pregnant.png')?>" /> {{ $records->exercise }}</p>
                      </td>
					          </tr>
					         @endforeach
							</tbody>
						</table>
					</div>				
				</div>
			</div>

					<!--		////////// 	Content blood   //////////   -->		
				<div class="tab-pane fade" id="pills-blood" role="tabpanel" aria-labelledby="pills-blood-tab">				
				  <div class="content card">
          <h3>ค่าน้ำตาล</h3>
          <hr/>
          <div style="text-align:center;"> <h4>กราฟระดับน้ำตาลในเลือด</h4></div>
	        <div id="chart1div" class="chartdiv"></div>
          <hr/>
          <!-- <div style="text-align:center;"><h4>สรุปค่าระดับน้ำตาลรายสัปดาห์</h4></div>
          <div id="chart2div"  class="chart2div"></div> -->
          <h3>สรุปค่าระดับน้ำตาลในเลือด</h3>
          <label> <h4>เลือกวันที่ </h4>
          <input type="text" id="dateRange" placeholder="Select Date Range"> </label> 
          <div id="barchart" style="width: 100%; height: 500px;"></div>
          <hr/>
          <div style="text-align:center;"><h4>ประวัติการบันทึก</h4></div>
          <table class="" cellpadding="1" cellspacing="1" id="">
            <thead>
              <tr class="table-header">
                <th>วัน</th>
                <th>เวลา</th>
                <th>มื้อ</th>
                <th>ช่วงการวัด</th>
                <th>ค่าน้ำตาล</th>
              </tr>
            </thead>
            <tbody id="">
              @foreach ($graphdata as $graphdatas)
              <?php 
                  $temp = explode(' ',$graphdatas->datetime);
                  $time = date("H:i", strtotime($temp[1]));
                  $Date = date("d", strtotime($temp[0]));
                  $year = date("Y", strtotime($temp[0]));  
                  $meal = array("","เช้า", "กลางวัน", "เย็น", "ก่อนนอน");
                  $tod = array("","ก่อน", "หลัง", "หลัง 1 ชม." , "หลัง 2 ชม.");
                  $strMonth= date("n",strtotime($temp[0]));
                  $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                  $strMonthThai=$strMonthCut[$strMonth];
                  $DateThai = $strMonthThai;
                  $mealPath = "";

                  switch ($graphdatas->meal) {
                    case "4":
                      if($graphdatas->blood_sugar>120){
                        $color = "#FF0000";
                      }elseif($graphdatas->blood_sugar<60){
                        $color = '#FADA5E';
                      }else{
                        $color = "#17202A";
                      }  
                      break;
                    default:
                    if(($graphdatas->time_of_day =='1' && $graphdatas->blood_sugar>95)||($graphdatas->time_of_day== '3' && $graphdatas->blood_sugar>140)||($graphdatas->time_of_day=='4' && $graphdatas->blood_sugar>120)){
                      $color = "#FF0000";
                    }elseif($graphdatas->blood_sugar<60){
                      $color = '#FADA5E';
                    }else{
                      $color = "#17202A";
                    }  
                  }
                  $mealPath = "";

                  switch ($graphdatas->meal) {
                    case 1:
                      $mealPath = "image/sunrise.png";
                      break;
                    case 2:
                      $mealPath = "image/sun.png";
                      break;
                    case 3:
                      $mealPath = "image/cloudy-night.png";
                      break;
                    case 4:
                      $mealPath = "image/coffee-break.png";
                      break;
                    case 5:
                      $mealPath = "image/afternoon-tea.png";
                      break;
                  }
              ?>
              <tr class="" style="color:{{{ $color }}}">
                <td>{{ $Date  }} {{$strMonthThai}} {{$year}}</td>
                <td>{{ $time  }} น.</td>
                <td><img class="icon" src="<?php echo asset("{$mealPath}")?>"/></td>
                <td>{{ $tod[$graphdatas->time_of_day]}}</td>
                <td><h1>{{ $graphdatas->blood_sugar}}</h1> มก./ดล.</td>
              </tr>
              @endforeach
            </tbody>
          </table>
				</div>
			</div>

					<!--		////////// 	Content fetal movement  //////////   -->		
		    <div class="tab-pane fade" id="pills-fetalmovement" role="tabpanel" aria-labelledby="pills-fetalmovement-tab">				
				<div class="content card">
          <h3>บันทึกนับลูกดิ้น</h3>
          <hr/>
					<table class="" cellpadding="1" cellspacing="1" id="">
          <thead>
            <tr class="table-header">
              <th>วัน</th>
              <th><img class="icon" src="<?php echo asset("image/sunrise.png")?>"/></th>
              <th><img class="icon" src="<?php echo asset("image/sun.png")?>"/></th>
              <th><img class="icon" src="<?php echo asset("image/cloudy-night.png")?>"/></th>
              <th>รวม</th>
            </tr>
          </thead>
              <tbody id="">
                @foreach ($fetal_movement as $fm )
                <?php 

                    $Date = date("d", strtotime($fm->date));
                    $year = date("Y", strtotime($fm->date));  
                    $strMonth= date("n",strtotime($fm->date));
                    $strMonthCut = Array(" ","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                    $strMonthThai=$strMonthCut[$strMonth];
                    $DateThai = $strMonthThai;
                    if(($fm->num_noon+ $fm->num_morning + $fm->num_evening)<10){
                      $color = "#FA8072";
                    }else{
                      $color = "#17202A";
                    }
                  ?>
                <tr class="" style="color:{{{ $color }}}">
                  <td style="display:none;"><label>{{ $fm->date }}</label></td>
                  <td >{{ $Date  }}  {{$strMonthThai}} {{$year}}</td>  
                  <td >{{ $fm->num_morning }}</td>
                  <td>{{ $fm->num_noon }}</td>
                  <td>{{ $fm->num_evening}}</td>
                  <td>{{ $fm->num_evening + $fm->num_noon +$fm->num_morning }}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
							
				</div>
			</div>
			
			<!--		////////// 	Content Message   //////////   -->		
			<div class="tab-pane fade" id="pills-message" role="tabpanel" aria-labelledby="pills-message-tab">				
				<div class="content card">
          <h3>ประวัติข้อความที่ส่ง</h3>
          <hr/>
					<div>
						<div class="list-group mb-3">
            @foreach($chats as $message)
						<table class="chat" width="100%" border="0">
							<tr>
								@if ($message['message_type'] == '01')
								<td align="left" width="10%" valign="top"><img src= "{{URL::asset('css/mom.png')}}" width="50" height="50" ></td>
								<td align="left" width="60%" valign="top"  bgcolor="#F4F6F6" >{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="left" width="20%"></td>								
								@elseif ($message['message_type'] == '02')
								<td align="right" width="20%"></td>
								<td align="right" width="60%" valign="top"  bgcolor="#FDEDEC">{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="right" width="10%" valign="top"><img src= "{{URL::asset('css/remi.png')}}" width="50" height="50" ></td>
								@elseif ($message['message_type'] == '03')
								<td align="right" width="20%"></td>
								<td align="right" width="60%" valign="top"  bgcolor="#D5F5E3">{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="right" width="10%" valign="top"><img src= "{{URL::asset('css/doctor_icon.png')}}" width="50" height="50" ></td>								
								@endif
							</tr>
                 
						</table>
								 @endforeach
                  </div>
              <hr/>
							<form method="POST" action="/remi_ai/api/weight_warning" class="p-2">
							     <input type="hidden" name="doctor_id" value="{{ $doctor_id }}" />
							     <input type="hidden" name="user_id_line" value="{{ $user_id }}" />
							     <div class="form-group">
							       <label for="exampleFormControlTextarea1">ข้อความ</label>
							       <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="text"></textarea>
							     </div>
							   <div class="form-group row mb-0">
							   	<div class="col-md-12">
							   		<button type="submit" class="btn btn-primary btn-block">{{ __('ส่งข้อความ') }}</button>			
							   	</div>
							   </div>
							</form>
								@if (session('status'))
								    <div class="alert alert-success">
								        {{ session('status') }}
								    </div>
								@endif						
					</div>				
				</div>
			</div>
			<!--		////////// 	Content Chat   //////////   -->		
			<!-- <div class="tab-pane fade" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">				
			    <div class="content card">
			    	<h3>ประวัติสนทนา</h3>
            <hr/>
			    	<div>
								@foreach($chats as $message)
						<table class="chat" width="100%" border="0">
							<tr>
								@if ($message['message_type'] == '01')
								<td align="left" width="10%" valign="top"><img src= "{{URL::asset('css/mom.png')}}" width="50" height="50" ></td>
								<td align="left" width="60%" valign="top"  bgcolor="#F4F6F6" >{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="left" width="20%"></td>								
								@elseif ($message['message_type'] == '02')
								<td align="right" width="20%"></td>
								<td align="right" width="60%" valign="top"  bgcolor="#FDEDEC">{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="right" width="10%" valign="top"><img src= "{{URL::asset('css/remi.png')}}" width="50" height="50" ></td>
								@elseif ($message['message_type'] == '03')
								<td align="right" width="20%"></td>
								<td align="right" width="60%" valign="top"  bgcolor="#D5F5E3">{{ $message['message'] }}
                <p>{{ $message['created_at'] }}		</p>	
                </td>
								<td align="right" width="10%" valign="top"><img src= "{{URL::asset('css/doctor_icon.png')}}" width="50" height="50" ></td>								
								@endif
							</tr>
                 
						</table>
								 @endforeach
			    	</div>				
			    </div>
			</div>
			</div>
		</div> -->
		
		</div>
	</div>
</div>


@endsection
