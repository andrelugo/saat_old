<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$codped=$_GET["codped"];
$dest=$_GET["dest"];
$cp=$_GET["cp"];
$msg=$_GET["msg"];
$forn=$_GET["forn"];
$modelo=$_GET["modelo"];
$tabela=$_GET["tabela"];
mysql_query("delete from $tabela where cod=$codped");
Header("Location:$dest?cp=$cp&msg=$msg&forn=$forn&modelo=$modelo");
?>