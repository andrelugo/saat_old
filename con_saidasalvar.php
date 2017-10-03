<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {	font-size: 24px;
	font-weight: bold;
}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
  <p align="center"><span class="style1">Controles de Produ&ccedil;&atilde;o  prontos para salvar</span></p>
      <table width="426" border="1" align="center">
<tr>
	<td width="270"><div align="center"><strong>Controler</strong></div></td>
	<td width="57"><div align="center"><strong>Destino</strong></div></td>
    <td width="77"><div align="center"><strong>Quantidade</strong></div></td>
</tr>
<?
$sql="select destino.descricao as destino,rh_user.nome as nome, count(cp.cod) as qt
from cp 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_cq
where folha_cq is null and data_sai is not null
group by nome,destino";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
$tot=0;
while ($linha = mysql_fetch_array($res)){
		print ("<tr> <td>$linha[nome]</td><td>$linha[destino]</td> <td>$linha[qt]</td> </tr>");
		$tot=$tot+$linha["qt"];
}
?>
<tr class="style1">
<td>Total</td>
<td colspan="2"><?print ($tot);?></td>
</table>
  
      <p>&nbsp;</p>
      <table width="759" border="1" align="center">
<tr>
	<td width="94"><div align="center"><strong>Barcode</strong></div></td>
    <td width="123"><div align="center"><strong>S&eacute;rie</strong></div></td>
    <td width="110"><div align="center"><strong>Modelo</strong></div></td>
    <td width="52"><div align="center"><strong>Destino</strong></div></td>
	<td width="260"><div align="center"><strong>Controler</strong></div></td>
</tr>
<?
$sql="select cp.cod as cod,barcode,serie,modelo.descricao as modelo,destino.descricao as destino,rh_user.nome as nome
from cp 
inner join modelo on modelo.cod = cp.cod_modelo 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_cq
where folha_cq is null and data_sai is not null
order by destino";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr> <td>$linha[barcode]</td> <td>$linha[serie]</td> <td>$linha[modelo]</td> <td>$linha[destino]</td> <td>$linha[nome]</td> <td></td></a></tr>");
}
?>
</table>
  </div>
</body>
</html>
