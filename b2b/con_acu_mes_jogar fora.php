<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$mes=$_GET["txtMes"];
$ano=$_GET["txtAno"];
$flag=$_GET["cmdAcuMes"];
If ($flag=="Gerar Gráfico"){
	Header("Location:gra_desempenho.php?m=$mes&a=$ano");
	exit;
}
?>
<html>
<head>
<title></title>
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

<p align="center">Relat&oacute;rio de entrada e sa&iacute;da de produtos em <br>
Fornecedor: Todos <br>
Servidor: Todos </p>
<p align="center"><a href="gra_desempenho.php<? print("?m=$mes&a=$ano");?>">Gerar Gr&aacute;fico</a> </p>
<p align="center"><span class="style1">Entrada<br>
</span></p>
<div align="center">
  <table width="460" border="1">
    <tr>
      <td width="198"><div align="center">Modelo</div></td>
      <td width="107"><div align="center">Quantidade</div></td>
    </tr>
<?
$countE = 0;
$sql="SELECT COUNT( cp.cod ) AS qt, modelo.descricao AS descricao
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
WHERE MONTH( data_entra ) = $mes 
AND YEAR( data_entra ) = $ano 
GROUP BY DESCRICAO
ORDER BY DESCRICAO;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$countE = $countE+$linha["qt"];
}
?>

    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$countE");?></strong></td>
    </tr>
  </table>
</div>
<hr>
<p align="center"><span class="style1">Sa&iacute;da</span></p>
  <table width="460" border="1" align="center">
    <tr>
      <td width="198"><div align="center">Modelo</div></td>
      <td width="107"><div align="center">Quantidade</div></td>
    </tr>
<?
$countS = 0;
$sql="SELECT COUNT(cp.cod) as qt,modelo.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
where MONTH(data_sai) = $mes and YEAR(data_sai) = $ano
GROUP BY DESCRICAO
order by DESCRICAO;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à saída de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$countS = $countS+$linha["qt"];
}
?>
    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$countS");?></strong></td>
    </tr>
</table>
  <p> Acumulado : <? $res=$countE-$countS;  print ($res);?></p>
</body>
</html>
