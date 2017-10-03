<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtDiaIni"])){$DiaIni=$_GET["txtDiaIni"];}else{$DiaIni=date("d");}
if (isset($_GET["txtMesIni"])){$MesIni=$_GET["txtMesIni"];}else{$MesIni=date("m");}
if (isset($_GET["txtAnoIni"])){$AnoIni=$_GET["txtAnoIni"];}else{$AnoIni=date("Y");}
if (isset($_GET["txtDiaFim"])){$DiaFim=$_GET["txtDiaFim"];}else{$DiaFim=date("d");}
if (isset($_GET["txtMesFim"])){$MesFim=$_GET["txtMesFim"];}else{$MesFim=date("m");}
if (isset($_GET["txtAnoFim"])){$AnoFim=$_GET["txtAnoFim"];}else{$AnoFim=date("Y");}
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
}
$dtini="$AnoIni-$MesIni-$DiaIni";
$dtfim="$AnoFim-$MesFim-$DiaFim";
if (isset($_GET["order"])){$order=$_GET["order"];}else{$order="";}
$sql="SELECT peca.cod_fabrica AS cod, peca.descricao AS descr, sum( pedido.qt ) AS solic
FROM peca INNER JOIN pedido ON pedido.cod_peca = peca.cod
WHERE data_cad BETWEEN ('$dtini')AND('$dtfim')
and peca.cod_fornecedor = $codfornecedor
GROUP BY cod, descr
$order";
?>
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
.style4 {color: #FF0000}
-->
</style></head>
<body>
<p align="center"><span class="style1">Selecione um per&iacute;odo e um fornecedor para realizar a busca no banco de dados<br>
</span></p>
<form name="form1" method="get" action="con_pedido_manual.php">
  <div align="center">
    <p>
      De:
      <input name="txtDiaIni" type="text" id="txtDiaIni" value="<?print ($DiaIni);?>" size="3" maxlength="2">
    /
    <input name="txtMesIni" type="text" id="txtMesIni" size="3" maxlength="2" value="<?print ($MesIni);?>" >
    /
    <input name="txtAnoIni" type="text" id="txtAnoIni" size="5" maxlength="4" value="<?print ($AnoIni);?>" >   
    ------
    At&eacute;:
    <input name="txtDiaFim" type="text" id="txtDiaFim" size="3" maxlength="2" value="<?print ($DiaFim);?>" >
/
<input name="txtMesFim" type="text" id="txtMesFim" size="3" maxlength="2" value="<?print ($MesFim);?>" >
/
<input name="txtAnoFim" type="text" id="txtAnoFim" size="5" maxlength="4" value="<?print ($AnoFim);?>" > 
------<select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
            <option value="0"></option>
<?	  
$sqlF="select * from fornecedor";
$resF=mysql_db_query ("$bd",$sqlF,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linhaF = mysql_fetch_array($resF)){
	if (isset($codfornecedor)){
		if ($codfornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
          </select>
<input type="submit" name="Submit" value="Pesquisar">
<input type="hidden" name="order" value="<? print($order);?>">
<br>

    </p>
  </div>
</form>
<p align="center"><span class="style3">Pedido de Pe&ccedil;as</span><br>
<span class="style2">  </span></p>
<table width="702" border="1" align="center">
<tr>
<td colspan="3"><div align="center"><span class="style2">Fornecedor :<span class="style3"><? print($desc);?></span>  Periodo de <? print("$DiaIni/$MesIni/$AnoIni");?> &agrave; <? print("$DiaFim/$MesFim/$AnoFim");?> </span></div></td>
</tr>
  <tr>
   <td width="71" class="style2"><a href="con_pedido_manual.php?order=order by cod&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim&cmbFornecedor=$codfornecedor")?>">C&oacute;d. Pe&ccedil;a </span></a></td>
   <td width="506"><a href="con_pedido_manual.php?order=order by descr&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim&cmbFornecedor=$codfornecedor")?>">Descricao</a></td>
    <td width="103"><strong><a href="con_pedido_manual.php?order=order by solic&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim&cmbFornecedor=$codfornecedor")?>">Qt Solicitada</strong></a></td>
  </tr>
<?
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
	<td><? print ($linha["cod"]);?></td>
	<td><? print ($linha["descr"]);?></td>
	<td><? print ($linha["solic"]);?></td>
</tr>
<?
}	
?>
</table>
<p>&nbsp;</p><hr>
<p>&nbsp;</p>
<table width="702" border="1" align="center">
<tr>
<td colspan="3"><div align="center" class="style3">Pend&ecirc;ncias
<? print($desc);?> Acumuladas at&eacute; <? print(date("d/m/Y"));?></div></td>
</tr>

  <tr>
   <td width="71" class="style2">C&oacute;d. Pe&ccedil;a </span></a></td>
   <td width="506">Descricao</a></td>
    <td width="103"><strong>Qt Solicitada</strong></a></td>
  </tr>
<?
$sql="SELECT peca.cod_fabrica AS cod, peca.descricao AS descr, sum( pedido.qt ) AS solic
FROM peca INNER JOIN pedido ON pedido.cod_peca = peca.cod inner
join cp on cp.cod = pedido.cod_cp
WHERE data_pronto is null
and peca.cod_fornecedor = $codfornecedor
GROUP BY cod, descr
order by solic desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
	<td><? print ($linha["cod"]);?></td>
	<td><? print ($linha["descr"]);?></td>
	<td><? print ($linha["solic"]);?></td>
</tr>
<?
}	
?>
</table>
<p><br>
  <span class="style4">OBS.: Nesta pend&ecirc;ncia visualiza-se somente os itens necess&aacute;rios para a libera&ccedil;&atilde;o de todos os produtos, do fornecedor escolhido, que est&atilde;o sob responsabilidade da Penha Tv Color e ainda n&atilde;o prontos. Diverg&ecirc;ncias de informa&ccedil;&atilde;o devem-se &agrave; m&aacute; utiliza&ccedil;&atilde;o do SAAT II por parte da equipe t&eacute;cnica.<br>
Se todo o material necess&aacute;rio para a libera&ccedil;&atilde;o de um equipamento for lan&ccedil;ado no sistema esta informa&ccedil;&atilde;o ser&aacute; corretamente visualizada aqui! </span></p>
<p>Esta pendencia n&atilde;o controla o atendimento de pedidos realizados para produtos que j&aacute; foram marcados como pronto pela equipe t&eacute;cnica! </p>
</body>
</html>
