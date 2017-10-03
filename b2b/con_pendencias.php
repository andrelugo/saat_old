<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Pendencia de produtos</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
</p>
  <p align="center"><span class="style1">Relat&oacute;rio de Pend&ecirc;ncia Geral - por modelo <br>
  </span></p>
  <div align="center">
    <table width="800" border="1">
      <tr>
        <td width="150"><div align="center">Cód. Fábrica</div></td>
        <td width="300"><div align="center">Modelo</div></td>
		<td width="50"><div align="center">Qtdade Total</div></td>
		<td width="50"><div align="center">Qtdade Ag. Análise</div></td>
		<td width="50"><div align="center">Qtdade Ag. Peças</div></td>
		<td width="50"><div align="center">Qtdade em Orçamento</div></td>
		<td width="50"><div align="center">Qtdade Prontos</div></td>
		<td width="50"><div align="center">Qtdade Entre 20 e 30 dias</div></td>
		<td width="50"><div align="center">Qtdade + de 30 dias</div></td>

      </tr>
<?
$countE = 0;
$sql="SELECT modelo.cod as codmodelo,cod_produto_fornecedor, modelo.descricao AS descricao, COUNT( cp.cod ) AS qtTot,
(select count(cp.cod) from cp where cod_modelo = codmodelo and data_sai is null and data_analize is null) as qtAganalise,
(select count(distinct cp.cod) from cp inner join pedido on pedido.cod_cp = cp.cod where cod_modelo = codmodelo and data_pronto is null and data_analize is not null) as qtAgpecas,
(select count(distinct cp.cod) from cp inner join orc on orc.cod_cp = cp.cod where cod_modelo = codmodelo and data_pronto is null and data_analize is not null) as qtAgorc,
(select count(distinct cp.cod) from cp where cod_modelo = codmodelo and data_sai is null and data_pronto is not null) as qtProntos,
(select count(distinct cp.cod) from cp where cod_modelo = codmodelo and data_sai is null AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30)) as qt2030,
(select count(distinct cp.cod) from cp where cod_modelo = codmodelo and data_sai is null AND (DATEDIFF(now( ) , data_entra) >=30)) as qt30
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
WHERE data_sai is null
and modelo.cod_fornecedor = $id
GROUP BY DESCRICAO
ORDER BY DESCRICAO;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta de pendencias de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
		<tr>
		<td><? print ("$linha[cod_produto_fornecedor]");?></td>
		<td><a href="con_modelo.php?modelo=<? print("$linha[codmodelo]");?>" target="_blank"><? print ("$linha[descricao]");?></a></td>
		<td><? print ("$linha[qtTot]");?></td>
		<td><? print ("$linha[qtAganalise]");?></td>
		<td><? print ("$linha[qtAgpecas]");?></td>
		<td><? print ("$linha[qtAgorc]");?></td>
		<td><? print ("$linha[qtProntos]");?></td>
		<td><? print ("$linha[qt2030]");?></td>
		<td><? print ("$linha[qt30]");?></td>
		</tr>
<?
		$countE = $countE+$linha["qtTot"];
}
?>
      <tr>
        <td><strong>TOTAL</strong></td>
        <td colspan="8"><strong><? print("$countE");?></strong></td>
      </tr>
    </table>
  </div>
  <hr>
</body>
</html>

