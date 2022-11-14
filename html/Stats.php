<html>
<head>
<style>
div.scroll {
  width:100%;
  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
}

th, td{
  height: 30px;
  margin: 0px;
  padding: 0px 6px;
  font-size:var(--font_medium);
  word-wrap: break-word;        
  overflow-wrap: break-word;
  max-width:200px;
}
tr{
  margin: 0px;
  padding: 0px;
}

</style>
</head>

<body>
  <?php
  
  include("body.php");
  
	if (isset($_GET["Menu"])){
		$Menu = htmlspecialchars($_GET["Menu"]);
	}else{
		$Menu = "";
	}
    
	if (isset($_GET["NewStat"])){
		$NewStat = htmlspecialchars($_GET["NewStat"]);
	}else{
		$NewStat = "";
	}
	if (isset($_GET["Opt"])){
		$Opt = htmlspecialchars($_GET["Opt"]);
	}else{
		$Opt = "";
	}
	if (isset($_GET["Submit"])){
		$Submit = htmlspecialchars($_GET["Submit"]);
	}else{
		$Submit = "";
	}
	if (isset($pwdString)){
	}else{
		$pwdString = "";
	}
	if (isset($_REQUEST['nummer'])){
		$pos = $_REQUEST['nummer'];
	}
  
	
	

  $baseurl = "Stats.php";
  $fullurl = $_SERVER['REQUEST_URI'];
	
  $url = "Stats.php";
  
  echo('<div class="scrollmenu_bright" style="background-color:var(--mediumgray);width:100%;display:flex;overflow:auto;">');
  
  $Stats_Params = json_decode(file_get_contents('data/Stats_Params.json'), true);
  $keys = array_keys($Stats_Params);
  
  for ($i=0;$i<sizeof($keys);$i++){
    $var = $keys[$i];
    echo('<a style="');
    if ($Menu == $var){echo('background-color:var(--green)');}
    echo('" href="Stats.php?Menu='.$var.$pwdString.'"> '.$var.' </a>');
  }
  if ($EditVal){
	echo('<a style="background-color:var(--darkgray)" href="Stats.php?Menu=add'.$pwdString.'"> + </a>');
  }

  echo('</div>');
  
  
      $FileName = "";
  SiteSection($Menu, "var(--lightgray)",6);
  if (array_key_exists($Menu,$Stats_Params)){
      $FileName = $Stats_Params[$Menu]["Filename"];
      $param = $Stats_Params[$Menu]["param"];
      $paramssign = $Stats_Params[$Menu]["paramssign"];
      $paramsname = $Stats_Params[$Menu]["paramsname"];
      $averageCalc = $Stats_Params[$Menu]["averageCalc"][0];
      $averageVals = $Stats_Params[$Menu]["averageCalc"][1];
	  if (array_key_exists('isSmooth',$Stats_Params[$Menu])){
		$isSmooth = $Stats_Params[$Menu]["isSmooth"];
	  }else{
		  $isSmooth = false;
	  }
	  if (array_key_exists('isSmooth',$Stats_Params[$Menu])){
		$isSmooth = $Stats_Params[$Menu]["isSmooth"];
	  }else{
		  $isSmooth = false;
	  }
	  if (array_key_exists('isTrendline',$Stats_Params[$Menu])){
		$isTrendline = $Stats_Params[$Menu]["isTrendline"];
	  }else{
		  $isTrendline = true;
	  }
	  if (array_key_exists('isStacked',$Stats_Params[$Menu])){
		$isStacked = $Stats_Params[$Menu]["isStacked"];
	  }else{
		  $isStacked = false;
	  }
	  
      $subplots = $Stats_Params[$Menu]["subplots"];
      $isPoints = $Stats_Params[$Menu]["isPoints"];
      $isLine = $Stats_Params[$Menu]["isLine"];
      $isHisto = $Stats_Params[$Menu]["isHisto"][0];
      $histoplots = $Stats_Params[$Menu]["isHisto"][1];
      $isXY = $Stats_Params[$Menu]["isXY"][0];
      $xyplots = $Stats_Params[$Menu]["isXY"][1];
	  if (array_key_exists('isPie',$Stats_Params[$Menu])){
		  $isPie = $Stats_Params[$Menu]["isPie"][0];
		  $pieplots = $Stats_Params[$Menu]["isPie"][1];
	  }else{
		$isPie = false;$pieplots = array();
	  }
	  
  }elseif($Menu=="add"){
      $EditVal = true;
      $FileName = "";
      $param = "";
      $params = "";
      $paramssign = "";
      $paramsname = "";
      $averageCalc = false;
      $averageVals = array();
      $isSmooth = false;
	  $isStacked = false;
	  $isTrendline = false;
	  
      $subplots = array();
      $isPoints = false;
      $isLine = false;
      $isHisto = false;$histoplots = array();
      $isXY = false;$xyplots = array();
      $isPie = false;$pieplots = array();
  }
  
  if ($EditVal or strlen($NewStat)>0){
    if($NewStat=="true"){
      
        $newStats = array();
        $theTitle = trim($_REQUEST["thetitle"]);
        $newStats["Filename"] = trim($_REQUEST["thefilename"]);
        $newStats["param"] = trim($_REQUEST["theparamstyp"]);
        $newStats["paramssign"] = trim($_REQUEST["theparamssign"]);
        $newStats["paramsname"] =  trim($_REQUEST["theparamsname"]);
        $newStats["subplots"] = explode(',',trim($_REQUEST["thesubplots"]));
        $newStats["isPoints"] = trim($_REQUEST["thepoints"])=="true";
        $newStats["isLine"] = trim($_REQUEST["theline"])=="true";
        $newStats["isSmooth"] = trim($_REQUEST["thesmooth"])=="true";
        $newStats["averageCalc"][0] = trim($_REQUEST["theaverage"])=="true";
        $newStats["averageCalc"][1] = explode(',',trim($_REQUEST["theaveragevals"]));
        $newStats["isHisto"][0] = trim($_REQUEST["thehisto"])=="true";
        $newStats["isHisto"][1] = explode(',',trim($_REQUEST["thehistoplots"]));
        $newStats["isXY"][0] = trim($_REQUEST["thexy"])=="true";
        $newStats["isXY"][1] = explode(',',trim($_REQUEST["thexyplots"]));
        $newStats["isPie"][0] = trim($_REQUEST["thepie"])=="true";
        $newStats["isPie"][1] = explode(',',trim($_REQUEST["thepieplots"]));
        //var_dump($newStats);
        $Stats_Params[$theTitle] = $newStats;
        //var_dump($newStats);
        $jsonStats = json_encode($Stats_Params);
        if (json_last_error() === JSON_ERROR_NONE){
          echo("Json Valid!");
          file_put_contents('data/Stats_Params.json',$jsonStats);
		  
		  if (!file_exists($newStats["Filename"].'.json')){
			file_put_contents($newStats["Filename"].'.json',json_encode(array()));
		  }
          
          echo  "<script type='text/javascript'>";
          echo 'window.location.href="Stats.php?Menu='.$theTitle.$pwdString.'"';
          echo "</script>";
        }else{
          echo("Error in your .json file!");
        }
    }elseif($NewStat=="false"){
      $theTitle = trim($_REQUEST["Menu"]);
      $theFilename = $FileName;
      echo($FileName);
      unset($Stats_Params[$theTitle]);
      $jsonStats = json_encode($Stats_Params);
      if (json_last_error() === JSON_ERROR_NONE){
          echo("Json Valid!");
          file_put_contents('data/Stats_Params.json',$jsonStats);
          
          echo("Delete: ".$theFilename);
          unlink($theFilename);
          echo  "<script type='text/javascript'>";
          echo 'window.location.href="Stats.php'.$pwdString.'"';
          echo "</script>";
      }
    }else{$theTitle = $Menu;}
    
    echo('
    <center></br>Please enter your settings and submit them!<div style="width:100%;float:left;"><table style="width:99%;float-left;display:inline;">');
      echo('<tr><td>New Title: </td><td><input type="text" class="ffield" style="min-width:200px;" name="thetitle" value="'.$Menu.'" /></td></tr>');
      echo('<tr><td>Filename [.json]: </td><td><input type="text" class="ffield" style="min-width:200px;" name="thefilename" value="'.$FileName.'" /></td></tr>');
      echo('<tr><td>Field names: [Datum,Geld,Hinweis,Ursache,Aktiv] </td><td><input type="text" class="ffield" style="min-width:200px;" name="theparamsname" value="'.$paramsname.'" /></td></tr>');
      echo('<tr><td>Field typ: [datum,wert,wert2,text,check,dropdown]</td><td><input type="text" class="ffield" style="min-width:200px;" name="theparamstyp" value="'.$param.'" /></td></tr>');
      echo('<tr><td>Field units: [,€,,,]</td><td><input type="text" class="ffield" style="min-width:200px;" name="theparamssign" value="'.$paramssign.'" /></td></tr>');
      echo('<tr><td>Subplots: [0,1.1,1.2,2,0]</td><td><input type="text" class="ffield" style="min-width:200px;" name="thesubplots" value="'.implode(',',$subplots).'" /></td></tr>');
      if ($isPoints){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Points: </td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="thepoints" '.$add.'/></td></tr>');
      if ($isLine){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Line: </td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="theline" '.$add.'/></td></tr>');
      if ($averageCalc){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Average: </td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="theaverage" '.$add.'/>
      <input type="text" class="ffield" style="min-width:150px;" name="theaveragevals" value="'.implode(',',$averageVals).'" /></td></tr>');
      if ($isSmooth){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Smooth: </td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="thesmooth" '.$add.'/></td></tr>');
      if ($isHisto){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Histogram: [0,1.1,1.2,2,0]</td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="thehisto" '.$add.'/>
      <input type="text" class="ffield" style="min-width:150px;" name="thehistoplots" value="'.implode(',',$histoplots).'" /></td></tr>');
      if ($isXY){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>XY-Plot: [0,0,-1,0,1]</td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="thexy" '.$add.'/>
      <input type="text" class="ffield" style="min-width:150px;" name="thexyplots" value="'.implode(',',$xyplots).'" /></td></tr>');
      if ($isPie){$add = "checked";}else{$add = "unchecked";}
      echo('<tr><td>Pie-Plot: [0,0,-1,0,1]</td><td><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;border-radius:6px;" name="thepie" '.$add.'/>
      <input type="text" class="ffield" style="min-width:150px;" name="thepieplots" value="'.implode(',',$pieplots).'" /></td></tr>');


$newarr = array("thetitle","thefilename","theparamsname","theparamstyp","theparamssign","thesubplots","thepoints","theline","theaverage","theaveragevals","thehisto","thehistoplots","thexy","thexyplots","thepie","thepieplots");
$parameter = implode(',',$newarr); 

    ?>
<script>
function setStats() {
            
	var url = "Stats.php?NewStat=true";
  var params = "<?php echo"$parameter"?>";
  var pwdString = "<?php echo"$pwdString"?>";
	narray = params.split(",");
    
	el = document.getElementsByName("thetitle");
	var PostString = "&Menu="+el[0].value;
    for (i = 0; i < narray.length; i++) {
		el = document.getElementsByName(narray[i]);
		val = el[0].value;
		if (val=="on"){
			if (el[0].checked){
				val="true";
			}else{
				val="false";
			}
		}
		PostString = PostString + "&" + narray[i] + "=" + val;
	}
	//alert(url+PostString);
	//document.getElementById();
    let confirmAction = confirm(url+PostString+"Do you want to generate this stats?");
    if (confirmAction) {
        window.open(url+PostString+pwdString,"_self");//,"_self"
    }
}
</script><?php


	echo($Menu);
	echo('</div>

    <tr><td><button class="ffield" style="font-size:var(--font_medium);width:120px;" onclick="setStats()">Sets</button></td>');
	if ($Menu=="add"){
		echo("<td></td>");
	}else{
		echo('<td><a class="ffield" style="font-size:var(--font_medium);width:120px;" href="Stats.php?NewStat=false&Menu='.$theTitle.$pwdString.'">Delete</a></td>');
	}
    echo('</tr></table>
    
</br></br></br>
    </center>');
  }
  
if (strlen($Menu)>0){
    $params = explode(',',$param);  
    $paramssign = explode(',',$paramssign); 
    $paramsname = explode(',',$paramsname); 
  
    for ($j=0;$j<sizeof($paramssign);$j++){
      echo('<style>
      .'.$paramsname[$j].'::after{
      content: "'.$paramssign[$j].'";
      margin-left: -40px;
      margin-right: 10px;
      }
      </style>');
    } 
  $name = str_replace("Wert_","",$param);
  $names = explode(',',$name); 
  if (file_exists($FileName.'.json')){
	  
    if (isset($_REQUEST["Update"])){
        $newArr = json_decode(file_get_contents($FileName.'_tmp.json'), true);
        file_put_contents($FileName.'.json',json_encode($newArr));
    }else{
        $newArr = json_decode(file_get_contents($FileName.'.json'), true);
    }
	  $len = sizeof($newArr);
  }else{
	  $newArr = array();
	  $len = 0;
  }
  
  echo('<div style="display:inline-block;position:relative;overflow-x: scroll;width:100%;overflow-y:hidden; padding-bottom:20px;margin-bottom:-8px;">');
  // -------------------------------- //
  // Filter
  
	if ($FilterVal){
		echo('<p>Wähle eine Filtereinstellung:</p>');
		$tmpurl = $fullurl;
		if (isset($_GET["RegExp"])){
			$RegExp = $_GET["RegExp"];
			$tmpurl = str_replace("&RegExp=".$RegExp,'',$tmpurl);
		}else{
			$RegExp = "";
		}
		echo('<input type="text" class="ffield" value="'.$RegExp.'" name="RegExp">
		<select class="ffield" style="width:100px;" type="select" name="FilterField" >');
		if (isset($_GET["FilterField"])){
			$FilterField = $_GET["FilterField"];
			$tmpurl = str_replace("&FilterField=".$FilterField,'',$tmpurl);
			echo('<option value="'.$FilterField.'">'.$FilterField.'</option>');
		}else{
			echo('<option value=" - "> - </option>');
			$FilterField = '';
		}
		for($k=0;$k<sizeof($params);$k++){
			echo('<option value="'.$params[$k].'"> '.$paramsname[$k].' </option>');
		}
      
        echo('"</select>');
		echo('<button class="ffield" style="font-size:var(--font_medium);" onclick="filterData()">Anwenden</button>');
		
		if (strlen($FilterField)>0 and strlen($RegExp)>0){
			$filterArr = array();$cc = 0;
			for ($i=0;$i<sizeof($newArr);$i++){
				if (preg_match('/'.$RegExp.'/',$newArr[$i][$FilterField])){
					$filterArr[$cc] = $newArr[$i];
					$cc = $cc + 1;
				}
			}
			$newArr = $filterArr;
		}
?>
		<script>		  
		function filterData() {
			var url = "<?php echo"$tmpurl"?>";
			var pwdString = "<?php echo"$pwdString"?>";
			RegExp = document.getElementsByName("RegExp")[0].value;
			FilterField = document.getElementsByName("FilterField")[0].value;
			var url = url + '&RegExp=' + RegExp + '&FilterField=' + FilterField;
			
			window.open(url+pwdString,"_self");
		}
		</script>
<?php
		
	}
  
  if (isset($_GET["Sort"])){
		$tmpStr = 'array_multisort (array_column($newArr, "'.$_GET["Sort"].'"), '.$_GET["Order"].', $newArr);';
		eval($tmpStr);
	  
	  if ($_GET["Order"]=="SORT_ASC"){
		$order = "SORT_DESC";
	  }else{
		$order = "SORT_ASC";
	  }
  }else{
      array_multisort (array_column($newArr, "Nummer"), SORT_DESC, $newArr);
	  $order = "SORT_DESC";
  }
  
  if (!$EditVal){
    
    

  
  echo('
  <table class="center" id="customers"  style="align:left;width: 1%; white-space: nowrap;border-collapse: collapse;">
  <tr>
  <th><a href="'.$baseurl.'?Menu='.$Menu.'&Sort=Nummer&Order='.$order.$pwdString.'">Nr.</a></th>');
  for ($i=0;$i<sizeof($params);$i++){
    echo('<th><a href="'.$baseurl.'?Menu='.$Menu.'&Sort='.$params[$i].'&Order='.$order.$pwdString.'">'.$paramsname[$i].'</a></th>');
  }
  if ($averageCalc){
    echo('<th>Durchschnitt</th>');
  }
  echo('</tr>');
  // start with all input fields
  if (!$FilterVal){
	  echo('<tr><td>');
	  if (isset($_REQUEST['nummer'])){
      $pos = $_REQUEST['nummer'];
	  }else{
      $pos = $len;
	  }
	  echo($pos); 
	  echo('</td>');  
	  // all values, dates ...
	  for ($i=0;$i<sizeof($params);$i++){
		  // only for dropdown
		  if (strlen(strpos(strtolower($params[$i]),'dropdown'))>0){
			$tmp = $params[$i];
			$c = explode('; ',$tmp);
			$params[$i] = $c[0];
			$tmppar = explode('; ',$tmp);
			$tmppar[0] = " - ";
			//var_dump($tmppar);
		  }
		// datum
		if (strtolower($params[$i])=='datum'){
		  echo('<td><input type="date" class="ffield"  style="width:140px;" name="'.$params[$i].'"
		  value="');
		  if ($Opt==1){
			echo(htmlspecialchars($_GET[($params[$i])]));
		  }else{echo("-");}
			echo('"/></td>');
		  // wert
		}elseif (strlen(strpos(strtolower($params[$i]),'wert'))>0){
		  echo('<td style="padding-right:30px;"><div class="'.$paramsname[$i].'"><input class="ffield" style="width:100px;" type="number" step="0.0001" value="');
		  if ($Opt==1){
			echo(htmlspecialchars($_GET[($params[$i])]));
		  }else{echo("-");}
			echo('" min="-1000000.00" max="1000000.00" step="0.001" name="'.$params[$i].'" /></div></td>'); 
			// dropdown
		}elseif (strlen(strpos(strtolower($params[$i]),'dropdown'))>0){
		  echo('<td style="">
		  <select class="ffield" style="width:100px;" type="select" name="'.($params[$i]).'" >');
		  
		  if (htmlspecialchars($_GET["Opt"])==1){
			echo('<option value="'.htmlspecialchars($_GET[($params[$i])]).'">'.htmlspecialchars($_GET[($params[$i])]).'</option>');
		  }else{
			echo('<option value=" - "> - </option>');
		  }
		  for($k=0;$k<sizeof($tmppar);$k++){
			  echo($tmppar[$k]);
			  echo('<option value="'.$tmppar[$k].'"> '.$tmppar[$k].' </option>');
		  }
      
        echo('"</select></td>');
      // checkbox
		}elseif (strlen(strpos(strtolower($params[$i]),'check'))>0){
			echo('<td style="text-align:center;"><input type="checkbox" class="ffield" style="--webkit-appearance: none;width:20px;height:20px;
			border-radius:6px;"');
		  if (htmlspecialchars($_GET["Opt"])==1){
			if ($_GET[($params[$i])]){echo("checked");
			}else{echo("unchecked");}
		  }else{echo("unchecked");}
		  echo(' name="'.$params[$i].'" /></td>');
			  // random string
		}else{   
		  echo('<td><input type="text" class="ffield" value="');
		  if ($Opt==1){
		  echo(htmlspecialchars($_GET[($params[$i])]));
		  }else{echo("-");}
		  echo('"name="'.$params[$i].'" /></td>');
		}
	  }
	  
	  if ($averageCalc){
		echo('<td>Durchschnitt</td>');
	  }
	  
	  // Absenden button //<form method="post">
	  //echo('<td><input type="submit" class="ffield" style="font-size:var(--font_medium);" name="absenden"/></td>');
	  echo('<td><button class="ffield" style="font-size:var(--font_medium);" onclick="writeData()">Absenden</button></td>');
	   
	echo('</tr>');
  }// end of is $FilterVal
      $tlen = sizeof($newArr);
      
      for ($i=0;$i<sizeof($newArr);$i++){
        $twert = ($tlen-$i-1.0);
        
        echo('<tr><td>'.$newArr[$i]["Nummer"].'</td>');
        for ($j=0;$j<sizeof($params);$j++){
          if (array_key_exists($params[$j],$newArr[$i])  ){
            echo('<td><a href="'.$baseurl.'?Menu='.$Menu.'&FilterVal=true&RegExp='.$newArr[$i][$params[$j]].'&FilterField='.$params[$j].$pwdString.'">'.$newArr[$i][$params[$j]]);
            if (strlen(strpos(strtolower($params[$j]),'wert'))>0 and (strlen($newArr[$i][$params[$j]])>0)){
              echo(" ".$paramssign[$j]);
            }
            echo('</a></td>');
          }else{
            echo('<td></td>');
          }
        }
        if ($averageCalc){
          $theKeys = array_keys($newArr[$i]);
          if ($newArr[$i][$theKeys[$averageVals[1]]]!=0){
              echo('<td>'.round($averageVals[2]*$newArr[$i][$theKeys[$averageVals[0]]]/$newArr[$i][$theKeys[$averageVals[1]]],2).'</td>');
          }
        }
        
        echo('<td style="padding:0px 6px;padding-right:0px;"><a style="text-decoration:none;color:var(--mainText);margin: 4px 0px;padding:3px;" class="ffield" href="Stats_mod.php?FileName='.$FileName.'&Menu='.$Menu.'&Mod=0&nummer='.$twert.'&params='.$param.$pwdString.'" >-</a>
        <a style="text-decoration:none;color:var(--mainText);margin: 4px 0px;padding:3px;"  class="ffield" href="Stats_mod.php?FileName='.$FileName.'&Menu='.$Menu.'&Mod=1&nummer='.$twert.'&params='.$param.$pwdString.'" >m</a>
        <a style="text-decoration:none;color:var(--mainText);margin: 4px 0px;padding:3px;"  class="ffield" href="Stats_mod.php?FileName='.$FileName.'&Menu='.$Menu.'&Mod=2&nummer='.$twert.'&params='.$param.$pwdString.'" >+</a>
        </td></tr>');//<a style="text-decoration:none;color:var(--mainText);margin: 4px 0px;padding:3px;"  class="ffield" href="Stats_mod.php?FileName='.$FileName.'&Menu='.$Menu.'&Mod=4&nummer='.$twert.'&params='.$param.'" >ins</a>
      }
      
      echo('</table>');
	  
    
    if(isset($_REQUEST["Submit"])){
        $newArr[$tlen]["Nummer"] = $pos;
        for ($i=0;$i<sizeof($params);$i++){
          echo($_REQUEST[($params[$i])]);
          $newArr[$tlen][$params[$i]] = trim($_REQUEST[($params[$i])]);
        }
        //echo($tlen);
        //var_dump($newArr);
        array_multisort (array_column($newArr, "Nummer"), SORT_ASC, $newArr);
        file_put_contents($FileName.'_tmp.json',json_encode($newArr));
        
        echo  "<script type='text/javascript'>";
        //echo 'window.location.href="Stats.php?Menu='.$Menu.'"';
        echo 'window.open("Stats.php?Update=true&Menu='.$Menu.$pwdString.'","_self")';
        echo "</script>";
		exit;
    }
	
  }

if (isset($_GET["PlotColor"])){
  $color = $_GET["PlotColor"];
}else{
  $color = "Farben";
}
//$color = "Safari";  
include("Plots.php");

  echo('</div>');
}// is xy plot


?>

<script>

function writeData() {
    var url = "<?php echo"$baseurl"?>";
    var params = "<?php echo"$param"?>";
    var pwdString = "<?php echo"$pwdString"?>";
    narray = params.split(",");
    var Menu = "<?php echo"$Menu"?>";
    var pos = "<?php echo"$pos"?>";
    var PostString = "?Menu="+Menu+"&Submit=true&nummer="+pos;
    for (var i = 0; i < narray.length; i++) {
      el = document.getElementsByName(narray[i]);
      val = el[0].value;
      PostString = PostString + "&" + narray[i] + "=" + val;
    }
    //alert(PostString);
    window.open(url + PostString + pwdString,"_self");
}

</script>

<?php
  SiteSection("", "",-1);
  include("footer.php");
  ?>

</body>

</html>
