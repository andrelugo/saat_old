<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require_once("includes/code128.php");
$cod128 = new code128();
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<body>
<div align="center">
  <strong>PRODUTOS AGUARDANDO DEFINI&Ccedil;&Atilde;O </strong><strong>DE OR&Ccedil;AMENTO </strong><br>
<? if (empty($_GET["txtDia"])){
$where="data_orc is not null";
$dt="Data Orc";
?>
<style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
  <form action="con_orcpendentes.php" method="get" name="form1" target="_blank">
    Imprimir Or&ccedil;amentos realizados em
    <input name="txtDia" type="text" id="txtDia" value="<? print date("d");?>" size="2" maxlength="2">
    /
    <input name="txtMes" type="text" id="txtMes" value="<? print date("m");?>" size="2" maxlength="2">
    /
    <input name="txtAno" type="text" id="txtAno" value="<? print date("Y");?>" size="4" maxlength="4">
    <input name="Gerar p&aacute;ginad e impress&atilde;o" type="submit" id="" value="Gerar p&aacute;gina de impress&atilde;o">
  </form>
<?
}else{
$dia=$_GET["txtDia"];
$mes=$_GET["txtMes"];
$ano=$_GET["txtAno"];
$dt="Ap/Rp";
$where="day(data_orc)=$dia and month(data_orc)=$mes and year(data_orc)=$ano ";
print (" Orçamentos realizados em $dia/$mes/$ano");
}
?>
</div>
<table width="694" border="0" align="center">
  <tr>
    <td width="142"><strong>Barcode</strong></td>
    <td width="220"><div align="center"><strong>Barras</strong></div></td>
    <td width="84"><strong>R$ Total</strong></td>
    <td width="152"><strong>Orcamento</strong></td>
    <td width="74"><strong><? print("$dt");?></strong></td>
  </tr>
<?
$sql="select barcode, orc_cliente, day(data_orc) as dia, month(data_orc) as mes, year(data_orc) as ano,cp.cod as cp
from cp left
join orc on orc.cod_cp = cp.cod
where $where and cod_decisao=0
group by barcode,orc_cliente,dia,mes,ano";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta de Or&ccedil;amentos pendentes".mysql_error());
	while ($linha = mysql_fetch_array($res)){
		$cp=$linha["cp"];
		$barcode=$linha["barcode"];
		$orc=$linha["orc_cliente"];
		$dtorc=$linha["dia"]."/".$linha["mes"]."/".$linha["ano"];
?>
  <tr>
    <td><? print($barcode);?></td>
    <td><? print $cod128->produceHTML("$barcode",0,20);?>
    <div align="center"></div></td>
    <td>
<? 
//// Corrigindo o valor orc
		$res2=mysql_query("select (orc.qt * orc.valor) as valor from orc where orc.cod_cp = $cp ") or die(mysql_error());
		$total=0;
		while ($linha2 = mysql_fetch_array($res2)){
			$total+=$linha2["valor"];
		}
		$totalF=number_format($total, 2, ',', '.');
		print($totalF);
//// Fim valor orc
?>
	</td>
    <td><? print($orc);?></td>
    <td><? if($dt=="Data Orc"){print($dtorc);}else{print("<input type='checkbox'>");}?></td>
  </tr>
<?
	}
?>
</table>
</body>
</html>