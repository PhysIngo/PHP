<html>
 <head>
  <title>Change JSON file</title>
 </head>
<script>
setTimeout("self.close()", 5 ) // after 5 seconds
</script>
 <body>
  <?php 
  
  $arr = include("body.php");

#phpinfo();
include_once('functions.php');
$File= htmlspecialchars($_GET["File"]);
$Parameter= htmlspecialchars($_GET["Parameter"]);
$Value= htmlspecialchars($_GET["Value"]);
$Url= htmlspecialchars($_GET["Url"]);


echo($File.",".$Parameter.",".$Value.",".$Url);
// modify json file

    $jsonArray = json_decode(file_get_contents($File), true);
    
    $jsonArray[$Parameter] = $Value;
    
    file_put_contents($File,json_encode($jsonArray));
    writeLog('Json_Log', 'File '.$File.
                            ', Parameter: '.$Parameter.', Value: '.$Value.
                            ', Url: '.$Url); 

							
if (strlen($Url)>2){
    header("Location: ".$Url, true);
    //echo("Url: ".urldecode($Url));
    echo  "<script type='text/javascript'>";
    //echo 'window..location.replace("'.urldecode($Url).'");';
    echo 'window.location.href ="'.urldecode($Url).'";';
    echo "</script>";
}else{

    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "</script>";
}

?>

 </body>
</html>

