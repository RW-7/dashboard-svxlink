<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Audio Peak Meter</title>
    <meta name="Author" content="Waldek SP2ONG" />
    <meta name="Description" content="Audio Test Peak Meter for SVXLink by SP2ONG 2022" />
    <meta name="KeyWords" content="SVXLink, SVXRelector,SP2ONG" />
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  background-color: #eee;
  font-size: 18px;
  font-family: Arial;
  font-weight: 300;
  margin: 2em auto;
  max-width: 40em;
  line-height: 1.5;
  color: #444;
  padding: 0 0.5em;
}
h1, h2, h3 {
  line-height: 1.2;
}
a {
  color: #607d8b;
}
.highlighter-rouge {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: .2em;
  font-size: .8em;
  overflow-x: auto;
  padding: .2em .4em;
}
pre {
  margin: 0;
  padding: .6em;
  overflow-x: auto;
}

#player {
    position:relative;
    width:205px;
    overflow: hidden;
    direction: ltl;
}

textarea {
    background-color: #111;
    border: 1px solid #000;
    color: #ffffff;
    padding: 1px;
    font-family: courier new;
    font-size:10px;
}




</style>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<script src="../audio/web-audio-peak-meter.js"></script>
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:0 0 10px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">RF Module Configurator</h1>


<?php 



//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//  if (empty($_POST["ssid"])) {
//     echo "Name is required";
//  } else {
//    $ssid = $_POST["ssid"]);
//  }
//}}


// load the connlist
$retval = null;
$conns = null;

// find the gateway
//$ipgw = null;

$RfConfFile = '/opt/SA818/sa818.json';

if (fopen($RfConfFile,'r'))
{
        $filedata = file_get_contents($RfConfFile);
        $RfData = json_decode($filedata,true);
};


$screen[0] = "Welcome to SA818 RF MODULE configuration tool.";
$screen[1] = "Please use buttons for actions.";
$screen[2] = "Actions are limited to section data only.";
$screen[3] = "Have a Fun. Vy 73 de SP0DZ |shhh...:)";




if (isset($_POST['btnDetect']))
    {

        $retval = null;
        $screen_top = null;
	$screen = null;
	$screen_small = null;
	
        $port = $_POST['port'];

	$command_top = "ls -1 /dev/ttyS* /dev/ttyUSB* 2>&1";
	exec($command_top,$screen_top,$retval);
	$retval = null;
	$i = 0;
	foreach ($screen_top as $port_test)
	{
        $retval = null;
        $screen_small = null;
		$screen[$i] = "Detection for:" .$port_test;
		$command = "sa818 --port \"" .$port_test. "\" version 2>&1";
        exec($command,$screen_small,$retval);
		if (!$retval)
		{
			$port = $port_test;
			$screen[$i] = $screen[$i] . " BINGO !"; 
		}
		$i = $i+1;
	};

}



if (isset($_POST['btnVersion']))
    {
        $retval = null;
        $screen = null;
        $port = $_POST['port'];
        $command = "sa818 --port \"" .$port. "\" version 2>&1";
        exec($command,$screen,$retval);
	if ($retval) echo("NOK");
	if (!$retval) {
		$RfData['port']=$port;
		$jsonRfData = json_encode($RfData);
        	file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/SA818/sa818.json /opt/SA818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/SA818/sa818.json', $screen, $retval);
	}
}



if (isset($_POST['btnRadio']))
    {

    $retval = null;
    $screen = null;
    $port = $_POST['port'];
	$freq = $_POST['freq'];
	$offset = $_POST['offset'];
	$squelch = $_POST['squelch'];
	$ctcss = $_POST['ctcss'];
	$tail = $_POST['tail'];
	$bw = $_POST['bw'];

        $command = "sa818 --port \"" .$port. "\" radio --bw \"" .$bw. "\"  --frequency \"" .$freq. "\" --offset \"" .$offset. "\" --squelch \"" .$squelch. "\" --ctcss \"" .$ctcss. "\" --tail \"" .$tail. "\" 2>&1";
            if (!$retval) exec($command,$screen,$retval);

	//if (!$retval) {
                $RfData['port']=$port;$RfData['freq']=$freq;$RfData['offset']=$offset;$RfData['squelch']=$squelch;$RfData['ctcss']=$ctcss;$RfData['tail']=$tail;$RfData['bw']=$bw;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/SA818/sa818.json /opt/SA818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo cp /var/www/html/rf/sa818.json /opt/SA818/sa818.json', $screen, $retval);
       // }
}

if (isset($_POST['btnFilters']))
    {

	$retval = null;
        $screen = null;
	$port = $_POST['port'];
        $fEmph = $_POST['fEmph'];
        $fLow = $_POST['fLow'];
        $fHigh = $_POST['fHigh'];

        $command = "sa818 --port \"" .$port. "\" filters  --emphasis \"" .$fEmph. "\" --lowpass \"" .$fLow. "\" --highpass \"" .$fHigh. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);
	        if (!$retval) {
                $RfData['port']=$port;$RfData['fEmph']=$fEmph; $RfData['fLow']=$fLow;$RfData['fHigh']=$fHigh;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/SA818/sa818.json /opt/SA818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/SA818/sa818.json', $screen, $retval);
        }

}

if (isset($_POST['btnVol']))
    {
	
	 $retval = null;
        $screen = null;
        $port = $_POST['port'];
        $volume = $_POST['volume'];

        $command = "sa818 --port \"" .$port. "\" volume  --level \"" .$volume. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);
                if (!$retval) {
                $RfData['volume']=$volume;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/SA818/sa818.json /opt/SA818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/SA818/sa818.json', $screen, $retval);
        }

}


//load json

$port = $RfData['port']; 
$freq = $RfData['freq'];$offset=$RfData['offset'];$ctcss=$RfData['ctcss'];$tail=$RfData['tail'];$squelch=$RfData['squelch'];$bw=$RfData['bw'];
$fEmph = $RfData['fEmph'];$fLow=$RfData['fLow'];$fHigh=$RfData['fHigh'];
$volume = $RfData['volume'];


// default section
// port
if ($port === "" || is_null($port)) $port = "/dev/ttyUSB0";

//radio
if ($freq === "" || is_null($freq)) $freq = "145.7850";
if ($offset === "" || is_null($offset)) $offset = "0.0";
if ($ctcss === "" || is_null($ctcss)) $ctcss = "77.0";
if ($tail === "" || is_null($tail)) $tail = "open";
if ($squelch === "" || is_null($squelch)) $squelch = "5";
if ($bw ==="" || is_null($bw)) $bw = "1";

//filter
if ($fEmph === "" || is_null($fEmph)) $fEmph = "no";
if ($fLow === "" || is_null($fLow)) $fLow = "yes";
if ($fHigh === "" || is_null($fHigh)) $fHigh = "yes";

//
if ($volume === "" || is_null($volume)) $volume = "8";


?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<DIV style="height:77px">
<table>
	<tr>
	<th>Screen</th> 
	</tr>
<tr>
<Td>
	<textarea name="scan" rows="4" cols="80"><?php 
			echo implode("\n",$screen); ?></textarea>

</td>



</tr>  
</table> 
</DIV>

<table>
        <tr>
        <th width = "380px">Port</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<Td> 
   <button name="btnDetect" type="submit" class="red"style="height:30px; width:105px; font-size:12px;">Detect</button> 
	Port: <input type "text" name="port" style="width: 150px" value="<?php echo $port;?>"
</TD>
<td>
<button name="btnVersion" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Get Version</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Radio</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<Td>
   	Freq: <input type "text" name="freq" style="width: 120px" value="<?php echo $freq;?>">
    <label for="Bw-select"  title="Bandweite">Bw:</label>
    <select name="bw" id="Bw-select">
        <option value="0"<?php echo ($bw == 0)?' selected':''; ?>>12.5kHz</option>
        <option value="1"<?php echo ($bw == 1)?' selected':''; ?>>25kHz</option>
    </select>
	Shift: <input type "text" name="offset" style="width: 50px" value="<?php echo $offset;?>"> <br>

    <label for="Ctcss-select"  title="Toneselect">Ctcss:</label>
    <select name="ctcss" id="Ctcss-select">
        <option value="None"<?php echo ($ctcss == "None")?' selected':''; ?>>None</option>
        <option value="67.0"<?php echo ($ctcss == "67.0")?' selected':''; ?>>67.0</option>
        <option value="71.9"<?php echo ($ctcss == "71.9")?' selected':''; ?>>71.9</option>
        <option value="74.4"<?php echo ($ctcss == "74.4")?' selected':''; ?>>74.4</option>
        <option value="77.0"<?php echo ($ctcss == "77.0")?' selected':''; ?>>77.0</option>
        <option value="79.7"<?php echo ($ctcss == "79.7")?' selected':''; ?>>79.7</option>
        <option value="82.5"<?php echo ($ctcss == "82.5")?' selected':''; ?>>82.5</option>
        <option value="85.4"<?php echo ($ctcss == "85.4")?' selected':''; ?>>85.4</option>
        <option value="91.5"<?php echo ($ctcss == "91.5")?' selected':''; ?>>91.5</option>
        <option value="94.8"<?php echo ($ctcss == "94.8")?' selected':''; ?>>94.8</option>
        <option value="97.4"<?php echo ($ctcss == "97.4")?' selected':''; ?>>97.4</option>
        <option value="100.0"<?php echo ($ctcss == "100.0")?' selected':''; ?>>100.0</option>
        <option value="103.5"<?php echo ($ctcss == "103.5")?' selected':''; ?>>103.5</option>
        <option value="107.2"<?php echo ($ctcss == "107.2")?' selected':''; ?>>107.2</option>
        <option value="110.9"<?php echo ($ctcss == "110.9")?' selected':''; ?>>110.9</option>
        <option value="114.8"<?php echo ($ctcss == "114.8")?' selected':''; ?>>114.8</option>
        <option value="118.8"<?php echo ($ctcss == "118.8")?' selected':''; ?>>118.8</option>
        <option value="123.0"<?php echo ($ctcss == "123.0")?' selected':''; ?>>123.0</option>
        <option value="127.3"<?php echo ($ctcss == "127.3")?' selected':''; ?>>127.3</option>
        <option value="131.8"<?php echo ($ctcss == "131.8")?' selected':''; ?>>131.8</option>
        <option value="136.5"<?php echo ($ctcss == "136.5")?' selected':''; ?>>136.5</option>


    </select>
    <label for="Squelch-select"  title="Rauschsperre">Squelch:</label>
    <select name="squelch" id="Squelch-select">
        <option value="0"<?php echo ($squelch == 0)?' selected':''; ?>>offen</option>
        <option value="1"<?php echo ($squelch == 1)?' selected':''; ?>>1</option>
        <option value="2"<?php echo ($squelch == 2)?' selected':''; ?>>2</option>
        <option value="3"<?php echo ($squelch == 3)?' selected':''; ?>>3</option>
        <option value="4"<?php echo ($squelch == 4)?' selected':''; ?>>4</option>
        <option value="5"<?php echo ($squelch == 5)?' selected':''; ?>>5</option>
        <option value="6"<?php echo ($squelch == 6)?' selected':''; ?>>6</option>
        <option value="7"<?php echo ($squelch == 7)?' selected':''; ?>>7</option>
        <option value="8"<?php echo ($squelch == 8)?' selected':''; ?>>8</option>
    </select>
    <label for="Tail-select"  title="Hello This Will Have Some Value">Tail:</label>
    <select name="tail" id="Tail-select">
    <option value="open"<?php echo ($tail == "open")?' selected':''; ?>>open</option>
    <option value="close"<?php echo ($tail == "close")?' selected':''; ?>>close</option>
    </select>

</TD>
<td>
<button name="btnRadio" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Set Radio</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Enable Filters</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<Td>
    <label for="Emphasis-select"  title="Wechseln Sie zum Diskriminator-Ausgang und -Eingang Defaut 1">Emphasis:</label>
    <select name="fEmph" id="Emphasis-select">
        <option value="Disable"<?php echo ($fEmph == "Disable")?' selected':''; ?>>Aus</option>
        <option value="Enable"<?php echo ($fEmph == "Enable")?' selected':''; ?>>An</option>
    </select>
    <label for="Low-select"  title="Tiefpassfilter umgehen  Defaut 1">Low:</label>
    <select name="fLow" id="Low-select">
        <option value="Disable"<?php echo ($fLow == "Disable")?' selected':''; ?>>Aus</option>
        <option value="Enable"<?php echo ($fLow == "Enable")?' selected':''; ?>>An</option>
    </select>
    <label for="High-select"  title="Hochpassfilter umgehen  Defaut 1">High:</label>
    <select name="fHigh" id="High-select">
        <option value="Disable"<?php echo ($fHigh == "Disable")?' selected':''; ?>>Aus</option>
        <option value="Enable"<?php echo ($fHigh == "Enable")?' selected':''; ?>>An</option>
    </select>

</TD>
<td>
<button name="btnFilters" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Set Filters</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Volume</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<Td>
    <label for="Volume-select"  title="Lautstärke">Volume:</label>
    <select name="volume" id="Volume-select">
        <option value="0"<?php echo ($volume == 0)?' selected':''; ?>>0</option>
        <option value="1"<?php echo ($volume == 1)?' selected':''; ?>>1</option>
        <option value="2"<?php echo ($volume == 2)?' selected':''; ?>>2</option>
        <option value="3"<?php echo ($volume == 3)?' selected':''; ?>>3</option>
        <option value="4"<?php echo ($volume == 4)?' selected':''; ?>>4</option>
        <option value="5"<?php echo ($volume == 5)?' selected':''; ?>>5</option>
        <option value="6"<?php echo ($volume == 6)?' selected':''; ?>>6</option>
        <option value="7"<?php echo ($volume == 7)?' selected':''; ?>>7</option>
        <option value="8"<?php echo ($volume == 8)?' selected':''; ?>>8</option>
    </select>
</TD>
<td>
<button name="btnVol" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Set Vol</button>
</TD>
</tr>
</table>

</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
