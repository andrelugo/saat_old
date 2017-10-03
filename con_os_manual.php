<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtAno"])){$ano=$_GET["txtAno"];}else{$ano=date("Y");}
if (isset($_GET["txtMes"])){$mes=$_GET["txtMes"];}else{$mes=date("m");}
if (isset($_GET["cmbFornecedor"])){
	$codfornecedor=$_GET["cmbFornecedor"];
	if ($codfornecedor<>0){
		$resDes=mysql_query("select descricao from fornecedor where cod=$codfornecedor");
		$desc=mysql_result($resDes,0,"descricao");
	}else{
		$desc="Não definido";	
	}
}else{
	$codfornecedor=0;
	$desc="Não definido";
}?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
.style1 {font-weight: bold}
.style2 {font-size: 18px}
.style3 {font-weight: bold; font-size: 24px; }
-->
</style></head>
<body>
<span class="style1">
<div align="center">Gerar OS(PDF)</div>
</span>
<form name="form1" method="get" action="pdf_os.php" target="_blank">
    <p align="center" class="style7"><span class="style1"> M&ecirc;s
    <input name="cmdAcuMes" type="submit" id="cmdAcuMes" value="Gerar / Visualizar OS">
    Fornecedor:
    <select name="cmbFornecedor" class="style5" id="select6"  tabindex="5">
      <option value="0"></option>
      <? $sql="select * from fornecedor where os_auto=3";
	  $sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linha = mysql_fetch_array($res)){
	if (isset($codfornecedor)){
		if ($codfornecedor==$linha[cod]){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
    </select>
    Exercicio:
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
    </span></p>
</form>
<hr>
<hr>
<div align="center"><span class="style1">Selecione um per&iacute;odo e um fornecedor para realizar a busca no banco de dados</span>
</div>
<form name="form1" method="get" action="con_os_manual.php">
        <p align="center">
    <input name="cmdAcuMes" type="submit" id="cmdAcuMes" value="Ordens de Serviço">
    Fornecedor:
<select name="cmbFornecedor" class="style5" id="select6"  tabindex="5">
<option value="0"></option>
<? $sql="select * from fornecedor where os_auto=2";
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linha = mysql_fetch_array($res)){
	if (isset($codfornecedor)){
		if ($codfornecedor==$linha[cod]){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
    </select>
    -- Exercicio:
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
</form>
<p align="center"><span class="style3">Ordem de Servi&ccedil;o </span><br>
<span class="style2">  </span></p>
<table width="885" border="1" align="center">
<tr>
<td colspan="14"><div align="center"><span class="style2">Fornecedor :<span class="style3"><? print($desc);?></span> Exercicio  <? print("$mes/$ano");?></span></div></td>
</tr>
  <tr class="style1">
	<td width="59">Barcode</td>
	<td width="59">Filial</td>
   	<td width="41">O. S.</td>
    <td width="23">Itm</td>
	<td width="78">Data Entra</td>
   <td width="51">Marca</td>
    <td width="65">Modelo</td>
    <td width="59">S&eacute;rie</td>
	<td width="226">Defeito</td>
	<td width="226">Solução</td>
	<td width="226">Destino</td>
	<td width="42">Data Sai </td>
	<td width="76">TX. M.O.</td>
	<td width="95">EXTRATO</td>
  </tr>
<? $sql="select barcode,destino.descricao as destino,os_fornecedor as os,item_os_fornecedor as itemos, date_format(data_entra,'%d/%m/%y') as dtentra, modelo.marca as marca,
modelo.descricao as modelo,serie as serie,date_format(data_sai,'%d/%m/%y') as dtsai, defeito.descricao as defeito,modelo.tx_mo, extrato_mo.descricao as extrato,
cp.filial as filial, solucao.descricao as solucao
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner
join defeito on defeito.cod = cp.cod_defeito inner
join solucao on solucao.cod = cp.cod_solucao left
join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo inner
join destino on destino.cod = cp.cod_destino
where month(data_sai) = $mes 
AND YEAR( data_sai ) = $ano 
and modelo.cod_fornecedor=$codfornecedor
order by os,itemos";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
$txTotal=0;
while ($linha = mysql_fetch_array($res)){
$valor=$linha["tx_mo"]; 
$txTotal=$txTotal+$valor;
?>
<tr>
	<td><? print ($linha["barcode"]);?></td>
	<td><? print ($linha["filial"]);?></td>
	<td><? print ($linha["os"]);?></td>
	<td><? print ($linha["itemos"]);?></td>
	<td><? print ($linha["dtentra"]);?></td>
	<td><? print ($linha["marca"]);?></td>
	<td><? print ($linha["modelo"]);?></td>
	<td><? print ($linha["serie"]);?></td>
	<td><? print ($linha["defeito"]);?></td>
	<td><? print ($linha["solucao"]);?></td>
	<td><? print ($linha["destino"]);?></td>
	<td><? print ($linha["dtsai"]);?></td>
	<td><? print("R$ ".number_format($valor, 2, ',', '.')); ?></td>
	<td><? print ("&nbsp;".$linha["extrato"]);?></td>
</tr>
<?
}	
?>
</table>
<p>&nbsp;</p><hr>
<p align="center" class="style3">Resumo</p> 
<table width="631" border="1" align="center">
    <tr>
      <td width="241"><div align="center" class="style1">Modelo</div></td>
      <td width="116"><div align="center" class="style1">Quantidade</div></td>
      <td width="114"><div align="center" class="style1">M.O. Unitária</div></td>
      <td width="132"><div align="center" class="style1">M.O. Total</div></td>
    </tr>
<?
$countE = 0;
$TmoTot = 0;
$sql="SELECT COUNT( cp.cod ) AS qt, modelo.descricao AS descricao,tx_mo
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
WHERE MONTH( data_sai ) = $mes 
AND YEAR( data_sai ) = $ano 
and modelo.cod_fornecedor=$codfornecedor
GROUP BY DESCRICAO
ORDER BY DESCRICAO;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
	$qt=$linha["qt"];
	$mo=$linha["tx_mo"];
	$moTot=$qt*$mo;
	$TmoTot=$TmoTot+$moTot;
	print ("<tr><td> $linha[descricao] </td>");
	print ("<td> $qt </td>");
	
	print("<td>R$ ".number_format($mo, 2, ',', '.')."</td>");
	print("<td>R$ ".number_format($moTot, 2, ',', '.')."</td></tr>");
	$countE = $countE+$linha["qt"];
}
?>

    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$countE");?></strong></td>
      <td><strong><? print("R$ ".number_format($TmoTot, 2, ',', '.'));?></strong></td>
    </tr>
</table>
</body>
</html>
