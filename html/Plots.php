<?php


if (isset($color)){}else{
  $color = "Farben";
}
$clrs = myFuncColor3($color);

// newArr ... array of format: newArr[$i][$params[$k]];
// params ... param and array name: array("date2,"wert");
// paramsname ... name of params: array("Datum,"Geld");
// paramssign ... sign of params: array(","€");

// subplots, histoplots, pieplots, xyplots ... array of length of params
// with -1 for the x-Axes and 1,1.2,2 for the y-Axes.
// for 1.1,1.2 it will plot it and two axes in one plot
// if number exceeds 1, then there is another plot shown for number 2...

if (isset($params)){}else{
	$params = array();return;
}
if (isset($paramsname)){}else{
	$paramsname = array();return;
}
if (isset($paramssign)){}else{
	$paramssign = array();return;
}
if (isset($newArr)){}else{
  $newArr = array();return;
}

if (isset($graphWidth)){}else{
  $graphWidth = "550px";
}
if (isset($graphHeight)){}else{
  $graphHeight = "300px";
}
  
// https://developers.google.com/chart/interactive/docs/gallery
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//
//-----------------------------------------------//
if (isset($isXY)){}else{
	$isXY = false;
}
if (isset($isPoints)){}else{
	$isPoints = false;
}
if (isset($isLine)){}else{
	$isLine = false;
}

// plot Line
if ($isXY or $isLine or $isPoints){
	if (isset($subplots)){}else{
		$subplots = array();
	}
	if (isset($isSmooth)){}else{
		$isSmooth = false;
	}
	if($isPoints or $isLine){
    $startCounter=0;
  }else{ $startCounter=1;}
	if($isXY){
    $xyCounter=2;
  }else{ $xyCounter=1;}

	for ($scatterCounter=$startCounter;$scatterCounter<$xyCounter;$scatterCounter++){
		
    
	if ($scatterCounter == 1){
		$isPoints = true;
		$isLine = false;
		if (isset($xyplots)){}else{
			$xyplots = array();
		}
		$subplots = $xyplots;
	}
  
  
   $plotNumber = max(round(max($subplots)),1);  
  for ($pltnr=1;$pltnr<(intval($plotNumber)+1);$pltnr++){
    $idxarr = array();
    $idx = array();
    $plotidx = array();
    $mc = 0;
    $isSecondaryHisto = false;
    for ($hc=0;$hc<sizeof($subplots);$hc++){
           
      if(round($subplots[$hc])==$pltnr){
        $idx[$mc] = $hc;
        $idxarr[$hc] = 1;
        $plotidx[$mc] = 1;
        if ($subplots[$hc]>($pltnr+0.1) ){
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
    
    // generate the input for the bar plot
  $plotChart = '["'.$paramsname[$xidx].'"';
  for ($k=0;$k<sizeof($idx);$k++){
    $plotChart = $plotChart .',"'.$paramsname[$idx[$k]].'"';
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
				$var = 'new Date('.intval($c[0]).', '.intval($c[1]-1.0).', '.intval($c[2]).')';
			}
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
    $plotChart = $plotChart .']';
  } 
  $thetime = microtime(true)*10000;
	//echo($plotChart);
  // plot barplot
    echo('
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});google.charts.setOnLoadCallback(drawPlotChart);
    function drawPlotChart() {
      var data = google.visualization.arrayToDataTable(['.$plotChart.']);
      var view = new google.visualization.DataView(data);
      var options = {orientation:"horizontal",backgroundColor:"transparent",
	  fontSize:15, 
	  legend: {position: "top", maxLines: 5},
      hAxis: { title: "Datum", titleTextStyle: {italic: false,bold:false},minorGridlines:{color: "none"},gridlines:{color: "#999"},},
      fontSize:15,'.$clrs.',
      vAxis:{title: "'.$paramsname[$idx[0]].' ['.$paramssign[$idx[0]].']", baselineColor:"#000000", 
      minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},
	  labels: ["",');
        for ($k=0;$k<$mc;$k++){
            echo('"'.$paramsname[$idx[$k]].'",');
        }echo('],');
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
          echo('vAxes:{1:{title:"'.$names2.'" , minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},baselineColor:"#000000", }},'); 
        }
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
      echo('
      };
      var chart = new google.visualization.LineChart(document.getElementById("plotchart_values'.strval($thetime+$pltnr+$scatterCounter*100).'"));
      chart.draw(view, options);
  }
  </script>
<div   class="center" id="plotchart_values'.strval($thetime+$pltnr+$scatterCounter*100).'" style="width:'.$graphWidth.'; min-height:200px;height:'.$graphHeight.';"></div>');

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
		$histoplots = array();
	}
	if (isset($isTrendline)){}else{
		$isTrendline = false;
	}
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
  
  ?>
  </br>
  <div class="center" style="overflow-x:hidden;overflow-y:hidden;display:inline-block;margin-top:10px;width:99%;">
    <div >
		<div style="display:inline-block;vertical-align:middle;margin-bottom:0px;display: flex;justify-content: center;">
		  <form action="" method="POST">
			<span id="valBox"></span>
			<input type="range" style="filter:var(--buttom);" min="3" max="48" step="1" 
			value="<?php echo($StepSize);?>" id="foo" 
			name="passengers" onchange='showVal(this.value,"<?php echo($nurl)?>");'/>
			<input type="text" style="background-color:var(--backgray);vertical-align:top;" 
			name="bar" id="bar" value="Slider Value = <?php echo($StepSize);?>" disabled />
		  </form> 
		</div>
    
  
  <script>
  function showVal(newVal,url){
    document.getElementById("valBox").innerHTML=newVal;
    window.location.replace(url + "&StepSize="+newVal);
  }
  </script>
  
  <!-- Slide to choose a different amount of bars.-->
  <?php
  $plotNumber = max(round(max($histoplots)),1);
  
  for ($pltnr=1;$pltnr<(intval($plotNumber)+1);$pltnr++){
    
    $idxarr = array();
    $idx = array();
    $plotidx = array();
    $mc = 0;
    $isSecondaryHisto = false;
    $stackedData = -1;
    for ($hc=0;$hc<sizeof($histoplots);$hc++){
           
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
      }
      $start = min($dates);$end = max($dates);
      $inc = ($end-$start)/($StepSize-1);
      if ($StepSize=="yearly"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $nyc = 0;
        for ($ny = $y1;$ny<=$y2;$ny++){
          $timeArray[$nyc] = $ny;
          $nyc = $nyc + 1;
        }
        $StepSize = $diff;
        $inc = ($end-$start)/($diff);
        $StepOpt = 1;
      }elseif ($StepSize=="monthly"){
        $y1 = date('Y',$start);
        $y2 = date('Y',$end);
        $m1 = date('m',$start);
        $m2 = date('m',$end);
        $diff = (($y2-$y1)*12) + $m2 - $m1 + 1;
        $nyc = 0;
        for ($ny = 0;$ny<$diff;$ny++){
          $timeArray[$nyc] = $y1 + floor(($m1+1+$ny)/12). "-".sprintf("%02d",(($m1+$ny)%12));
          $nyc = $nyc + 1;
        }
        
        $start = (sprintf("%02d",$y1)."-".sprintf("%02d",$m1)."-01");
        $start = strtotime($start);
        $end = (sprintf("%02d",$y2)."-".sprintf("%02d",$m2)."-01");
        $end = strtotime($end);
        $end = strtotime(date("Y-m-t",$end));
        $StepSize = $diff;
        $inc = ($end-$start)/($diff);
        $StepOpt = 2;
      }elseif ($StepSize=="daily"){
        $diff = ceil(($end-$start)/(3600*24));
        $StepSize = $diff;
        $inc = 3600*24;
        $StepOpt = 0;
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
        $val = round( (($dates[$j]-$start)/($inc) ) );
      }else if($StepOpt==1){
        $val = findStringInArrayFast(date("Y",$dates[$j]),$timeArray);
      }else if($StepOpt==2){
        $val = findStringInArrayFast(date("Y-m",$dates[$j]),$timeArray);
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
        $date = gmdate("Y-m-d",( $start + $inc*($i+0.5)));
        $c = explode('-',$date);
        $var = 'new Date('.intval($c[0]).', '.intval($c[1]-1.0).', '.intval($c[2]).')';
      }else{
        $var = $start + $inc*($i+0.5);
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
          $trendidx = sizeof($idx);
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
      seriesType: "bars", 
	  legend: {position: "top", maxLines: 5},
      hAxis: { title: "Datum",minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},
      fontSize:15,bar: { groupWidth: "62%" },
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
          echo('vAxes:{1:{title:"'.$names2.'" ,minorGridlines:{color: "none"},gridlines:{color: "#999"}, titleTextStyle: {italic: false,bold:false},},}');   
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
<div   class="center" id="barchart_values'.strval($thetime+$pltnr).'" style="width:'.$graphWidth.'; min-height:200px;height:'.$graphHeight.';"></div>');
echo('</div></div>');
  }
  
  $isHisto = false;
}



if (isset($isPie)){}else{
	$isPie = false;
}
if ($isPie){
  
	if (isset($pieplots)){}else{
		$pieplots = array();
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
    
  // plot pieplt
  $thetime = microtime(true)*10000;
    echo('
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});google.charts.setOnLoadCallback(drawPieChart);
    function drawPieChart() {
      var data = google.visualization.arrayToDataTable(['.$pieChart.']);
      var view = new google.visualization.DataView(data);
      var options = {orientation:"horizontal",backgroundColor:"transparent",'.$clrs.',
	  legend: {position: "top", maxLines: 5},
      fontSize:15,sliceVisibilityThreshold: 0,
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
<div   class="center" id="piechart_values'.$thetime.$hc.'" style="width:'.$graphWidth.'; min-height:200px;height:'.$graphHeight.';"></div>');
echo('</div></div>');
  }
  $isPie = false;
}


?>
