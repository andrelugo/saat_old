<?
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
$folha=$_GET["folha"];
if (isset($_GET["msg"])){$msg=$_GET["msg"];}else{$msg="";}
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
  <p align="center">Ap&oacute;s digitar um c&oacute;digo de barras clique sobre sua linha correspondente abaixo! </p>
    <table width="797" border="1">
<tr>
	<td width="99"><div align="center"><strong>Barcode</strong></div></td>
    <td width="129"><div align="center"><strong>S&eacute;rie</strong></div></td>
    <td width="115"><div align="center"><strong>Modelo</strong></div></td>
    <td width="54"><div align="center"><strong>Destino</strong></div></td>
	<td width="213"><div align="center"><strong>Controler</strong></div></td>
	<td width="147"><div align="center"><strong>Data Saída</strong></div></td>
</tr>
<?
$sql="select cp.cod as cod,barcode,serie,modelo.descricao as modelo,destino.descricao as destino,data_registro_saida as data,rh_user.nome as nome
from cp 
inner join modelo on modelo.cod = cp.cod_modelo 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_cq
where folha_cq=$folha
order by data";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros da folha $folha ".mysql_error());
while ($linha = mysql_fetch_array($res)){
	$jv="scr_registra_saida2.php?cp=$linha[cod]&folha=$folha";
		if (empty($linha["data"])){
			print ("<tr onMouseOver=$jvA onMouseOut=$jvB><a href=$jv>
			<td> $linha[barcode]</td> <td>$linha[serie]</td> <td>$linha[modelo]</td> <td>$linha[destino]</td> <td>$linha[nome]</td> <td>$linha[data]</td></a></tr>");
		}else{
			print ("<tr bgcolor='#00CCCC'> <td>$linha[barcode]</td> <td>$linha[serie]</td> <td>$linha[modelo]</td> <td>$linha[destino]</td> <td>$linha[nome]</td> <td>$linha[data]</td></a></tr>");
		}
}
?>
</table>
  </div>
</body>
</html>