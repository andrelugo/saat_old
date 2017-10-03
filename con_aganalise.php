<?
require_once("sis_valida.php");
require_once("sis_conn.php");

$sqlLtec=mysql_query("select linhatec from rh_user where cod=$id");
$Ltec=mysql_result($sqlLtec,0,"linhatec");
if ($Ltec==0){
	$where="";
}else{
	$where="and modelo.linha=$Ltec";
}


?>
<html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>
<body>
<p align="center" class="Cabe&ccedil;alho"> Produtos Aguardando analise em <? print(date("d/m/Y"))?></p>

<p align="center" class="Cabe&ccedil;alho">Detalhado</p>
<div align="center">
  <table width="583" border="1">
      <tr class="Cabe&ccedil;alho">
        <td width="172">Modelo</td>
        <td width="250">Cód Barras</td>
	    <td width="139">Data Cód Barras</td>
	    <td width="139">Dias Parado</td>
    </tr>
<?
$count =0;
$sql="select modelo.descricao as mode,barcode,data_barcode,DATEDIFF(now(),data_entra) AS dd
from cp inner join modelo on modelo.cod = cp.cod_modelo
where data_analize is null $where";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
	if ($linha["dd"]>3){
?>		<tr bgcolor="#FF0000">
<?	}else{
		if ($linha["dd"]==3){
?>			<tr bgcolor="#FFFF00">
<? 		}else{
?>			<tr>
<? 		}
	}
?>
	<td><? print($linha["mode"]);?></td>
	<td><? print($linha["barcode"]);?></td>
	<td><? print($linha["data_barcode"]);?></td>
	<td><div align="center"><? print($linha["dd"]);
	$count++;
}?>
</div></td>
</tr>
    <tr class="style3"><td class="Cabe&ccedil;alho">TOTAL</td>
    <td class="style3"><span class="style3"><? print("$count");?></span></td></tr>
  </table>
  <p>&nbsp;</p>
  <table width="258" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="139"><div align="center">Modelo</div></td>
      <td width="103"><div align="center">Quantidade</div></td>
  </tr>
<?
$count = 0;
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where data_analize is null $where
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td class="style3">TOTAL</td><td class="style3"><?print("$count");?></td></tr>
</table>
</div>
</body>
</html>
