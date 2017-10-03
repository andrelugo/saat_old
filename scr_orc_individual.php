<?
// Esta página recebe os dados da página Pré-Notas de orçamentos individuais arguadando pré-notas.
require_once("sis_valida.php");
require_once("sis_conn.php");

$cp=$_GET["cp"];
$barcode=$_GET["barcode"];

$res=mysql_query("select max(cod_orc_individual) as cod from orc")or die(mysql_error());
$orc=mysql_result($res,0,"cod");
if(empty($orc) || $orc==NULL){
	$orc=1;
}else{
	$orc++;
}
mysql_query("update orc set cod_orc_individual=$orc,data_imprime_individual=now() where cod_cp = $cp and cod_orc_individual is null");
Header("Location:con_orc_individual.php?txtBarcode=$barcode");
?>