<?php
include_once __DIR__.'/config.php';          
include_once __DIR__.'/tools.php';       
include_once __DIR__.'/functions.php';    
if (file_exists(__DIR__.'/tgdb.inc.php')) {
   include_once __DIR__.'/tgdb.inc.php'; }
else {
   include_once __DIR__.'/tgdb.php'; }

$url=URLSVXRAPI;
// echo $url;
if ($url!="") {
//  Initiate curl
$ch = curl_init();
// Will return the response; if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// fix SSL verification
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
// Set the url
curl_setopt($ch, CURLOPT_URL, $url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
$nodes = json_decode($result, true);
 } else { $nodes="";}
if ($nodes!="") {
if(array_key_exists('Name', $nodes)) {
    $name=$nodes['Name'];
} else { $name="";}
?>
<!--

<span style="font-weight: bold;font-size:14px;">SVXReflector Nodes</span>

<br>

-->


<?php
$i=0;
echo 'Call;RXFREQ;TXFREQ;Echolink;DefaultTG;DefaultTGName;CTCSS`;LAT;LONG;Location;Locator;Mode;Verbund;Website;SysOp';
foreach ($nodes['nodes'] as $key =>$value)
  
{
if ($value['Type'] ==1 && $value['RXFREQ'] !=$value['TXFREQ'] && $key !='Zello-Gtw' && $key !='Zello-Gtw2' ){	
   echo "<pre>";
   if($key=='DB0BIW'){
	 $value['Website']=  'https://afu.bzsax.de/cms/db0biw';
   }
    echo $key . ';' . $value['RXFREQ'] . ';'. $value['TXFREQ'] . ';' .$value['Echolink'] . ';' .$value['DefaultTG'] . ';'  .$tgdb_array[$value['DefaultTG']] . ';' .$value['CTCSS'] . ';' .$value['LAT'] . ';' .$value['LONG'] . ';' .$value['Location'] . ';' .$value['Locator'] . ';'. $value['Mode'] . ';' .$value['Verbund'] . ';' .$value['Website'] . ';' .$value['SysOp'];
    echo "</pre>";
$i++;
}
}
echo 'Count' . $i;
?>

<?php 
}
?>
