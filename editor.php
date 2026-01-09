<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "include/config.php";
include_once "include/config.inc.php";
include_once "include/tools.php";
include_once "include/functions.php";
$svxConfigFile = '/etc/svxlink/svxlink.conf';

if (fopen($svxConfigFile, 'r')) {
    $svxconfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
    $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
    $fmnetwork = $svxconfig['ReflectorLogic']['HOSTS'];
    //$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];
    $node_password = $svxconfig['ReflectorLogic']['AUTH_KEY'];
    $node_user = $callsign;}
else { $callsign="NOCALL"; 
       $fmnetwork="not registered";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
<head>
    <meta name="robots" content="index" />
    <meta name="robots" content="follow" />
    <meta name="language" content="German" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="SVXLink" />
    <meta name="Author" content="G4NAB, SP2ONG, SP0DZ" />
    <meta name="Description" content="Dashboard for SVXLink by G4NAB, SP2ONG, SP0DZ" />
    <meta name="KeyWords" content="SVXLink,G4NAB, SP2ONG, SP0DZ" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
<link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">    

<?php echo ("<title>" . $callsign ." Ver 2.1 Dashboard</title>"); ?>

<?php include_once "include/browserdetect.php"; ?>
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="scripts/pcm-player.min.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" type="text/css" rel="stylesheet" />
    <script src="scripts/featherlight.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="">

</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">

<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<?php include("./include/top_header.php") ?>

</div></div>
</div>


<?php

include_once "include/top_menu.php"; 
?>

<div class="content"><center>
<div style = "margin-top:0px;">
</div></center>
</div>
<?php
if (isProcessRunning('node')) {
echo '&nbsp;&nbsp;<button class="button link" onclick="playAudioToggle(8080, this)"><b>&nbsp;&nbsp;&nbsp;<img src=images/speaker.png alt="" style = "vertical-align:middle">&nbsp;&nbsp;RX Monitor&nbsp;&nbsp;&nbsp;</b></button><br><br>';
}
?>
<?php
if (MENUBUTTON=="TOP") {
include_once "include/buttons2.php";

}

?>
<?php
if($_GET['id']=="log") {
    $edit_file = "log/index.php";
}
if($_GET['id']=="svxlink") {
    $edit_file = "svxlink/index.php"; 
}
if($_GET['id']=="amixer") {
    $edit_file = "amixer/index.php"; 
}
elseif($_GET['id']=="echolink"){
    $edit_file = "echolink/index.php";
}
elseif($_GET['id']=="metarinfo"){
    $edit_file = "metarinfo/index.php";
}
elseif($_GET['id']=="nodeInfo"){
    file_backup("/etc/svxlink/","node_info.json");
    $edit_file="nodeInfo/index.php";
}
if($_GET['id']=="logic") {
    $edit_file = "logic/index.php"; 
}
if($_GET['id']=="power") {
    $edit_file = "power/index.php";
}
// check if form has been submitted
//$filename = by choice;





    echo '<table style = "margin-bottom:0px;border:0; border-collapse:collapse; cellspacing:0; cellpadding:0; background-color:#f1f1f1;"><tr style = "border:none;background-color:#f1f1f1;">';
    echo '<td width="200px" valign="top" class="hide" style = "height:auto;border:0;background-color:#f1f1f1;">';
    echo '<div class="nav" style = "margin-bottom:1px;margin-top:1px;">'."\n";

    echo '<script type="text/javascript">'."\n";
    echo 'function reloadStatusInfo(){'."\n";
    echo '  $("#statusInfo").load("include/status.php",function(){ setTimeout(reloadStatusInfo,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadStatusInfo,3000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="statusInfo" style = "margin-bottom:30px;">'."\n";
    include "include/status.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</td>'."\n";
    echo '<td valign="top"  style = "height:595px; width=620px; text-align: center; border:none;  background-color:#f1f1f1;">';	


#if($_SESSION['auth'] === 'AUTHORISED') {
echo '<iframe height="100%" id="editIframe" src="' . $edit_file . '" width="620px" height="495px" title="EDIT"></iframe>';

    echo '</td>';
#   }   else {
#        echo '<h1 id="power" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">You are not yet authorised.</h1>';
#        echo '</td>';    
#    }
?>
</tr></table>
<?php
//
    echo '<div class="content2">'."\n";
    echo '<script type="text/javascript">'."\n";
    echo 'function reloadSysInfo(){'."\n";
    echo '  $("#sysInfo").load("include/system.php",function(){ setTimeout(reloadSysInfo,15000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSysInfo,15000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="sysInfo">'."\n";
    include "include/system.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
?>
<?php
if (MENUBUTTON=="BOTTOM") {
    include_once "include/buttons.php"; }
include("./include/footer.php") ?></div>
</fieldset>
<br>
</body>
</html>
