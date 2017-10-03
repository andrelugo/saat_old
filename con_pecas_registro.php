<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$registro=$_GET["registro"];
?>
<html>
<head>
<title>Relatório de peças em Registro de saída</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilo.css" rel="stylesheet" type="text/css"></head>
<body>
<div align="center" class="Titulo2">
<p align="center">
<?
$sql="SELECT peca.descricao AS peca, sum( orc.qt ) AS qt, peca.venda AS pv, peca.venda * sum( orc.qt ) AS tot
FROM orc
INNER JOIN cp ON cp.cod = orc.cod_cp
INNER JOIN peca ON peca.cod = orc.cod_peca
INNER JOIN fechamento_reg ON fechamento_reg.cod = cp.cod_fechamento_reg
WHERE fechamento_reg.registro = '$registro'
GROUP BY peca";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows==0){
	die ("<h1>Nenhum resultado encontrado para a pesquisa com o registro de saídas $registro</h1>");
}else{
?>
  </p>
Resumo de pe&ccedil;as or&ccedil;adas no registro de saidas <? print($registro);
}
?>
<table width="721" border="1">
  <tr class="caixaPR1">
    <td width="446">Pe&ccedil;a</td>
    <td width="80">Qtdade</td>
    <td width="83">P.V. Unit R$ </td>
    <td width="84">VL. TOT R$ </td>
  </tr>
 <?
 $Total=0;
 while ($linha=mysql_fetch_array($res)){
 ?>
  <tr>
    <td><? print($linha["peca"]);?></td>
    <td><? print($linha["qt"]);?></td>
    <td><? $pv=$linha["pv"];
		$pv2=number_format($pv, 2, ',', '.');
		print($pv2);?></td>
    <td><? $pvt=$linha["tot"];
		$pvt2=number_format($pvt, 2, ',', '.');
		print($pvt2);
		$Total+=$pvt;?></td>
  </tr>
  <?
  }
  ?>
  <tr>
  	<td class="caixaPR1">TOTAL R$ </td>
  	<td colspan="3" class="caixaPR1"><?
		$Total2=number_format($Total, 2, ',', '.');
		 print("R$ ".$Total2);?></td>
  	</tr>
</table>
<p class="style2">&nbsp;</p>
</div>
</body>
</html>
