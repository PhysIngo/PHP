<html>
 <head>
  <title>Remove From Stats</title>
 </head>
<script>
//setTimeout("self.close()", 5 ) // after 5 seconds
</script>
 <body>
  <?php 
  include("body.php");
 
  
if (isset($_GET["FileName"])){
	$FileName = htmlspecialchars($_GET["FileName"]);
}else{
	$FileName = "";
}
if (isset($_GET["Mod"])){
	$Mod = htmlspecialchars($_GET["Mod"]);
}else{
	$Mod = "";
}
if (isset($_GET["Menu"])){
	$Menu = htmlspecialchars($_GET["Menu"]);
}else{
	$Menu = "";
}
if (isset($_GET["nummer"])){
	$Num = htmlspecialchars($_GET["nummer"]);
}else{
	$Num = "";
}
if (isset($_GET["params"])){
	$params = htmlspecialchars($_GET["params"]);
}else{
	$params = "";
}
if (isset($pwdString)){
	//$pwdString;
}else{
	$pwdString = "";
}

if (strlen($Menu)>0){
    $add = 'Menu='.$Menu;
}else{
    $add = 'Menu=';
}

$stats = json_decode(file_get_contents($FileName.'.json'), true);
$aktstat = $stats[$Num];
	
$params = explode(',',$params);
// Zurück
if ($Mod==-1){
    // go back 1 step
    $stats = json_decode(file_get_contents($FileName.'_old.json'), true);
    file_put_contents($FileName.'.json',json_encode($stats));
}
// save everything
elseif ($Mod==-2){
    $stats = json_decode(file_get_contents($FileName.'.json'), true);
    file_put_contents($FileName.'_save.json',json_encode($stats));
    file_put_contents($FileName.'_saved.json',json_encode($stats));
    
    $url = 'Stats.php?'.$add;
    echo  "<script type='text/javascript'>";
    echo 'window.open("'.$url.$pwdString.'","_self")';
    echo "</script>";
// load last save
}elseif ($Mod==-3){
    
    $stats = json_decode(file_get_contents($FileName.'_save.json'), true);
    file_put_contents($FileName.'.json',json_encode($stats));
    $url = 'Stats.php?'.$add;
    echo  "<script type='text/javascript'>";
    echo 'window.open("'.$url.$pwdString.'","_self")';
    echo "</script>";
        
}elseif ($Mod==4){
    for ($i=0;$i<sizeof($stats);$i++){
        if ($i>=$Num){
            if ($i==$Num){
              //$newstats[$i] = $stats[$i];
            }
            $newstats[$i+1] = $stats[$i];
            $newstats[$i+1]["Nummer"] = $i+1;
		}else{
			$newstats[$i] = $stats[$i];
		}
    }
    $stats = $newstats;
    file_put_contents($FileName.'.json',json_encode($stats));
	
}else{

    array_multisort (array_column($stats, "Nummer"), SORT_ASC, $stats);
    $oldstats = $stats;
    //file_put_contents($FileName.'.json',json_encode($stats));
    //add
    if ($Mod==2){
        echo('<div style="padding-left:10px;padding-top:5px;"><h2>not removed: </h2></div></br>');
        $Num = sizeof($stats);
    // modify or delete
    }else{
        echo('<div style="padding-left:10px;padding-top:5px;"><h2>removed: </h2></div></br>');
		#var_dump($stats);
		echo('</br>');
        $stats = removeArrayLine($stats,$Num);
		#var_dump($stats);
        for ($i=0;$i<sizeof($stats);$i++){
			$stats[$i]["Nummer"] = intval($stats[$i]["Nummer"]);
		}
    }
    if ($Mod==2 or $Mod==0){
        array_multisort(array_column($stats, "Nummer"), SORT_ASC, $stats);
        for ($i=0;$i<sizeof($stats);$i++){
            $stats[$i]["Nummer"] = $i;
        }
    }
	
    file_put_contents($FileName.'_tmp.json',json_encode($stats));
}

// delete, mod, add
if ($Mod>0){
    echo("</br>Nummer: ".$Num."</br>");
    echo('<div style="padding-left:10px;padding-top:5px;"><h2>delete/mod/add: </h2></div></br>');
    $opt = '&nummer='.$Num;
    for ($i=0;$i<sizeof($params);$i++){
        $opt = $opt.'&'.trim(strtolower($params[$i]).'='.$aktstat[$params[$i]]);
    }
    $url = 'Stats.php?Update=true&Opt=1&'.$add.$opt;
}else{
    $url = 'Stats.php?Update=true&'.$add;
}

//header('"'.$url.$pwdString.'"');
echo($url);
echo  "<script type='text/javascript'>";
echo 'window.open("'.($url).'","_self")';
//echo 'window.open(""'.$url.$pwdString.'","_self")';
echo "</script>";



?>

 </body>
</html>

