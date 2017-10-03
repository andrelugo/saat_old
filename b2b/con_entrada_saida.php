<? //toda e qulquer consulta do b2b deve ser filtrada por  "modelo.cod_fornecedor=$id and "
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtMes"])){$mes=$_GET["txtMes"];}else{$mes=date("n");}
if (isset($_GET["txtAno"])){$ano=$_GET["txtAno"];}else{$ano=date("Y");}
if (isset($_GET["chkGrafico"])){
	if($_GET["chkGrafico"]==1){
		Header("Location:gra_desempenho.php?m=$mes&a=$ano&f=$id");
		exit;
	}
}
?>
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="0">
<td><div align="center">
      <p class="Titulo2">Indicador de Desempenho  Mensal</p>
      <form name="form1" method="get" action="con_entrada_saida.php">
        <p>
    Hist&oacute;rico de
    <select name="txtMes" id="txtMes">
          <option value="1" <? if ($mes==1){print ("selected");}?>>Janeiro</option>
          <option value="2"<? if ($mes==2){print ("selected");}?>>Fevereiro</option>
          <option value="3"<? if ($mes==3){print ("selected");}?>>Mar&ccedil;o</option>
          <option value="4"<? if ($mes==4){print ("selected");}?>>Abril</option>
          <option value="5"<? if ($mes==5){print ("selected");}?>>Maio</option>
          <option value="6"<? if ($mes==6){print ("selected");}?>>Junho</option>
          <option value="7"<? if ($mes==7){print ("selected");}?>>Julho</option>
          <option value="8"<? if ($mes==8){print ("selected");}?>>Agosto</option>
          <option value="9" <? if ($mes==9){print ("selected");}?>>Setembro</option>
          <option value="10"<? if ($mes==10){print ("selected");}?>>Outubro</option>
          <option value="11"<? if ($mes==11){print ("selected");}?>>Novembro</option>
          <option value="12"<? if ($mes==12){print ("selected");}?>>Dezembro</option>
          </select>
          de
          <input name="txtAno" type="text" id="txtAno" value="<? print($ano);?>" size="4" maxlength="4">
        </p>
        <p>
          <input type="checkbox" name="chkGrafico" value="1">
-  Gerar Gr&aacute;fico</p>
        <p> 
          <input name="cmdAcuMes" type="submit" id="cmdAcuMes3" value=" Consultar">
        </p>
      </form>
      <br>
Fornecedor: Todos <br>
Servidor: Todos 
</p>
</div></td>
<p align="center"><span class="style1">Entrada<br>
</span></p>
<div align="center">
  <table width="460" border="1">
    <tr class="Cabe&ccedil;alho">
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
and modelo.cod_fornecedor=$id
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
    <tr class="Cabe&ccedil;alho">
      <td width="198"><div align="center">Modelo</div></td>
      <td width="107"><div align="center">Quantidade</div></td>
    </tr>
<?
$countS = 0;
$sql="SELECT COUNT(cp.cod) as qt,modelo.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
where MONTH(data_sai) = $mes and YEAR(data_sai) = $ano
and modelo.cod_fornecedor=$id
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
  <p align="center" class="Titulo1"> Acumulado : <? $res=$countE-$countS;  print ($res);?>
<?///////////TESTE DE HISTÓRICO DE PRODUÇÃO?>
<hr>
<p align="center"><span class="style1">Histórico</span></p>
  <table width="460" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="198"><div align="center">Data</div></td>
      <td width="107"><div align="center">Entrata</div></td>
      <td width="107"><div align="center">Saída</div></td>
      <td width="107"><div align="center">Saldo</div></td>
    </tr>
<?
$countS = 0;
$sql="SELECT day(data_sai) as dtpesquisada
FROM cp
where month(data_sai)=$mes and year(data_sai)=$ano
group by dtpesquisada";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à saída de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
	$dia="$linha[dtpesquisada]";
	if(strlen($dia)==1){$dia="0$linha[dtpesquisada]";}
	if(strlen($mes)==1){$mes="0".$mes;}
	$dtprint="$dia/$mes/$ano";
	?>
	<tr><td><? print ($dtprint);?> </td>
	<?
	$datapes="$ano-$mes-$dia";
	$sql="SELECT (SELECT count(cod) FROM `cp` WHERE date(data_entra) <= '$datapes') as entrada,count(cod) as saida
	FROM `cp` 
	WHERE date(data_sai) <= '$datapes'";
	//die($sql);
	$res2=mysql_db_query ("$bd",$sql,$Link) or die ("Erro".mysql_error());
	$qtentra=mysql_result($res2,0,"entrada");
	$qtsaida=mysql_result($res2,0,"saida");
	$saldo=$qtentra-$qtsaida;
	
	$sql3="select count(cod) as entra,(select count(cod) from cp where day(data_sai)='$dia' and month(data_sai)='$mes' and year(data_sai)='$ano') as sai from cp where day(data_entra)='$dia' and month(data_entra)='$mes' and year(data_entra)='$ano'";
	//die($sql3);
	$res3=mysql_query($sql3);
	$entra=mysql_result($res3,0,"entra");
	$sai=mysql_result($res3,0,"sai");
	?>
			<td> <? print("$entra"); ?> </td>
			<td> <? print("$sai"); ?> </td>
			<td> <? print("$saldo"); ?> </td>
	</tr>
	<?
//		$countS = $countS+$linha["qt"];
}
?>
    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$countS");?></strong></td>
    </tr>
</table>
<?/////////// FIM TESTE DE HISTÓRICO DE PRODUÇÃO?>
</body>
</html>
