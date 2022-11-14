<?php

    
function replaceStrings($name,$arg){
	
	
	if ($arg==0){
		$newName = str_replace(" ","\ ",$name);
		$newName = str_replace("(","\(",$newName);
		$newName = str_replace(")","\)",$newName);
		$newName = str_replace("[","\[",$newName);
		$newName = str_replace("]","\]",$newName);
		$newName = str_replace("'","\'",$newName);
		$newName = str_replace("-","\-",$newName);
	}else{
		$newName = str_replace("\ "," ",$name);
		$newName = str_replace("\(","(",$newName);
		$newName = str_replace("\)",")",$newName);
		$newName = str_replace("\[","[",$newName);
		$newName = str_replace("\]","]",$newName);
		$newName = str_replace("\'","'",$newName);
		$newName = str_replace("\-","-",$newName);
		
	}
	return $newName;
}

function deleteStrings($name){
	
	$newName = str_replace("\ "," ",$name);
	$newName = str_replace("\(","(",$newName);
	$newName = str_replace("\)",")",$newName);
	$newName = str_replace("\[","[",$newName);
	$newName = str_replace("\]","]",$newName);
	$newName = str_replace("\'","'",$newName);
	$newName = str_replace("\-","-",$newName);
	$newName = str_replace("[pleer]","",$newName);
	$newName = str_replace("[pleer","",$newName);
		
	return $newName;
}


function SongPlayed($Song){
	
	
$dir = "data/AllSongs.dat";
$rr = file_get_contents($dir);
$alls = explode("\n","\n".$rr);
for ($i=0;$i<sizeof($alls);$i++){                
        $songs[$i] = $alls[$i+1];
}
$counter = arrayInside($Song,$songs,0);
	return $counter;
}


// check if exact string is inside an array of exact strings
function exactArray($Array,$Entry){
    $c = 0;
    for ($i=0;$i<sizeof($Array);$i++){
    $tmp = $Array[$i];
      if ($tmp == $Entry){
	$c = 1;
	return(1);
	break;
      }
      
    }
    if ($c==0){
      return(0);
    }else{
      return(1);
    }
}



// check if an string is inside an array of strings
function insideArray($Array,$Entry){
    $c = 0;
    //echo("</br>");
    
    for ($i=1;$i<sizeof($Array);$i++){
    $tmp = $Array[$i];
    //echo(sizeof($Array).":".$tmp." vs. ".$Entry."</br>");
      if ($tmp== $Entry){
	$c = 1;
	return(0);
	break;
      }
      
    }
    if ($c==0){
      return(0);
    }else{
      return(1);
    }
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

// function to check if inside array
// the $flag=1 is for only returning 1 for inside and 0 for not
// the $flag=0 also counts the appearance.
function arrayInside($tmpName,$tmpArray,$flag){
    $counter = 0;
    for ($i=0;$i<sizeof($tmpArray);$i++){
	if (strlen(strpos(trim($tmpName), trim($tmpArray[$i])))>0){
	    if ($flag==1){
		return 1;
	    }
	    $counter = $counter + 1;
	}elseif (strlen(strpos(trim($tmpArray[$i]), trim($tmpName)))>0){
	    if ($flag==1){
		return 1;
	    }
	    $counter = $counter + 1;
	}
    }
    return $counter;
}


// function to check if inside array
function findStringInArray($tmpName,$tmpArray,$flag){
$counter = 0;
$cArray = array();
  for ($i=0;$i<sizeof($tmpArray);$i++){
      if (strlen(strpos($tmpArray[$i],$tmpName))>0){
      	if($flag==1){
          return $i;
        }
        array_push($cArray,$i);
      }
  }
  if($flag==1){
  	return -1;
  }else{
  	return $cArray;
  }
}


// function to check if inside array
function findStringInArrayFast($tmpName,$tmpArray){
  for ($i=0;$i<sizeof($tmpArray);$i++){
      if (strlen(strpos($tmpArray[$i],$tmpName))>0){
          return $i;
      }
  }
  return -1;
}

function findStringInArrayFastReverse($tmpName,$tmpArray){
  for ($i=0;$i<sizeof($tmpArray);$i++){
      if (strlen(strpos(strtolower($tmpName),strtolower($tmpArray[$i])))>0){
          return $i;
      }
  }
  return -1;
}	

function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"]);
}
if(isMobileDevice()){
    //echo "Mobile Browser Detected";
    return 1;
}
else {
    //echo "Mobile Browser Not Detected";
    return 0;
}

function convertTime($dur){
    $s = $dur%60;
    $m = (($dur-$s)/60)%60;
    $h = ($dur-$s-$m*60)/3600;
    $str = $h."h:".$m."m:".$s."s";
    //echo ($str . "<br>");
    return($str);
}
    
    
// function to write a log file    
function writeLog($logname,$strings){
    
    
    $currentTimeVal = gmdate("Y-m-d") . "T" . gmdate("H:i:s.u") . "Z";
    $file = "data/Log/".$logname.'.log';
    $Saved_File = fopen($file, 'a');
    fwrite($Saved_File, "\r\n".$currentTimeVal.";". $strings);
    fclose($Saved_File);
}


function includeWithVariables($filePath, $variables = array(), $print = true)
{
    $output = NULL;
    if(file_exists($filePath)){
        // Extract the variables to a local namespace
        extract($variables);

        // Start output buffering
        ob_start();

        // Include the template file
        include $filePath;

        // End buffering and return its contents
        $output = ob_get_clean();
    }
    if ($print) {
        print $output;
    }
    return $output;

}


function calculateHistogramBins($Song,$Array,$fac){
    
    $vec = findStringInArray($Song,$Array,0); 
    
    $timeVals = array();
    $l = sizeof($vec);
    $c = explode(',',$Array[$vec[0]]);
    for ($i=0;$i<$l;$i++){
	$c = explode(',',$Array[$vec[$i]]);
	array_push($timeVals,strtotime($c[0]));
    }
    //2020-04-20T18:12:31.000000Z equal to: 
    $startTime = strtotime("2020-04-20");
    $diff = strtotime("now")-$startTime;
    $steps = round($diff/$fac);
    
    $theArg = '"'.gmdate("Y-m-d H:i:s", ($step/2+$startTime) );
    $cc = 0;$nc = 0;
    for ($j=0;$j<$fac;$j++){
	$cc = 0;
	$time_end = ($j+1)*$steps;
	if ($j>0){
	    $theArg = $theArg.'+"'.gmdate("Y-m-d H:i:s", ($j*$steps+$steps/2+10000+$startTime) );
	}
	//echo(":".$j.";".($j*$steps+$step/2+$startTime).",".$nc."</br>");
	$oldnc = $nc;$theAdd = '';
	for ($k = $oldnc;$k<sizeof($timeVals);$k++){
	    if ( ($timeVals[$nc]-$startTime)<$time_end){
		if ($nc==sizeof($timeVals)-1){
		    $theAdd = $theAdd.','.($cc+1).'\n"';
		}
	    }else{
		$theAdd = $theAdd.','.($cc).'\n"';
		$cc = 0;
		break 1;
	    }
	    $nc = $nc + 1;
	    $cc = $cc + 1;
	}
	if (strlen($theAdd)==0){
	    $theArg = $theArg.',0\n"';
	}else{
	    $theArg = $theArg.$theAdd;
	}
	    
    }
    echo($theArg);
    
    
    
}


function myFuncColor(){
	$Parameters = json_decode(file_get_contents('data/Parameters.json'), true);
	$c = $Parameters["Color"];
	$Colors = json_decode(file_get_contents('data/Colors.json'), true);
	$colArr = $Colors[trim($c)]["Color"];
          echo('colors: ["'.$colArr[0].'", "'.$colArr[1].'", "'.$colArr[2].'"]');
	  /*
        if (trim($c) == "Black"){
          echo('colors: ["#955295", "#B484B4", "#D5B2D5"]');
        }else if (trim($c) == "Orange"){
          echo('colors: ["#FF8C00", "#FFD700", "#F9E076"]');
        }else if (trim($c) == "Autumn"){
          echo('colors: ["#c58b2a", "#ECA326", "#bd5734"]');
        }else if (trim($c) == "Green"){
          echo('colors: ["#228B22", "#86af49", "#405d27"]');
        }else if (trim($c) == "Blue"){
          echo('colors: ["#4682B4", "#89CFF0", "#2196F3"]');
        }else if (trim($c) == "Custom"){
          echo('colors: ["#a2b9bc", "#b2ad7f", "#a78fb9"]');
        }else if (trim($c) == "Red"){
          echo('colors: ["#d16644", "#925946", "#bf8f72"]');
        }else if (trim($c) == "Matlab"){
          echo('colors: ["#0072be", "#eeb220", "#76ac30"]');
       }else if (trim($c) == "Safari"){
          echo('colors: ["#c58b2a", "#6B8E23", "#7c4f26"]');
       }*/
      
  }
  
  function myFuncColor3($color){
	$Colors = json_decode(file_get_contents('data/Parameters.json'), true);
	$colArr = $Colors[trim($color)]["Color"];
	$str = 'colors: ["'.$colArr[0].'", "'.$colArr[1].'", "'.$colArr[2].'","'.$colArr[3].'","'.$colArr[4].'","'.$colArr[5].'","'.$colArr[6].'","'.$colArr[7].'",
	"'.$colArr[8].'","'.$colArr[9].'"]';
	  /*
        if ($color == "Black"){
          $str = 'colors: ["#955295", "#B484B4", "#D5B2D5","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Orange"){
          $str = 'colors: ["#FF8C00", "#FFD700", "#F9E076","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Autumn"){
          $str = 'colors: ["#c58b2a", "#ECA326", "#bd5734","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Green"){
          $str = 'colors: ["#228B22", "#86af49", "#405d27","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Blue"){
          $str = 'colors: ["#4682B4", "#89CFF0", "#2196F3","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Custom"){
          $str = 'colors: ["#a2b9bc", "#b2ad7f", "#a78fb9","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Red"){
          $str = 'colors: ["#d16644", "#925946", "#bf8f72","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Matlab"){
          $str = 'colors: ["#0072be", "#eeb220", "#76ac30","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
        }else if ($color == "Safari"){
          $str =  'colors: ["#c58b2a", "#6B8E23", "#7c4f26","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
	}else {
	  $str = 'colors: ["#4682B4","#a2b9bc","#b2ad7f","#a78fb9","#86af49","#c58b2a","#bd5735","#CAB577","#405d27","#d16644"]';
	}*/
	//echo($str);
	return($str);
  }
  
  
function removeArrayLine($arr,$num){
    $newArr = array();
    $cc = 0;
        for ($i=0;$i<sizeof($arr);$i++){
	    if ($i!=$num){
		$tmp = $arr[$i];
		$newArr[$cc] = $tmp;
		$newArr[$cc]["Nummer"] = $i;
		$cc = $cc+1;
	    }
	}
	return $newArr;
		
  }
  
    
function removeArrayPos($arr,$num){
    $newArr = array();
    $num = intval($num);
    $cc = 0;
        for ($i=0;$i<sizeof($arr);$i++){
	    if ($i!=$num){
		$tmp = $arr[$i];
		$newArr[$cc] = $tmp;
		$cc = $cc+1;
	    }
	}
	return $newArr;
		
  }
  
  
  
function plotHistogram($dates,$data,$fac,$start,$stop){
    
    for ($i=0;$i<sizeof($dates);$i++){
      $dates[$i] = strtotime($dates[$i]);
    }
    $timeVals = $dates;
    //echo(strlen($start));
    //2020-04-20T18:12:31.000000Z equal to: 
    if (strlen($start)>2){
	$startTime = $start;
    }else{
	$startTime = strtotime("2020-04-20");
    }
    if (strlen($end)>2){
	$endTime = $stop;
    }else{
	$endTime = strtotime("now");
    }
    $diff = $endTime-$startTime;
    $steps = round($diff/$fac);
    
    $theArg = '"'.gmdate("Y-m-d H:i:s", ($step/2+$startTime) );
    $cc = 0;$nc = 0;
    $arr = array();
    for ($j=0;$j<$fac;$j++){
	$cc = 0;
	$time_end = ($j+1)*$steps;
	if ($j>0){
	    $theArg = $theArg.'+"'.gmdate("Y-m-d H:i:s", ($j*$steps+$steps/2+10000+$startTime) );
	}
	$arr[$j]["Date"] = gmdate("Y-m-d H:i:s", ($j*$steps+$steps/2+10000+$startTime) );
	$arr[$j]["Value"] = 0;
	//echo(":".$j.";".($j*$steps+$step/2+$startTime).",".$nc."</br>");
	$oldnc = $nc;$theAdd = '';
	for ($k = $oldnc;$k<sizeof($timeVals);$k++){
	    if ( ($timeVals[$nc]-$startTime)<$time_end){
		if ($nc==sizeof($timeVals)-1){
		    //$theAdd = $theAdd.','.($cc+1).'\n"';
		}
	    }else{
		//$theAdd = $theAdd.','.($cc).'\n"';
		break 1;
	    }
	    $nc = $nc + 1;
	    
	    if (sizeof($data)>1){
		$inc = $data[$nc];
	    }else{
		$inc = 1;
	    }
	    $cc = $cc + $inc;
	}
	$theAdd = $theAdd.','.($cc).'\n"';
	$arr[$j]["Value"] = $cc;
	$cc = 0;
	
	if (strlen($theAdd)==0){
	    $theArg = $theArg.',0\n"';
	}else{
	    $theArg = $theArg.$theAdd;
	}
	    
    }
    //echo($theArg);
    //echo($arr[1]["Date"]);
    //echo($arr[1]["Value"]);
    //echo($arr[0]["Value"]);
    return($arr);
    
    
}
function plotBar($finance,$arg,$opt){
    $dates = array_column($finance, 'Datum');
    $geld = array_column($finance, 'Geld');
    $reise = array_column($finance, 'Reise');
    $von = array_column($finance, 'Von');
    $zusatz = array_column($finance, 'Zusatz');
    $ursache = array_column($finance, 'Ursache');
    
    $var = 0;
    for ($i=0;$i<sizeof($dates);$i++){
	if ($opt==1 && $reise[$i]>0){
	    continue;
	}
	if ($opt==2 && $von[$i]=="Ingo"){
	    continue;
	}
	if ($opt==3 && $von[$i]=="Lina"){
	    continue;
	}
	if ($opt==0 && strlen(strpos("x",$zusatz))==0){
	    continue;
	}
	
	if ($ursache[$i] == $arg){
	    $var = $var + $geld[$i];
	}
    }
    return ($var);
}


function calcStats($finance,$strgs,$start,$end,$inp){
    
    if (strlen($start)>2){
	$startTime = $start;
    }else{
	$startTime = strtotime("2020-01-01");
    }
    if (strlen($end)>2){
	$endTime = $end;
    }else{
	$endTime = strtotime("Now");
    }
    //echo($startTime."/".$endTime.",".gmdate("Y-m-d",$startTime)."/".gmdate("Y-m-d",$endTime));
    if (strlen($strgs[0])==0){
	$strgs = array("Haushalt","Jobs","Transport","Lebensmittel","Persönliches","Events",
	"Versicherung","Studium");
    }    
    if ($inp==""){
	$inp=array("Ingo","Lina");
    }elseif (sizeof($inp)==1){
	$inp[1] = "";
    }
    
    $data = array();
    for ($j=0;$j<sizeof($strgs);$j++){
	$val = 0.0;
	$label = $strgs[$j];
	for ($i=0;$i<sizeof($finance);$i++){
	    $tmp = $finance[$i]["Zusatz"];
	    //echo($finance[$i]["Nummer"].",".$finance[$i]["Datum"].",".$tmp.",".$i.",".$finance[$i]["Geld"]."</br>");
	    if (strtotime($finance[$i]["Datum"])<$startTime or strtotime($finance[$i]["Datum"])>$endTime or (strlen(trim($tmp[0])=="x")==1) ){
		continue;
	    }
	    if (trim($finance[$i]["Von"]) !== $inp[0] and trim($finance[$i]["Von"]) !== $inp[1]){
		continue;
	    }
	    if ($finance[$i]["Ursache"] == $label){
		//echo($finance[$i]["Geld"].",".$finance[$i]["Artikel"]."</br>");
		$val = $val + $finance[$i]["Geld"];
	    }
	    
	}
	//echo($val."</br>newline</br></br></br>");
	$data[$j]["y"] = $val;
	$data[$j]["label"] = $label;
    }
    $val = 0;
    for ($i=0;$i<sizeof($finance);$i++){
	$tmp = $finance[$i]["Zusatz"];
	if (strtotime($finance[$i]["Datum"])<$startTime or strtotime($finance[$i]["Datum"])>$endTime or (strlen(trim($tmp[0])=="x")==1) ){
		continue;
	}
	if (trim($finance[$i]["Von"]) !== $inp[0] and trim($finance[$i]["Von"]) !== $inp[1]){
	    continue;
	}
	$tmp = $finance[$i]["Reise"];
	if ( (strlen($finance[$i]["Reise"])>0)  ){//
	    $val = $val + $finance[$i]["Geld"];
	}
    }
    $data[sizeof($strgs)]["y"] = $val;
    $data[sizeof($strgs)]["label"] = "Reise";
	
	
    return($data);
    
    
}

function getSingleField($finance,$arg,$startTime,$endTime,$inp){
    if ($inp==""){
	$inp=array("Ingo","Lina");
    }elseif (sizeof($inp)==1){
	$inp[1] = "";
    }
  $newArray = array();
  $cc = 0;
  for ($i=0;$i<sizeof($finance);$i++){
      
	if (trim($finance[$i]["Von"]) !== $inp[0] and trim($finance[$i]["Von"]) !== $inp[1]){
	    continue;
	}
      if (  ($finance[$i]["Ursache"] == $arg && strtotime($finance[$i]["Datum"])>=$startTime && strtotime($finance[$i]["Datum"])<=$endTime) ){
	  $newArray[$cc] = $finance[$i];
	  $cc = $cc+1;
      }
      
  }
  return ($newArray);
}



function scaleImageFileToBlob($file) {

    $source_pic = $file;
    $max_width = 800;
    $max_height = 800;

    list($width, $height, $image_type) = getimagesize($file);

    switch ($image_type)
    {
        case 1: $src = imagecreatefromgif($file); break;
        case 2: $src = imagecreatefromjpeg($file);  break;
        case 3: $src = imagecreatefrompng($file); break;
        default: return '';  break;
    }

    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if( ($width <= $max_width) && ($height <= $max_height) ){
        $tn_width = $width;
        $tn_height = $height;
        }elseif (($x_ratio * $height) < $max_height){
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        }else{
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
    }

    $tmp = imagecreatetruecolor($tn_width,$tn_height);

    /* Check if this image is PNG or GIF, then set if Transparent*/
    if(($image_type == 1) OR ($image_type==3))
    {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
    //$src="Rezepte.lala.jpg";
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    /*
     * imageXXX() only has two options, save as a file, or send to the browser.
     * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
     * So I start the output buffering, use imageXXX() to output the data stream to the browser,
     * get the contents of the stream, and use clean to silently discard the buffered contents.
     */
    ob_start();
    // ------------------------------------------ //
    // ---------------- my modification --------- //
    // ------------------------------------------ //
    //$image_type = 2;
    switch ($image_type)
    {
        case 1: imagegif($tmp); break;
        case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
        case 3: imagepng($tmp, NULL, 0); break; // no compression
        default: echo ''; break;
    }

    $final_image = ob_get_contents();

    ob_end_clean();

    return $final_image;
}



function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth) {
    $explode = explode(".", $imageName);
    $filetype = $explode[1];

    if ($filetype == 'jpg') {
        $srcImg = imagecreatefromjpeg("$imageDirectory/$imageName");
    } else
    if ($filetype == 'jpeg') {
        $srcImg = imagecreatefromjpeg("$imageDirectory/$imageName");
    } else
    if ($filetype == 'png') {
        $srcImg = imagecreatefrompng("$imageDirectory/$imageName");
    } else
    if ($filetype == 'gif') {
        $srcImg = imagecreatefromgif("$imageDirectory/$imageName");
    }


    if ($filetype == 'jpg') {
        imagejpeg($thumbImg, "$thumbDirectory/$imageName");
    } else
    if ($filetype == 'jpeg') {
        imagejpeg($thumbImg, "$thumbDirectory/$imageName");
    } else
    if ($filetype == 'png') {
        imagepng($thumbImg, "$thumbDirectory/$imageName");
    } else
    if ($filetype == 'gif') {
        imagegif($thumbImg, "$thumbDirectory/$imageName");
    }
}


// modify json file

function changeJSON($File, $Parameter, $Value) {
    
    $jsonArray = json_decode(file_get_contents($File), true);
    
    if (json_last_error() === JSON_ERROR_NONE){
	$jsonArrayOld = $jsonArray;
	if (sizeof($Parameter)>1){
	    for ($i=0;$i<sizeof($Parameter);$i++){
		
		// hardcoding exceptions
		if ($Parameter[$i]=="Timer" && !is_array($Value[$i]) )
		{
		    echo("skip");
		}else{
		    $jsonArray[$Parameter[$i]] = $Value[$i];
		}
	    }
	}else{
	    // hardcoding exceptions
	    if ($Parameter=="Timer" && !is_array($Value) )
	    {
		echo("skip");
	    }else{
		$jsonArray[$Parameter] = $Value;
	    }
	}
	//file_put_contents($File,json_encode($jsonArrayOld));
	$bytes = file_put_contents($File,json_encode($jsonArray));
	if (!$bytes or  abs(sizeof($jsonArrayOld)-sizeof($jsonArray))>(sizeof($Parameter))  ) {
	    echo("error");
	    file_put_contents($File,json_encode($jsonArrayOld));
	    writeLog('Json_Log','ERROR with (changeJSON function) File '.$File.
				    ', Parameter: '.$Parameter.', Value: '.$Value);
	    $jsonArray = $jsonArrayOld;
	}else{
	    file_put_contents($File,json_encode($jsonArray));
	    writeLog('Json_Log','(changeJSON function) File '.$File.
				    ', Parameter: '.$Parameter.', Value: '.$Value);
	}
	
				
	return $jsonArray;
    }else{
	writeLog('Json_Log','Error in decoding json: (changeJSON function) File '.$File.
				    ', Parameter: '.$Parameter.', Value: '.$Value);
    }
}


function uploadFile($dest, $path){
    
}

function SiteSection($title,$col,$opt){
    if (strlen($col)==0) {
	$col = "var(--mediumgray)";
    }
    if ($opt>-1){
	if ($opt==1){
	    echo('<div style="padding:10px;background-color:'.$col.';"><div>
	    <center><h3 >'.$title.'</h3></center>
	    <div class="border" style="margin-bottom:10px;"></div><div>');
	}elseif ($opt==4){
	    echo('<div style="padding:10px;background-color:'.$col.';"><div>
	    <center><h3 >'.$title.'</h3></center>
	    <div>');
	}elseif($opt==2){
	    echo('
	    <div style="display: block;float:left;width:100%;padding:10px;background-color:'.$col.';clear:both;">
	      <div class="mycenter">
		<h3>'.$title.'</h3>
		<div class="border" style="margin-bottom:10px;"></div>
		<div class="mycenter" style="display:inline-block;width:100%;">');
	}elseif ($opt==5){
	    echo('<div style="display: block;float:left;width:100%;padding:10px;background-color:'.$col.';clear:both;">
	      <div class="mycenter">
		<h3>'.$title.'</h3>
		<div class="mycenter" style="display:flex;">');
	}else{
	    echo('
	    <div style="display: block;float:left;width:100%;padding:10px;background-color:'.$col.';clear:both;">
	      <div class="mycenter">
		<h3>'.$title.'</h3>
		<div class="border" style="margin-bottom:10px;"></div>
		<div class="mycenter" style="display:flex;">');
	}
	
	
    }elseif($opt==-1){
	echo('</div></div></div>');
    }
}

function drawArray($arr,$dx,$dy,$width,$height,$minV,$maxV){
    

    echo('

    <style>
	.pixel{width:'.$width/$dx.'px;height:'.$height/$dy.'px;float:left;display:block;}
	.line{width:100%;display:block;margin:0px;padding:0px;height:'.$height/$dy.'px;}
    </style>
    ');
    if ($maxV <= $minV){
	$maxV = 0;
	$minV = INF;
	for ($i=0;$i<$dy;$i++){
	  for ($j=0;$j<$dx;$j++){
	    if ($arr[$i][$j]>$maxV){
		$maxV = $arr[$i][$j];
	    }elseif ($arr[$i][$j]<$minV){
		$minV = $arr[$i][$j];
	    }
	  }
	}
    }
    
    //echo($maxV.";".$minV."end");
    for ($i=0;$i<$dy;$i++){
      echo('<div class="line">');
      for ($j=0;$j<$dx;$j++){
	//$val = round( ($arr[$i][$j]-$minV)/($maxV-$minV)*250);
	$val =  max(min(round( ($arr[$i][$j]-$minV)/($maxV-$minV)*200),255),0);
	//$col = "rgb(".$val.",".$val.",".(200-$val).")";
	$col = "rgb(".$val.",".$val.",".$val.")";
	//$col = "rgb(150,".$val.",".(200-$val).")";
	//$col = "rgb(".$val.",".(250-$val).",0)";
	echo('<div class="pixel" style="background-color:'.$col.'"></div>');
      }
      echo('</div>');
    }
}

function strigToBinary($string)
{
    $characters = str_split($string);
 
    $binary = [];
    foreach ($characters as $character) {
        $data = unpack('H*', $character);
        $binary[] = base_convert($data[1], 16, 2);
    }
 
    return implode(' ', $binary);    
}
 
function binaryToString($binary)
{
    $binaries = explode(' ', $binary);
 
    $string = null;
    foreach ($binaries as $binary) {
        $string .= pack('H*', dechex(bindec($binary)));
    }
 
    return $string;    
}


function getCaloriesFromText($c,$food){
    $kals = 0;
    $pos = findStringInArrayFastReverse($c[1],$food[0]);
    $fac = 0;
                  $c[0] = str_replace("1/2",".5",$c[0]);
                  $c[0] = str_replace("1/4",".25",$c[0]);
                  if ($pos > -1){
		    $afac = 1;
		    if(strlen(strpos($c[0],' kleine'))){
                      $d = explode(' kleine',$c[0]);
		      $c[0] = $d[0];
                      $afac = 0.5;
		    }elseif(strlen(strpos($c[0],' große'))){
                      $d = explode(' große',$c[0]);
		      $c[0] = $d[0];
                      $afac = 1.5;
		    }
		    if(strlen(strpos($c[0],' '))==0 && ctype_digit($c[0])){
			$c[0] = $food[2][$pos];
                    }
		    
		    if (strlen(strpos($c[0],' g'))){
                      $d = explode(' g',$c[0]);
                      $fac = $d[0]/100;
                    }elseif (strlen(strpos($c[0],' kg'))){
                      $d = explode(' kg',$c[0]);
                      $fac = $d[0]*1000/100;
                    }elseif(strlen(strpos($c[0],' ml'))){
                      $d = explode(' ml',$c[0]);
                      $fac = $d[0]/100;
                    }elseif(strlen(strpos($c[0],' l'))){
                      $d = explode(' l',$c[0]);
                      $fac = $d[0]*1000/100;
                    }elseif(strlen(strpos($c[0],' EL'))){
                      $d = explode(' EL',$c[0]);
                      $fac = $d[0]*15/100;
                    }elseif(strlen(strpos($c[0],' Pk'))){
                      $d = explode(' Pk',$c[0]);
                      $fac = $d[0]*20/100;
                    }elseif(strlen(strpos($c[0],' TL'))){
                      $d = explode(' TL',$c[0]);
                      $fac = $d[0]*5/100;
                    }elseif(strlen(strpos($c[0],' Zehe'))){
                      $d = explode(' Zehe',$c[0]);
                      $fac = $d[0]*3/100;
                    }elseif(strlen(strpos($c[0],' Scheibe'))){
                      $d = explode(' Scheibe',$c[0]);
                      $fac = $d[0]*40/100;
		    }elseif(strlen(strpos($c[0],' Tropfen'))){
                      $d = explode(' Tropfen',$c[0]);
                      $fac = $d[0]*0.25/100;
		    }elseif(strlen(strpos($c[0],' Prise'))){
                      $d = explode(' Prise',$c[0]);
                      $fac = $d[0]*0.4/100;
		    }elseif(strlen(strpos($c[0],' Bund'))){
                      $d = explode(' Bund',$c[0]);
                      $fac = $d[0]*10*$food[2][$pos]/100;
		    }
		    $fac = $fac * $afac;
		    
                    
                    $kals = round($food[1][$pos]*$fac);
                    
                  }
		  return($kals);                          
}


function changeUrl($url, $sets, $val='!!!!!'){
	if (isset($_GET[$sets])){
		$var = htmlspecialchars($_GET[$sets]);
		$nurl = str_replace("&".$sets."=".$var,'',$url);
	}else{
		$nurl = $url;
	}
	if ($val='!!!!!'){
	}else{
	    if (strlen(strpos($url,".php?"))>0){
		    $nurl = $nurl."&".$sets."=".$val;
	    }else{
		    $nurl = $nurl."?".$sets."=".$val;
	    }
	}

	return($nurl);
}


?> 
