<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$cod=$_GET["cod"];
$tabela=$_GET["tabela"];
$nota=$_GET["nota"];
mysql_query("delete from $tabela where cod=$cod");
Header("Location:frm_nf_entrada.php?nota=$nota");
?>