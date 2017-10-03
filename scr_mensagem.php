<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$l1=$_POST["txtL1"];
$l2=$_POST["txtL2"];
$l3=$_POST["txtL3"];
$l4=$_POST["txtL4"];
$l5=$_POST["txtL5"];
mysql_query("update base set l1 = '$l1'");
mysql_query("update base set l2 = '$l2'");
mysql_query("update base set l3 = '$l3'");
mysql_query("update base set l4 = '$l4'");
mysql_query("update base set l5 = '$l5'");
?>
<html>
<head></head> 
<body>
T&iacute;tulo: <? print($l1);?><br>
LINHA 1: <? print($l2);?><br>
LINHA 2: <? print($l3);?><br>
LINHA 3: <? print($l4);?><br>
Autor: <? print($l5);?><br>
</body> 
</html> 