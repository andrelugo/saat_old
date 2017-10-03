<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$cod=$_POST["cod"];
$codf=$_POST["codf"];
$destino=$_POST["cmbDestino"];
$order="order by itm_fechamento_reg";

$sql=mysql_query("select max(itm_fechamento_reg) as num from cp where cod_fechamento_reg=$codf")or die(mysql_error());
$num=mysql_result($sql,0,"num");
$num++;
$sql=mysql_query("update cp set cod_destino=$destino,itm_fechamento_reg=$num where cod=$cod")or die(mysql_error());
Header("Location:frm_reg_dig.php?codf=$codf&order=$order");
?>