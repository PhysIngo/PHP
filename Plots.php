<?php

ob_start();
if (isset($color)){}else{
  $color = "Safari";
}

$Colors = json_decode(file_get_contents('data/Colors.json'), true);
$colArr = $Colors[trim($color)]["Color"];
$clrs = 'colors: ["'.$colArr[0].'", "'.$colArr[1].'", "'.$colArr[2].'","'.$colArr[3].'","'.$colArr[4].'","'.$colArr[5].'","'.$colArr[6].'","'.$colArr[7].'",
"'.$colArr[8].'","'.$colArr[9].'"]';

$ActCol = $Colors[trim($color)];
ob_end_clean();

if (isset($fullurl)){}else{
  $fullurl = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
}
// newArr ... array of format: newArr[$i][$params[$k]];
// params ... param and array name: array("date2,"wert");
// paramsname ... name of params: array("Datum,"Geld");
// paramssign ... sign of params: array(","€");

// subplots, histoplots, pieplots, xyplots ... array of length of params
// with -1 for the x-Axes and 1,1.2,2 for the y-Axes.
// for 1.1,1.2 it will plot it and two axes in one plot
// if number exceeds 1, then there is another plot shown for number 2...
//$isFile = false;
if (isset($isFile)){}else{
  $isFile = false;
}

if (isset($params)){}else{
	$params = array();return;
}
if (isset($paramsname)){}else{
	$paramsname = array();return;
}
if (isset($paramssign)){}else{
	$paramssign = array(sizeof($params));
}
if (isset($newArr)){}else{
  $newArr = array();if(!$isFile){return;}
}

if (isset($rangeSelector)){}else{
  $rangeSelector = true;
}

if (isset($graphWidth)){}else{
  $graphWidth = '"100%"';
}
if (isset($graphHeight)){}else{
  $graphHeight = "300";
}
if ($rangeSelector){
  //$graphHeight = $graphHeight + 150;
}
 
if (isset($isTrendline)){}else{
		$isTrendline = false;
}
  
if (isset($isArea)){}else{
		$isArea = false;$isAreaActiv = false;
}
   
if (isset($relative)){
    if($relative=="daily"){
      $relativeFac = 24*3600;
      $relative = true;
    }elseif($relative=="monthly"){
      $relativeFac = 24*3600*365.251/12;
      $relative = true;
    }elseif($relative=="yearly"){
      $relativeFac = 24*3600*365.251;
      $relative = true;
    }elseif($relative=="hourly"){
      $relativeFac = 60*60;
      $relative = true;
    }elseif($relative=="minutely"){
      $relativeFac = 60;
      $relative = true;
    }else{//
      $relativeFac = 1;
    }
  }else{
  $relative = false;
  $relativeFac = 1;
}
if ($isFile){
  //var_dump($FileName);
  if (isset($skipHeader)){}else{
    $skipHeader = 0;
  }
  $cc = 0;
  $newArr = file($FileName);
  for ($i=0;$i<$skipHeader;$i++){
    array_shift($newArr);
  }
  foreach($newArr as $key => $line) {
      $newArr[$key] = explode(",", $line);
  }
}
// https://developers.google.com/chart/interactive/docs/gallery
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//

if (isset($isPoints)){}else{
	$isPoints = false;
}
if (isset($isLine)){}else{
	$isLine = false;
}
if (isset($isXY)){}else{
	$isXY = false;
}

if (isset($subplots)){}else{
  $subplots = array();
}
// plot Line
if ($isXY or $isLine or $isPoints){
	if (isset($isSmooth)){}else{
		$isSmooth = false;
	}
	if($isPoints or $isLine){
    $startCounter=0;
  }else{ $startCounter=1;}
	if($isXY){
    $xyCounter=2;
  }else{ $xyCounter=1;}

  $thePlotCounter = 0;
	for ($scatterCounter=$startCounter;$scatterCounter<$xyCounter;$scatterCounter++){
		
    
	if ($scatterCounter == 1){
		$isPoints = true;
		$isLine = false;
		if (isset($xyplots)){
      $subplots = $xyplots;
    }else{
			$xyplots = array();
		}
	}
  
  
   $plotNumber = max(round(max($subplots)),1);  
  for ($pltnr=1;$pltnr<(intval($plotNumber)+1);$pltnr++){
    
    if ($isArea){
      if ($areaVals[4] == $thePlotCounter){
        $isAreaActiv = true;
      }else{
        $isAreaActiv = false;
      }
    }
    
    
    $idxarr = array();
    $idx = array();
    $plotidx = array();
    $trendidx = array();
    $trendpos = array();
    $mc = 0;$nc = 0;
    $isSecondaryHisto = false;
    for ($hc=0;$hc<sizeof($subplots);$hc++){
           
        
      if(floor($subplots[$hc])==$pltnr){
        if ($isTrendline){
          $theVal = floatval($subplots[$hc])*100-floor(floatval($subplots[$hc])*10)*10;
          //echo($theVal);
          if($theVal >0.9){
            $trendidx[$nc] = $theVal;
            $trendpos[$nc] = $mc;
            $nc = $nc + 1;
          }
        }
        
        $idx[$mc] = $hc;
        $idxarr[$hc] = 1;
        $plotidx[$mc] = 1;
        if ($subplots[$hc]>($pltnr+0.4999) ){
          $isSecondaryHisto = true; 
          $plotidx[$mc] = 2;
        }
        $mc = $mc + 1;
      }else{
        $idxarr[$hc] = 0;
      }
      
        
      if ($subplots[$hc]==0){
        continue;
      }
      if ($subplots[$hc]<0){
          $xidx = $hc;
          continue;
      }
      
    }
    //var_dump($trendidx);
    //var_dump($trendpos);
    // generate the input for the bar plot
  $plotChart = '["'.$paramsname[$xidx].'"';
  for ($k=0;$k<sizeof($idx);$k++){
    $plotChart = $plotChart .',"'.$paramsname[$idx[$k]].'"';
  }
  if ($isArea and $isAreaActiv){
    $plotChart = $plotChart.',"'.$areaVals[3].'"';
  }
  
  $plotChart = $plotChart.']';
  for ($j=0;$j<sizeof($newArr);$j++){
	if (array_key_exists($params[$xidx],$newArr[$j])  ){
		if (strlen(trim($newArr[$j][$params[$xidx]]))==0){
			continue;
		}else{
			$var = trim($newArr[$j][$params[$xidx]]);
			if (substr_count($var,'-')==2){
        $c = explode('-',$var);
        if ($relative){
          $var = (strtotime(trim($newArr[$j][$params[$xidx]]))-strtotime(trim($newArr[sizeof($newArr)-1][$params[$xidx]])) )/($relativeFac);
        }else{
          $var = DateToGoogleDate($var);
        }
			}elseif (substr_count($var,':')==2){
        $c = explode(':',$var);
        //if ($relative){
        //  $var = (strtotime(trim($newArr[$j][$params[$xidx]]))-strtotime(trim($newArr[sizeof($newArr)-1][$params[$xidx]])) )/($relativeFac);
        //}else{
          $var = DateToGoogleDate(date('d-m-y')." ".$var);
          //$var = '['.(int)$c[0].', '.(int)$c[1].', '.(int)$c[1].']';
        //}
			}/*else{
        $var = intval($var);
      }*/
			$plotChart = $plotChart.',['.$var.'';
		}
	}else{
		continue;
	}
    for ($k=0;$k<sizeof($idx);$k++){
		if (array_key_exists($params[$idx[$k]],$newArr[$j])  ){
			if (strlen($newArr[$j][$params[$idx[$k]]])==0){
				$val = "null";
			}else{
				$val = $newArr[$j][$params[$idx[$k]]];
			}
		}else{
			$val = "null";
		}
      $plotChart = $plotChart .','.$val;
    }
    
    if ($isArea  and $isAreaActiv){ 
        $plotChart = $plotChart .',null';
    }
    $plotChart = $plotChart .']';
  } 
  
  
    if ($isArea and $isAreaActiv){
      
      if  (substr_count(trim($newArr[0][$params[$xidx]]),'-')==2){
        $var = $areaVals[1];
        $var = DateToGoogleDate($var);
        $plotChart = $plotChart.',['.$var.'';
      }else{
        $plotChart = $plotChart.',['.$areaVals[1].'';
      }
      for ($k=0;$k<sizeof($idx);$k++){
        $plotChart = $plotChart .',null';
      }
      $plotChart = $plotChart .','.$areaVals[0].'';
      if  (substr_count(trim($newArr[0][$params[$xidx]]),'-')==2){
        $var = $areaVals[2];
        $var = DateToGoogleDate($var);
        $plotChart = $plotChart.'],['.$var.'';
      }else{
        $plotChart = $plotChart.'],['.$areaVals[2].'';
      }
      for ($k=0;$k<sizeof($idx);$k++){
        $plotChart = $plotChart .',null';
      }
      $plotChart = $plotChart .','.$areaVals[0].']';
    }
    
    
  $thetime = microtime(true)*10000;
	//echo($plotChart);
  // plot barplot
  
?>

<script type="text/javascript">
  
<?php

echo('
google.load("visualization", "current", {packages: ["controls", "charteditor","corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() { ');
  
        echo('var data = google.visualization.arrayToDataTable(['.$plotChart.']);'); 
    
    echo('
    var dash = new google.visualization.Dashboard(document.getElementById("dashboard'.strval($thetime+$pltnr+$scatterCounter*100).'"));');
  echo('
    var control = new google.visualization.ControlWrapper({
        controlType: "ChartRangeFilter",
        containerId: "control_div'.strval($thetime+$pltnr+$scatterCounter*100).'",
        options: {
            filterColumnIndex: 0,
            ui: {
                chartOptions: {
            orientation:"horizontal",backgroundColor:"transparent",fontSize:15,'.$clrs.',');
            
            if ($isSmooth){
              echo('curveType: "function",');
            }
            if ($isPoints){
              echo('pointSize: 6,');
            }else{
              echo('pointSize: 0,');
            }
            if ($isLine){
              echo('lineSize: 2,');
            }else{
              echo('lineSize: 0,');
            }
            echo('hAxis: { title: "", titleTextStyle: {italic: false,bold:false},minorGridlines:{color: "none"},gridlines:{count: 4,color: "#999"},textPosition: "none"},');
            if ($isArea and $isAreaActiv){
            echo('series: {'.sizeof($idx).': {type: "area", color: "gray"}},');
            }
            echo('vAxis:{title: "", minorGridlines:{color: "none"},gridlines:{color: "#999",count: 4}, titleTextStyle: {italic: false,bold:false},textPosition: "none"},
                    height: 40,
                    width: "'.$graphWidth.'",
                    chartArea: {
                        width: "80%"
                    }
                }
            }
        }
    });');
    echo('
    var chart = new google.visualization.ChartWrapper({
        chartType: "LineChart",
        containerId: "plotchart_values'.strval($thetime+$pltnr+$scatterCounter*100).'"
    });');?>

    function setOptions (wrapper) {
       
        
        wrapper.setOption('backgroundColor', 'transparent');
        wrapper.setOption('orientation', 'horizontal');
        wrapper.setOption('legend.position', 'top');
        wrapper.setOption('legend.maxLines', '5');
        wrapper.setOption('chartArea.width', '80%');
        <?php
        //
        echo('wrapper.setOption("fontsize", "15");');
        echo('wrapper.setOption("width", "'.$graphWidth.'");');
        echo('wrapper.setOption("height", "'.$graphHeight.'");');
        echo('wrapper.setOption("colors", '.str_replace("colors: ","",$clrs).');');
        
        echo('wrapper.setOption("hAxis.title", "'.$paramsname[$xidx].'");');
        echo('wrapper.setOption("hAxis.titleTextStyle.italic", false);');
        echo('wrapper.setOption("hAxis.titleTextStyle.bold", true);');
        echo('wrapper.setOption("hAxis.minorGridlines.color", "none");');
        echo('wrapper.setOption("hAxis.gridlines.count", "4");');
        echo('wrapper.setOption("hAxis.gridlines.color", "'.$ActCol["Gray"][4].'");');
        echo('wrapper.setOption("hAxis.baselineColor", "'.$ActCol["Gray"][0].'");');
        
        echo('wrapper.setOption("vAxis.title", "'.$paramsname[$idx[0]].' ['.$paramssign[$idx[0]].']");');
        echo('wrapper.setOption("vAxis.titleTextStyle.italic", false);');
        echo('wrapper.setOption("vAxis.titleTextStyle.bold", true);');
        echo('wrapper.setOption("vAxis.minorGridlines.color", "none");');
        echo('wrapper.setOption("vAxis.gridlines.count", "4");');
        echo('wrapper.setOption("vAxis.gridlines.color", "'.$ActCol["Gray"][4].'");');
        echo('wrapper.setOption("vAxis.baselineColor", "'.$ActCol["Gray"][0].'");');
        
        echo('wrapper.setOption("crosshair.trigger","both");');
        echo('wrapper.setOption("crosshair.orientation","both");');
        if ($isTrendline){
          for ($kidx=0;$kidx<sizeof($trendidx);$kidx++){
           // if ($trendidx[$kidx]>0){
              echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.type","polynomial");');
              echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.degree",'.$trendidx[$kidx].');');
              //echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.visibleInLegend",true);');
              echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.lineWidth",2);');
              echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.color","black");');
              echo('wrapper.setOption("trendlines.'.$trendpos[$kidx].'.pointSize","0");');
            //}
          }
         }
        /*echo(' wrapper.setOption("labels", ["",');
        for ($k=0;$k<$mc;$k++){
            echo('"'.$paramsname[$idx[$k]].'",');
        }echo(']);');*/
        if ($isSecondaryHisto){
          for ($k=0;$k<$mc;$k++){
            if ($plotidx[$k]>1.5){
              echo('wrapper.setOption("series.'.$k.'.targetAxisIndex", "1");');
              $names2 = $paramsname[$idx[$k]].' ['.$paramssign[$idx[$k]].']';
            }else{
              echo('wrapper.setOption("series.'.$k.'.targetAxisIndex", "0");');
            }
          }
          echo('wrapper.setOption("vAxes.1.title", "'.$names2.'");');
          echo('wrapper.setOption("vAxis.1.titleTextStyle.italic", false);');
          echo('wrapper.setOption("vAxis.1.titleTextStyle.bold", true);');
          echo('wrapper.setOption("vAxis.1.minorGridlines.color", "none");');
          echo('wrapper.setOption("vAxis.1.gridlines.count", "4");');
          echo('wrapper.setOption("vAxis.1.gridlines.color", "'.$ActCol["Gray"][4].'");');
          echo('wrapper.setOption("vAxis.1.baselineColor", "'.$ActCol["Gray"][0].'");');
        }
        
        if ($isSmooth){
          echo('wrapper.setOption("curveType", "function");');
        }
        if ($isPoints){
          echo('wrapper.setOption("pointSize", 6);');
        }else{
          echo('wrapper.setOption("pointSize", 0);');
        }
        if ($isLine){
          echo('wrapper.setOption("lineSize", 2);');
        }else{
          echo('wrapper.setOption("lineSize", 0);');
        }
        
        if ($isArea and $isAreaActiv){
          echo('wrapper.setOption("series.'.sizeof($idx).'.type", "area");');
          echo('wrapper.setOption("series.'.sizeof($idx).'.color", "gray");');
          echo('wrapper.setOption("series.'.sizeof($idx).'.pointSize", 0);');
        }
      ?>
    }
    setOptions(chart);
    //setOptions(control);
   <?php
    echo('
    dash.bind([control], [chart]);
    dash.draw(data);
  	google.visualization.events.addListener(control, "statechange", function () {
        var v = control.getState();
        return 0;
    });');?>
}
</script>
<?php
$thePlotCounter = $thePlotCounter + 1;
echo('
<div class="center" id="dashboard'.strval($thetime+$pltnr+$scatterCounter*100).'">
    <div class="center" id="plotchart_values'.strval($thetime+$pltnr+$scatterCounter*100).'" align="center"></div>
    <div class="center" id="control_div'.strval($thetime+$pltnr+$scatterCounter*100).'" align="center"></div>
</div class="center">');




  }

}// end of $scatterCounter
  $isXY = false; 
  $isLine = false;
  $isPoints = false;
}// end of isPoints and isLine


if (isset($isHisto)){}else{
	$isHisto = false;
}
if ($isHisto){
  
	if (isset($histoplots)){}else{
		$histoplots = $subplots;
	}
  $histoplots = array_map('floatval', $histoplots);
	if (isset($isStacked)){}else{
		$isStacked = false;
	}
  
  $nurl = $fullurl;
  
  if (isset($_GET["StepSize"])){
    $StepSize = htmlspecialchars($_GET["StepSize"]);
    $nurl = str_replace("&StepSize=".$_GET["StepSize"],'',$fullurl);
  }elseif (isset($StepSize)){
  }else{
	  $StepSize = "";
  }
  if(strlen($StepSize)==0){
    $StepSize = 12;
    $nurl = str_replace("&StepSize=12",'',$fullurl);
  }
  
  if (isset($histoSkipSlider)){
  }else{
    $histoSkipSlider = false;
  }
  ?>
  </br>
  <div class="center" style="overflow-x:hidden;overflow-y:hidden;display:inline-block;margin-top:10px;width:99%;">
    <div >
      <?php
      
  if (!$histoSkipSlider){
    ?>
		<div style="display:inline-block;vertical-align:middle;margin-bottom:0px;display: flex;justify-content: center;">
		  <form action="" method="POST">
			<span id="valBox"></span>
			<input type="range" style="filter:var(--buttom);" min="3" max="48" step="1" 
			value="<?php echo($StepSize);?>" id="foo" 
			name="passengers" onchange='showVal(this.value,"<?php echo($nurl)?>");'/>
			<input type="text" style="max-width:180px;padding:0px;margin:5px;vertical-align:center;" 
			name="bar" id="bar" value="Slider Value = <?php echo($StepSize);?>" disabled />
      <select class="ffield" onchange='showVal(this.value,"<?php echo($nurl)?>");' name="dropstep" id="dropstep">
      <?php
      $optStep = array("-","minutely","hourly","daily","weekly","monthly","quarterly","yearly","hourofday","weekday","dayofmonth","dayofyear","monthofyear","calendarweek");
      for ($i=0;$i<sizeof($optStep);$i++){
        if ($StepSize==$optStep[$i]){
          $optAdd = 'selected';
        }else{$optAdd = '';
        }
        echo('<option value="'.$optStep[$i].'" '.$optAdd.'>'.$optStep[$i].'</option>');
      }
      ?>
      </select>
		  </form> 
		</div>
    
  
  <script>
  function showVal(newVal,url){
    document.getElementById("valBox").innerHTML=newVal;
    //alert(newVal);
    window.location.replace(url + "&StepSize="+newVal);
  }
  </script>
  
  <!-- Slide to choose a different amount of bars.-->
  <?php
  }//End of histoSkipSlider
  $saveStepSize = $StepSize;
  //var_dump($histoplots);
  
  $plotNumber = max(array_map("abs", $histoplots));
  //var_dump($plotNumber);
  for ($pltnr=1;$pltnr<(intval($plotNumber)+1);$pltnr++){
    
    $idxarr = array();
    $idx = array();
    $plotidx = array();
    $mc = 0;
    $isSecondaryHisto = false;
    $stackedData = -1;
    
    for ($hc=0;$hc<sizeof($histoplots);$hc++){
      $StepSize = $saveStepSize;
           
      if(round($histoplots[$hc])==$pltnr){
        if ($histoplots[$hc]<($pltnr) ){
          $stackedData = $hc;
        }else{
          $idx[$mc] = $hc;
          $idxarr[$hc] = 1;
          $plotidx[$mc] = 1;
          if ($histoplots[$hc]>($pltnr+0.1) ){
            $isSecondaryHisto = true; 
            $plotidx[$mc] = 2;
          }
          $mc = $mc + 1;
        }
      }else{
        $idxarr[$hc] = 0;
      }
      if ($histoplots[$hc]==0){
        continue;
      }
      if ($histoplots[$hc]<0){
          $xidx = $hc;
          continue;
      }
      
    }
    
    if ($stackedData>-1){	  	
      $elements = array_column($newArr, $params[$stackedData]);
      $theElements = array_values(array_unique($elements));	
    }
    
    $dates = array();
    $StepOpt = 0;
    if (substr_count($newArr[0][$params[$xidx]],'-')==2){
      for ($j=0;$j<sizeof($newArr);$j++){
        $dates[$j] = strtotime($newArr[$j][$params[$xidx]]);
        if ($relative){
          $dates[$j] = ($dates[$j]-strtotime($newArr[sizeof($newArr)-1][$params[$xidx]]))/$relativeFac;
        }
      }
      
      $start = min($dates);$end = max($dates);
      if (isset($histoBorders)){
        $start = $histoBorders[0];
        $end = $histoBorders[1];
      }
      $formatAdd = '';
      $inc = ($end-$start)/($StepSize-1);
      if ($StepSize=="yearly"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $StepSize = $diff;
        $inc = 3600*24*365.251;
        $StepOpt = 1;
        $StepSize = $y2-$y1 + 1;
        $formatAdd = 'format: "YY",';
      }elseif ($StepSize=="monthly"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $m1 = date('m',$start);
        $m2 = date('m',$end);
        $diff = (($y2-$y1)*12) + $m2 - $m1 + 1;
        $start = strtotime(gmdate("Y-m-01 05:00:00",($start)));
        $end = strtotime(gmdate("Y-m-t 23:59.59",($end)));
        $StepSize = $diff;
        $inc = ($end-$start)/($diff);
        $StepOpt = 2;
        $formatAdd = 'format: "MMM YY",';
      }elseif ($StepSize=="quarterly"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $m1 = date('m',$start);
        $m2 = date('m',$end);
        $diff = (($y2-$y1)*4) + round(($m2 - $m1)/3) + 1;
        $start = strtotime(gmdate("Y-m-01 05:00:00",($start)));
        $end = strtotime(gmdate("Y-m-t 23:59.59",($end)));
        $StepSize = $diff;
        $inc = ($end-$start)/($diff);
        $StepOpt = 3;
        $formatAdd = 'format: "MMM YY",';
      }elseif ($StepSize=="daily"){
        $diff = ceil(($end-$start)/(3600*24));
        $StepSize = $diff;
        $inc = 3600*24;
        $StepOpt = 0;
        $formatAdd = 'format: "d. MMM YY",';
      }elseif ($StepSize=="weekday"){
        $StepSize = 7;
        $inc = 3600*24;
        $StepOpt = -4;
      }elseif ($StepSize=="calendarweek"){
        $StepSize = 52;
        $inc = 3600*24*7;
        $StepOpt = -9;
      }elseif ($StepSize=="dayofyear"){
        $StepSize = 366;
        $inc = 3600*24;
        $StepOpt = -10;
      }elseif ($StepSize=="dayofmonth"){
        $StepSize = 31;
        $inc = 3600*24;
        $StepOpt = -7;
      }elseif ($StepSize=="monthofyear"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $m1 = date('m',$start);
        $m2 = date('m',$end);
        $diff = (($y2-$y1)*12) + $m2 - $m1 + 1;
        $start = strtotime(gmdate("Y-m-01 05:00:00",($start)));
        $end = strtotime(gmdate("Y-m-t 23:59.59",($end)));
        $inc = ($end-$start)/($diff);
        $StepSize = 12;
        $StepOpt = -5;
      }elseif ($StepSize=="hourofday"){
        $StepSize = 24;
        $inc = 3600;
        $StepOpt = -6;
      }elseif ($StepSize=="weekly"){
        $diff = ceil(($end-$start)/(3600*24*7));
        $StepSize = $diff;
        $inc = 3600*24*7;
        $StepOpt =0;
        $formatAdd = 'format: "d. MMM YY",';
      }elseif ($StepSize=="hourly"){
        $diff = ceil(($end-$start)/(3600));
        $StepSize = $diff;
        $inc = 3600;
        $StepOpt = -1;
        $formatAdd = 'format: "hh:00:00 d. MMM YY",';
      }elseif ($StepSize=="minutely"){
        $diff = ceil(($end-$start)/(60));
        $StepSize = $diff;
        $inc = 60;
        $StepOpt = -2;
        $formatAdd = 'format: "hh:ii:00 d. MMM YY",';
      }
    }else{
      for ($j=0;$j<sizeof($newArr);$j++){
        $dates[$j] = ($newArr[$j][$params[$xidx]]);
      }
      $start = min($dates);$end = max($dates);
      $inc = ($end-$start)/($StepSize-1);
    }
    
    
    $data = array();
    for ($l=0;$l<$StepSize;$l++){
      if (sizeof($idx)==0){
        $data[$l][0] = 0;
      }else{
        if ($stackedData>-1){
          for ($k=0;$k<sizeof($theElements);$k++){
            $data[$l][$k] = 0;
          } 	  	
        }else{
          for ($k=0;$k<sizeof($idx);$k++){
            $data[$l][$k] = 0;
          } 
        }
      }
    }
    for ($j=0;$j<sizeof($newArr);$j++){
      if ($StepOpt == 0){
        $val = round( -0.5 + (($dates[$j]-$start)/($inc ) ));
      }else if($StepOpt==1){
        
        $y0 = date("Y",$start);
        $y2 = date("Y",$dates[$j]);
        $val = $y2-$y0;
      }else if($StepOpt==2){
        $m0 = date("m",$start);
        $m2 = date("m",$dates[$j]);
        $y0 = date("Y",$start);
        $y2 = date("Y",$dates[$j]);
        $val = $m2-$m0+($y2-$y0)*12;
      }else if($StepOpt==3){
        $m0 = date("m",$start);
        $m2 = date("m",$dates[$j]);
        $y0 = date("Y",$start);
        $y2 = date("Y",$dates[$j]);
        $val = round(($m2-$m0-0.5)/3)+($y2-$y0)*4;
      }else if($StepOpt==-4){
        $val = gmdate("N",$dates[$j])-1;
      }else if($StepOpt==-5){
        $val = gmdate("n",$dates[$j])-1;
      }else if($StepOpt==-6){
        $val = gmdate("G",$dates[$j]);
      }else if($StepOpt==-7){
        $val = gmdate("j",$dates[$j])-1;
      }else if($StepOpt==-9){
        $val = gmdate("W",$dates[$j])-1;
      }else if($StepOpt==-10){
        $val = gmdate("z",$dates[$j]);
      }else{
        $val = round( -0.5 + (($dates[$j]-$start)/($inc ) ));
        
      }
      if (sizeof($idx)==0){
        $data[$val][0] = $data[$val][0] + 1;
      }else{
        
        if ($stackedData>-1){ 	
          for ($k=0;$k<sizeof($theElements);$k++){
            $el = $newArr[$j][$params[$stackedData]];
            if ($el == $theElements[$k]){
              $data[$val][$k] = $data[$val][$k] + $newArr[$j][$params[$idx[0]]];
            }
          } 	 
        }else{
          for ($k=0;$k<sizeof($idx);$k++){
            if (is_numeric($newArr[$j][$params[$idx[$k]]])){
              $data[$val][$k] = $data[$val][$k] + $newArr[$j][$params[$idx[$k]]];
            }
          }
        }
      }
    }
    
      // generate the input for the bar plot
    $barChart = '["'.$paramsname[$xidx].'"';
    if ($stackedData>-1){ 	
        for ($k=0;$k<sizeof($theElements);$k++){
            $barChart = $barChart .',"'.$theElements[$k].'"';
        } 	 
    }else{
      for ($k=0;$k<sizeof($idx);$k++){
        $barChart = $barChart .',"'.$paramsname[$idx[$k]].'"';
      }
    }
    
    if (sizeof($idx)==0){
      $barChart = $barChart .',"Bins"';
      $idx = -1;
    }
    if ($isTrendline){
      $barChart = $barChart . ',"Gesamt"';
    }
    $barChart = $barChart.']';
    for ($i=0;$i<$StepSize;$i++){
      if (substr_count($newArr[0][$params[$xidx]],'-')==2){
        if ($StepOpt==-1){
          $date = gmdate("Y-m-d h:00:00",( $start + $inc*($i+0.5)));
        }elseif ($StepOpt==-2){
          $date = gmdate("Y-m-d h:i:00",( $start + $inc*($i+0.5)));
        }else{
          $date = gmdate("Y-m-d 12:00:00",( $start + $inc*($i+0.5)));
        }
        if ($StepOpt==2){
          $date = gmdate("Y-m-01",strtotime("+".$i." month", $start));
        }elseif ($StepOpt==3){
          $date = gmdate("Y-m-01",strtotime("+".(3*$i)." month", $start));
        }
        $c = explode('-',$date);
        if ($relative){
          $var = (strtotime($date)-strtotime($newArr[sizeof($newArr)-1][$params[$xidx]]))/$relativeFac;
        }else{
          if (substr_count($date,':')==2){
            $cn = explode(" ",$c[2]);
            $c[2] = $cn[0];
            $d = explode(":",$cn[1]);
            $timeAdd = ', '.implode(',',$d);
            //echo($timeAdd);
          }else{
            $timeAdd = "";
          }
          $var = 'new Date('.intval($c[0]).', '.intval($c[1]-1.0).', '.intval($c[2]).$timeAdd.')';
          if ($StepOpt<-3){
            $var = $i;
          }
        }
      }else{
        $var = $start + $inc*($i+0.5);
      }
      if ($StepOpt==-4){
        $var = '"'.date('l', strtotime("Monday + $i Days")).'"'; // 0 - 7
      }elseif ($StepOpt==-5){
        $monthNum  = $i+1;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $var = '"'.$dateObj->format('F').'"'; // 0 - 12
      }elseif ($StepOpt==-6){
        $var = '"'.($i+1).':00"'; // 0 -- 24
      }elseif ($StepOpt==-7){
        $var = $i+1;  // 0 - 31
      }
      
      
      $barChart = $barChart .',['.$var.'';

      $thesum = 0;
      if ($stackedData>-1){ 	
        for ($k=0;$k<sizeof($theElements);$k++){
          $barChart = $barChart .','.$data[$i][$k];
          $thesum = $thesum + $data[$i][$k];
        } 	 
      }else{
        for ($k=0;$k<sizeof($idx);$k++){
          $barChart = $barChart .','.$data[$i][$k];
          $thesum = $thesum + $data[$i][$k];
        }
      }
      if ($isTrendline){
        $barChart = $barChart . ','.$thesum;
        if ($isStacked){
          $trendidx = sizeof($theElements);
        }else{
          $trendidx = sizeof($theElements);
          //$trendidx = sizeof($idx);
        }
      }
      $barChart = $barChart .']';
    } 
  //echo($barChart);
  // plot barplot
  $thetime = microtime(true)*10000;
  
    echo('
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});google.charts.setOnLoadCallback(drawBarChart);
    function drawBarChart() {
      var data = google.visualization.arrayToDataTable(['.$barChart.']);
      var view = new google.visualization.DataView(data);
      var options = {orientation:"horizontal",backgroundColor:"transparent",'.$clrs.',
      seriesType: "bars", legend: {position: "top", maxLines: 5},fontSize:15,bar: { groupWidth: "62%" },
      hAxis: { '.$formatAdd.'title: "'.$paramsname[$xidx].'",minorGridlines:{count:0,color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},
      vAxis:{title: "'.$paramsname[$idx[0]].' ['.$paramssign[$idx[0]].']", 
      minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},
      labels: ["",');
        for ($k=0;$k<$mc;$k++){
            echo('"'.$paramsname[$idx[$k]].'",');
        }
        echo('], ');
        if ($isSecondaryHisto){
          echo('series: {');
          for ($k=0;$k<$mc;$k++){
            if ($plotidx[$k]>1.5){
              echo(''.$k.': {targetAxisIndex:1} ,');
              $names2 = $paramsname[$idx[$k]].' ['.$paramssign[$idx[$k]].']';
            }else{
              echo(''.$k.': {targetAxisIndex:0} ,');
            }
          }
          echo('}, '); 
          echo('vAxes:{1:{title:"'.$names2.'" ,minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},},');   
        }
        if ($isTrendline){
          echo('series: {'.$trendidx.': {type: "line",color:"black",lineWidth: 2}},');
        }
        if ($isStacked){
          echo('isStacked: true, ');
        }
        if ($isSmooth){
          echo('curveType: "function",');
        }
        //      legend: { position: "none"},
      echo('
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values'.strval($thetime+$pltnr).'"));
      chart.draw(view, options);
  }
  </script>
<div align="center"  class="center" id="barchart_values'.strval($thetime+$pltnr).'" style="width:'.$graphWidth.'; min-height:200px;height:'.$graphHeight.';max-height:100%;"></div>');
echo('</div></div>');
  }
  $isHisto = false;
}



if (isset($isPie)){}else{
	$isPie = false;
}
if ($isPie){
  
	if (isset($pieplots)){}else{
		$pieplots = $subplots;
	}
	if (isset($is3D)){}else{
		$is3D = false;
	}

  for ($hc=0;$hc<sizeof($pieplots);$hc++){
    $mc = 0;
	if ($pieplots[$hc]<0){
			$pieplots[$hc] = 0;
			$xidx = $hc;
	}    
	if ($pieplots[$hc]>0){
			$pieplots[$hc] = 0;
			$yidx = $hc;
	}    
	if ($pieplots[$hc]==0){
      continue;
    }
  }
  if (true){
	  	
	$elements = array_column($newArr, $params[$xidx]);
	
	$theElements = array_values(array_unique($elements));	
	$piearray = array();
	for ($k=0;$k<sizeof($theElements);$k++){
		$piearray[$k] = 0;
	}
	for ($j=0;$j<sizeof($newArr);$j++){
		for ($k=0;$k<sizeof($theElements);$k++){
			
			if ($theElements[$k] == $elements[$j]){
        if ($yidx==0){
          $val = 1;
        }else{
          $val = abs(floatval($newArr[$j][$params[$yidx]]));
        }
				$piearray[$k] = $piearray[$k] + $val;
			}
		}
	}
  
  $pieChart = "";  
  $pieChart = $pieChart.'["'.$paramsname[$xidx].'","'.$paramsname[$yidx].'"]';
  for ($i=0;$i<sizeof($piearray);$i++){
	  $pieChart = $pieChart.',["'.$theElements[$i].'",'.$piearray[$i].']';
  }
  
  //echo($pieChart);
  $graphHeight = str_replace("px","",$graphHeight);
  // plot pieplt
  $thetime = microtime(true)*10000;
    echo('
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});google.charts.setOnLoadCallback(drawPieChart);
    function drawPieChart() {
      var data = google.visualization.arrayToDataTable(['.$pieChart.']);
      var view = new google.visualization.DataView(data);
      var options = {orientation:"horizontal",backgroundColor:"transparent",'.$clrs.',
	  legend: {position: "top", maxLines: 5},
      fontSize:15,sliceVisibilityThreshold: 0,height:'.$graphHeight.',
      pieHole: 0.1, pieSliceText: "percentage",');
        if ($is3D){
          echo('is3D: true,');
        }
      echo('
      };
      var chart = new google.visualization.PieChart(document.getElementById("piechart_values'.$thetime.$hc.'"));
      chart.draw(view, options);
  }
  </script>
<div align="center" class="center" id="piechart_values'.$thetime.$hc.'" style="width:'.$graphWidth.';max-width:100%;height:'.$graphHeight.';"></div>');
echo('</div></div>');
  }
  $isPie = false;
}


if (isset($isEasyHisto)){}else{
	$isEasyHisto = false;
}
if ($isEasyHisto){
  if ($isTime){
    if (strlen(strpos($referenz,"new Date"))>0){
      $add = ',type:"date"';
    }else{
      $add = ',type:"timeofday"';
    }
    $tadd = ',"timeline"';
  }else{$add = "";$tadd = "";
  }
  $text = '[["Name",{label:"'.$paramsname.'"'.$add.'},{label:"'.$paramsname.'"'.$add.'}]';

    for ($i=2;$i<$teilnehmer+2;$i++){
        /*if ($i==7){
            continue;
        }*/
        if ($isTime){
          if (strlen(strpos($referenz,"new Date"))>0){
            $datestr = 'new Date('.(int)gmdate("Y",$plusminus[$i]).', '.(int)(gmdate("m",$plusminus[$i])-1).
            ', '.((int)gmdate("d",$plusminus[$i])+1).')';
            $text = $text.',["'.$names[$i].'",'.$datestr.', null]';
          }else{
            $datestr = '['.(int)gmdate("H",$plusminus[$i]).', '.(int)gmdate("i",$plusminus[$i]).', 0]';
            $text = $text.',["'.$names[$i].'",'.$datestr.', null]';
          }
            
            
    
        }else{
            $text = $text.',["'.$names[$i].'",'.$plusminus[$i].',""]';
        }
    }
    if ($isTime){
      $text = $text.',["Referenz", null, '.$referenz.']';
    }else{
      $text = $text.',["Referenz", "", '.$referenz.']';
    }
    $text = $text . ']';
//var_dump($text);
      
      
  $thetime = microtime(true)*10000;
    echo('
    
    <script type="text/javascript">
      google.charts.load("visualization", "1.0",  {packages:["corechart"'.$tadd.']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable('.$text.' );
    
        var options = {');
            if (!$isTime){
              echo('chartType:"ComboChart",');
              //title: "'.$paramsname.'",
            }
            echo('legend: { position: "none" },
            fontSize:15,backgroundColor:"transparent",');
            if (!$isTime){
              echo('histogram: { bucketSize: '.$buckets.' },');
            }
            echo('vAxis:{ticks:[]},
            fontName:"Sofia",
            hAxis:{title:"'.$paramsname.'');
            if (!$isTime){
             echo('['.$paramssign.']');
            }
             echo('",');
             if ($isTime){
              echo('ticks:[],');
            }
            if ($minVal!=$maxVal){
              echo('viewWindow: { min:'.$minVal.',max:'.$maxVal.'},');
            }
            echo('titleTextStyle: {bold:"true", italic:"false"}},');
            if ($isTime){
              echo('vAxis:{ticks:[]},');
            }
            echo('            
            colors: ["'.$plotclr.'","gray"],
            seriesType: "bars", 
            series: {
                    0: { targetAxisIndex: 0},
                    1: { targetAxisIndex: 1, type: "line", lineDashStyle: [10, 2]}
                }
        };

        var chart = new google.visualization.Histogram(document.getElementById("easyHisto_'.$thetime.'"));
        chart.draw(data, options);
      }
    </script>');
    echo('<div id="easyHisto_'.$thetime.'" style="width:'.$graphWidth.';display : inline-block;float:none;max-width:100%;height:'.$graphHeight.';"></div>');
    $isEasyHisto = false;//  align="center" class="center" 
}
  
  
  
if (isset($isGeoChart)){}else{
	$isGeoChart = false;
}
if ($isGeoChart){
  
  
	if (isset($geoplots)){}else{
		$geoplots = $subplots;
	}
  
  if (isset($regionCode)){
    if (strlen($regionCode)==0){
      $regionCode = '';
    }else{
      if ($regionCode=="DE"){
        $add = 'resolution:"provinces",';
      }else{$add = '';}
      $regionCode = 'region: "'.$regionCode.'",'.$add;
    }
  }else{
    $regionCode = '';
  }
  if (isset($isMap)){}else{
    $isMap = false;
  }
  if ($isMap){
    $GeoTyp = "Map";
  }else{
    $GeoTyp = "GeoChart";
  }
  // find index of lat (1) lon (2) and counts (3)
  $foundIdx = array(-1,-1,-1);
  for ($parCounter=0;$parCounter<sizeof($paramsname);$parCounter++){
    if ($geoplots[$parCounter]==1){
      $foundIdx[0] = $parCounter;
    }elseif($geoplots[$parCounter]==2){
      $foundIdx[1] = $parCounter;
    }elseif($geoplots[$parCounter]==3){
      $foundIdx[2] = $parCounter;
    }
  } 
  
  $GeoInput = '[[';
  for ($parCounter=0;$parCounter<sizeof($foundIdx);$parCounter++){
    $GeoInput = $GeoInput . '"' . $paramsname[$foundIdx[$parCounter]].'"';
    if ($parCounter<sizeof($foundIdx)-1){
      $GeoInput = $GeoInput.',';
    }
  }
  $GeoInput = $GeoInput . ']';
        for ($i=0;$i<sizeof($newArr);$i++){
            $GeoInput = $GeoInput . ',[';
            for ($parCounter=0;$parCounter<sizeof($foundIdx);$parCounter++){
              $GeoInput = $GeoInput .floatval($newArr[$i][$params[$foundIdx[$parCounter]]]);
              if ($parCounter<sizeof($foundIdx)-1){
                $GeoInput = $GeoInput.',';
              }
            }
            
            $GeoInput = $GeoInput . ']';
			   }
    $GeoInput = $GeoInput . ']';
    
    
    if (isset($RegioColor)){
      }else{
      $RegionColor = 'transparent';
    }
      $thetime = microtime(true)*10000; 
    //var_dump($GeoInput);
	   echo('
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {
        "packages":["geochart"],
      });
      
         google.charts.setOnLoadCallback(drawChart);
         function drawChart() {
            var data = google.visualization.arrayToDataTable('.$GeoInput.');

        var options = {
          legend:"none",
          magnifyingGlass: {enable: true, zoomFactor: 7.5},
          '.$regionCode.'
				  backgroundColor:"transparent",datalessRegionColor: "'.$RegionColor.'",defaultColor: "#00FF00",
          keepAspectRatio: true,
          tooltip: {
              isHtml: true
          },
          colorAxis: {position:"none", colors: ["'.$ActCol["Color"][2].'","'.$ActCol["Color"][1].'"]},
			  };			


            var chart = new google.visualization.'.$GeoTyp.'(document.getElementById("GeoChart_'.$thetime.'"));
            chart.draw(data, options);
         }
      </script>');
    echo('<div id="GeoChart_'.$thetime.'" style="width:'.$graphWidth.';display : inline-block;float:none;max-width:100%;height:'.$graphHeight.';"></div>');
      $isGeoChart = false;
      
  
}

?>
