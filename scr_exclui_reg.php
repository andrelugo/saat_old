<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$cod=$_GET["cod"];
$codf=$_GET["codf"];
$order=$_GET["order"];
mysql_query("update cp set cod_fechamento_reg=NULL where cod=$cod") or die(mysql_error);
Header("Location:frm_reg.php?codf=$codf&order=$order");
?>