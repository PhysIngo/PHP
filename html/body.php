<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
 
<link rel="shortcut icon" href="/favicon.ico">
<link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.png">
<link rel="icon" type="image/png" href="/favicon.png" sizes="96x96">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="html5slider.js"></script>  
  
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia&effect=neon|outline|emboss|shadow-multiple">

 <script>
    function setStyleSheet(url){
       var stylesheet = document.getElementById("stylesheet");
       stylesheet.setAttribute('href', url);
    }
    </script>
    
<link rel="stylesheet" href="css/StyleSheet.css" id="currentCSS"  type="text/css"><?php echo ""; ?></a>
<link id="stylesheet" rel="stylesheet" type="text/css" href=""/>


    <title>Neue Seite</title>
</head>
<style>
.bg-img {
  background-image: url("");
  min-height: 100px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  padding-left:50px;
  padding-top:75px;
  position: relative;
}
</style>

    
    
</head>
<body>
    
    
  <?php
  include_once('functions.php');
  $url = "";
  $PlotColor = "Farben";
  $pwdString = "";
  
  
  $Parameters = json_decode(file_get_contents('data/Parameters.json'), true);
  //var_dump($Parameters);
  $ActCol = $Parameters["Farben"];
  
  echo('
<style>
:root {
  --blue: '.$ActCol["Color"][0].';
  --green: '.$ActCol["Color"][1].';
  --orange: '.$ActCol["Color"][2].';  
  
  --buttom: '.$ActCol["Button"][1].';  
  
  --mainText: '.$ActCol["Gray"][0].';
  --backgray: '.$ActCol["Gray"][1].';
  --lightgray: '.$ActCol["Gray"][2].';
  --mediumgray: '.$ActCol["Gray"][3].';
  --darkgray: '.$ActCol["Gray"][4].';
  --darkergray: '.$ActCol["Gray"][5].';
}
</style>');
  

  ?>
    
   
            
  <div class="bg-img" >
    <div class="content">
      <h1 style='font-family: "Sofia", sans-serif;text-shadow: 0 0 1px var(--mainText);'><?php echo($Parameters["Title"]); ?></h1><!-- font-family:candara;-->
      <h2 style='font-family: "Sofia", sans-serif;font-size:var(--font_huge);text-shadow: 0 0 4px var(--lightgray);'><?php echo($Parameters["SubTitle"]); ?></h2>
      </br>
    </div>
  </div>
    
    <?php
    
 
echo('
<div style="position:sticky;top: 0;z-index: 100;background-color:var(--darkestgray);">');

      echo('<div class="dropdown" style="float:right;height:auto;vertical-align:center;background-color:var(--darkegray);">');
      echo('<img class="dropbtn" style="all: unset;height:32px;padding-top:8px;margin-left:12px;margin-right:8px;filter: var(--buttom);" src="Icons/Icon_Opt2.png"/>');

// Edit ------------------ //
          echo('  <div class="dropdown-content" style="margin-top:0px;margin-left:-52px;min-width: auto;">');
          $clrs = 'background-color:var(--lightgray);';
		  $add = "";
		  $url = $_SERVER['REQUEST_URI'];
		  //echo($url);
		  $EditVal = "false";
		  if (isset($_GET["EditVal"])){
			$EditVal = htmlspecialchars($_GET["EditVal"]);
		  }else{
			$EditVal = "true";
		  }
		  if (strlen(strpos($url,"EditVal"))==0){
			  if (strlen(strpos($url,"php?"))){
              $add = "&EditVal=".$EditVal;
            }else{
              $add = "?EditVal=".$EditVal;
            }
		  }else{
            if ($EditVal=="true"){
              $url = str_replace("&EditVal=".$EditVal,"",$url);
              $url = str_replace("?EditVal=".$EditVal,"",$url);
              $clrs = 'background-color:var(--green);';        
            }else{
              $url = str_replace("EditVal=".$EditVal,"EditVal=true",$url);
            }
            $add = "";
          
          }
		  if (isset($_GET["EditVal"])){
			$EditVal = boolval(htmlspecialchars($_GET["EditVal"]));
		  }else{
			$EditVal = false;
		  }
          echo('<a href="'.$url.$add.'"  style="padding-top:8px;height:32px;width:104px;font-size:var(--font_large);'.$clrs.'">Edit</a>');

// Filter ------------------ //
          $clrs = 'background-color:var(--lightgray);';
		  $add = "";
		  $url = $_SERVER['REQUEST_URI'];
		  //echo($url);
		  if (isset($_GET["FilterVal"])){
			$FilterVal = htmlspecialchars($_GET["FilterVal"]);
		  }else{
			$FilterVal = "true";
		  }
		  if (strlen(strpos($url,"FilterVal"))==0){
			  if (strlen(strpos($url,"php?"))){
              $add = "&FilterVal=".$FilterVal;
            }else{
              $add = "?FilterVal=".$FilterVal;
            }
		  }else{
            if ($FilterVal=="true"){
              $url = str_replace("&FilterVal=".$FilterVal,"",$url);
              $url = str_replace("?FilterVal=".$FilterVal,"",$url);
              $clrs = 'background-color:var(--green);';        
            }else{
              $url = str_replace("FilterVal=".$FilterVal,"FilterVal=true",$url);
            }
            $add = "";
          
          }
		  if (isset($_GET["FilterVal"])){
			$FilterVal = boolval(htmlspecialchars($_GET["FilterVal"]));
		  }else{
			$FilterVal = false;
		  }
          echo('<a href="'.$url.$add.'"  style="padding-top:8px;height:32px;width:104px;font-size:var(--font_large);'.$clrs.'">Filter</a></div>');
          
      echo("</div>");
  
  
  echo('<div class="scrollmenu" style="background-color:var(--darkestgray);">');
  echo('<a href="index.php">Home</a>');
  echo('<a href="Stats.php?Menu=Bestellung">Bestellungen</a>');
  echo('<a href="Jobs.php">Jobs</a>');
  echo('<a href="ColorStyler.php">Farbe</a>');
  echo('
  </div>
  
  
  
</div>');


?>

</body>

<!--
<script>
function ColorFunc(){	
  window.location.href = window.document.location.protocol;
}
</script>-->
</html>
