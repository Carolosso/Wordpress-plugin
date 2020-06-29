<?php
/**
 * Plugin Name: MBKP Kamera
 * Description: Wtyczka obsługująca obraz z kamery na stronie internetowej
 * Version: 1.0.0
 * Author: WB-MIL
 * Plugin URI: https://github.com/Carolosso/Wordpress-Plugin
 */


 /// KAMERA KOŚCIELNA/STATYCZNA WYLACZONA /////
function mbkp_offline(){

  $display='';
  $display.='<div>';
  $display.='<script type="text/javascript" src="/clappr.min.js"></script>';
  $display.='<div id="player"></div>';
  $display.='<p><script> var player = new Clappr.Player({width:"100%",height:"30vw",source: "http://stream.hlg.com.pl/hlgtv.m3u8", parentId: "#player", autoPlay:true});</script></p>';
  $display.='</div>';
return $display;
}
//--------------------------------------------


/// KAMERA KOŚCIELNA/STATYCZNA WLACZONA /////
function mbkp_online(){

    $display='';
    $display.='<div>';
    $display.='<script type="text/javascript" src="/clappr.min.js"></script>';
    $display.='<div id="player"></div>';
    $display.='<p><script> var player = new Clappr.Player({width:"100%",height:"30vw",source: "http://stream.hlg.com.pl/kosciolnowadeba.m3u8", parentId: "#player", autoPlay:true});</script></p>';
   $display.='</div>';
  return $display;
}
//-----------------------------------------

// PANEL USTAWIEN ADMINA
function mbkp_settings_page(){
?>
<style>
form{
  padding: 20px;
}
.mbkp_a{
  margin-top: 35px;
}
 .mbkp_input{
  padding:10px;
}
.mbkp_h4{
  padding: 10px;
  font-weight: 700;
  font-size: 20px;
}

h1 img{
  margin-right:12px;
}

#mbkp_info{
  display:none;
  font-size:16px;
  color: white; 
  background-color:#007cba;
  padding: 25px;
  position:center;
  border-radius:5px;
  }


</style>
<div class="wrap">
<div>
<h1><img src="https://parafiamatkibozej.com.pl/wp-content/plugins/mbkp_kamera_plugin/camera_icon.png">Przełączanie źródła obrazu transmisji na żywo</h1>
</div>

<hr>
<form method="POST" class="form-table">
  <div class="mbkp_input"><input type="radio"  name="raz" value="on">
  <span class="mbkp_h4">Obraz prosto z kamery kościelnej</span></div> 
<br>

<!-- PODGLAD PLAYERA MOZE KIEDYS BEDZIE !!<PROBLEM Z HTTPS>!!
<script type="text/javascript" src="/clappr.min.js"></script>
<div id="player"></div>
<p><script>
  //  var player = new Clappr.Player({width:"30%",height:"100px",source: "http://stream.hlg.com.pl/kosciolnowadeba.m3u8", parentId: "#player", autoPlay:true});
</script></p>
-->
<div class="mbkp_input"><input type="radio"  name="raz" value="off" >
<span class="mbkp_h4">Obraz transmisji HLG</span></div>
<br>
<!-- PODGLAD PLAYERA MOZE KIEDYS BEDZIE !!<PROBLEM Z HTTPS>!!
<script type="text/javascript" src="/clappr.min.js"></script>
<div id="player"></div>
<p><script>
   // var player = new Clappr.Player({width:"30%",height:"100px",source: "http://stream.hlg.com.pl/hlgtv.m3u8", parentId: "#player", autoPlay:true});
</script></p>
-->
<div class="mbkp_input"><input type="submit" class="button-primary" name="submit" value="Zastosuj"></div>
<a class="mbkp_a" href="http://parafiamatkibozej.com.pl/msza-sw-na-zywo" target="_blank">Podgląd strony</a>
</form>
</div>


<?php
 require_once(ABSPATH.'wp-admin/includes/upgrade.php');
 global $wpdb;
 $table='wpcv_mbkp_kamera';
  $sql='';

  if ($_POST['raz']=="on"){
    $sql="UPDATE $table SET Stan = 'ON' WHERE id = 1;";
    dbDelta( $sql );
  }   
  else if($_POST['raz']=="off"){

    $sql="UPDATE $table SET Stan = 'OFF' WHERE id = 1;";
    dbDelta( $sql );
  }

  $sql=$wpdb->get_var("SELECT Stan FROM $table WHERE id=1");
  ?>
<h3>Aktualnie nadawany jest obraz:
  <?php
  if ($sql=="ON") {
    print " prosto z kamery kościelnej!";
  }
  else if ($sql=="OFF") {
    print " transmisji HLG!";
  }
?>

 </h3>
 <div id="mbkp_info"><?php

if ($_POST['submit']){
  echo "Zatwierdzono zmiany!";
?>
<script>
  document.getElementById('mbkp_info').style.display = 'block';
  setTimeout(function(){
    document.getElementById('mbkp_info').style.display = 'none';
  }, 3000);
</script>
<?php
} 
?> 
</div>


 <?php
}

//-----------------------------------------


// FUNKCJA KONTROLNA 
function mbkp_switcher(){
  global $wpdb;
  $table='wpcv_mbkp_kamera';
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');

$sql=$wpdb->get_var("SELECT Stan FROM $table WHERE id=1");

  if ($sql=="ON") {
    return mbkp_online();
  }
  else if ($sql=="OFF") {
    return mbkp_offline();
  }
}
//-----------------------------------------


function mbkp_admin_option(){
    add_menu_page('Sterowanie kamerą','Obraz z kamery','manage_options','mbkp_camera','mbkp_settings_page','https://parafiamatkibozej.com.pl/wp-content/plugins/mbkp_kamera_plugin/camera_icon2.png',2);
}

add_shortcode('mbkp-kamera', 'mbkp_switcher');

add_action('admin_menu','mbkp_admin_option');

?>
