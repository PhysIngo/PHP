<html>
 <head>
  <title>Jobs</title>
 </head>
 
 <body>
  <?php 
  include("body.php");
  
  SiteSection("Jobs", "var(--lightgray)",6);

$JobName = "data/Jobs.json";
$ArrayName = "data/Bestellungen_2022.json";
$FreqName = "wert_freq";
$DatumName = "datum";
$startTime = "2022-01-01";
$endTime = "2023-01-01";

$fjobs = json_decode(file_get_contents($JobName), true);
$flen = sizeof($fjobs);
  
$farray = json_decode(file_get_contents($ArrayName), true);
$len = sizeof($farray);
  
$params = array_keys($farray[0]);

    $cc = 0;
    for ($i=0;$i<$flen;$i++){
      // check if the file already exists
      $flag = 0;
      // check the date
        $date = $fjobs[$i][$DatumName];
        //echo("stats: ".time().",".strtotime($date)."</br>");
        if (strtotime($date)>time() ){
          $flag = 1;
        }
        $begin = new DateTime($date);
        $end = new DateTime(date('Y-m-d H:i:s'));
        $dateArray = array();
        $freq = $fjobs[$i][$FreqName]." month";
        $interval = DateInterval::createFromDateString($freq);
        $period = new DatePeriod($begin, $interval, $end);
		
		echo('Job Eintrag: '.implode(',',$fjobs[$i]));
        echo("</br>Folgende Daten müssen vorhanden sein: ");
        foreach ($period as $dt) {
            $a = $dt->format("Y-m-d\n");
            echo($a);
            if ( (strtotime($a)>=strtotime($startTime)) && 
            (strtotime($a)<=strtotime($endTime)) ){
              array_push($dateArray, $a);
            }
        }
        if ($flag == 0){
        for ($j=0;$j<$len;$j++){
			$elflag = true;
			for ($k=1;$k<sizeof($params);$k++){
				if ($params[$k]==$DatumName){
					continue;
				}
				if (trim($farray[$j][$params[$k]])==trim($fjobs[$i][$params[$k]]) ){
				}else{
					$elflag = false;
				}
			}
			if ($elflag){
                  
                $pos = findStringInArray($farray[$j][$DatumName],$dateArray,0);
                if (!empty($pos)){
                  array_splice($dateArray,$pos[0],1);
                }
            
			}
        } 
      }
	  if (sizeof($dateArray)>0){
		  echo("</br>Neue Buchungen: ");
		  for ($k=0;$k<sizeof($dateArray);$k++){
			echo($dateArray[$k].",");
			$flag = 0;
		  }
      
		  //echo("flag : ".$flag."</br>");
		  if ($flag==0){
			echo ("</br>Folgende Buchungen wurden ergänzt: </br>");
			for ($k=0;$k<sizeof($dateArray);$k++){
			  $farray[$len+$cc] = $fjobs[$i];
			  $farray[$len+$cc][$DatumName] = $dateArray[$k];
			  $farray[$len+$cc]["Nummer"] = $len+$cc;
			  $theLogStr = print_r($farray[$len+$cc],true);
			  echo($theLogStr."</br>");
			  
			  $cc = $cc+1;
			}
		  }
	  }else{
		  echo('</br>Keine neuen Einträge hinzugefügt.</br>');
	  }
    }
    file_put_contents($ArrayName,json_encode($farray));
    
	echo('</div>');
  
  
  SiteSection("", "",-1);
  include("footer.php");
  ?>

</body>

</html>