<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$peca=$_GET["peca"];
$onde=$_GET["onde"];
$consulta=mysql_query("select venda from peca where cod=$peca");
$valor=mysql_result($consulta,0,"venda");
$sql="update orc inner join cp on cp.cod = orc.cod_cp set valor=$valor where cod_peca=$peca and orc_cliente is null";
mysql_query($sql);
Header("Location:frm_orcarnocliente.php?onde=$onde");
?>
