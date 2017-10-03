<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$mes=$_GET["txtMes"];
$ano=$_GET["txtAno"];
$fornecedor=$_GET["cmbFornecedor"];
if ($fornecedor==0){
	$for="";
	$descFor="TODOS";
}else{
	$for="AND MODELO.COD_FORNECEDOR=$fornecedor";
	$res=mysql_query("select descricao from fornecedor where cod=$fornecedor");
	$descFor=mysql_result($res,0,"descricao");
}
$flag=$_GET["cmdAcuMes"];
If ($flag=="Gerar Gráfico"){
	Header("Location:gra_desempenho.php?m=$mes&a=$ano&f=$fornecedor");
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
Fornecedor: <? print($descFor);?> <br>
Servidor: Todos </p>
<p align="center"><a href="gra_desempenho.php<? print("?m=$mes&a=$ano&f=$fornecedor");?>">Gerar Gr&aacute;fico</a> </p>
<p align="center"><span class="style1">Entrada <? print($mes."/".$ano);?><br>
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
$for
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
<p align="center"><span class="style1">Sa&iacute;da <? print($mes."/".$ano);?> </span></p>
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
$for
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
  <p>&nbsp;</p>
  <p align="center"><span class="style1">Pend&ecirc;ncia Geral <br>
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
WHERE data_sai is null
$for
GROUP BY DESCRICAO
ORDER BY DESCRICAO;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; entrada de produtos por M&ecirc;s".mysql_error());
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
  <p>&nbsp;</p>
  <p> Acumulado : <? $res=$countE-$countS;  print ($res);?></p>
</body>
</html>
