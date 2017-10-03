<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$criterio=$_GET["criterio"];
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style4 {
	font-size: 14px;
	font-style: italic;
}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>

<body>

<p align="center"><span class="style1">PRODUTOS PRONTOS <? print($criterio);?><br>
</span><span class="style4">Ordenados pela data em que deixou PRONTO! </span></p>
<div align="center">
  <table width="800" border="1">
    <tr>
	  <td width="164"><div align="center">Data Analise</div></td>
      <td width="227"><div align="center">Modelo</div></td>
      <td width="229"><div align="center">S&eacute;rie</div></td>
      <td width="152"><div align="center">Data Pronto </div></td>
    </tr>
<?
if ($criterio=="HOJE"){$dia="and day(data_pronto) = day(NOW())";}else{$dia="";}
$count = 0;
$sql="SELECT DATE_FORMAT(data_pronto, '%d/%m/%Y') AS dd, DATE_FORMAT(data_analize, '%d/%m/%Y') AS da, serie,modelo.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
where MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id $dia
order by dd desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td>$linha[da]</td><td> $linha[descricao] </td><td> $linha[serie] </td><td> $linha[dd] </td></tr>");
		$count++;
}
?>

    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$count");?></strong></td>
    </tr>
  </table>
</div>
    <div align="center">
      <p>&nbsp;</p>
      <p><span class="style1">Agrupado por modelo</span>            </p>
          </p>
</div>
  <table width="447" border="1" align="center">
    <tr>
      <td width="361"><div align="center">Modelo</div></td>
      <td width="70"><div align="center">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where  MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id $dia
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".$sql);
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td class="style3"><strong>TOTAL</strong></td>
  <td class="style3"><strong><?print("$count");?></strong></td></tr>
</table>
</body>
</html>
