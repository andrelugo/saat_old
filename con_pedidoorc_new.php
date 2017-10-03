<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtDiaIni"])){$DiaIni=$_GET["txtDiaIni"];}else{$DiaIni=date("d");}
if (isset($_GET["txtMesIni"])){$MesIni=$_GET["txtMesIni"];}else{$MesIni=date("m");}
if (isset($_GET["txtAnoIni"])){$AnoIni=$_GET["txtAnoIni"];}else{$AnoIni=date("Y");}
if (isset($_GET["txtDiaFim"])){$DiaFim=$_GET["txtDiaFim"];}else{$DiaFim=date("d");}
if (isset($_GET["txtMesFim"])){$MesFim=$_GET["txtMesFim"];}else{$MesFim=date("m");}
if (isset($_GET["txtAnoFim"])){$AnoFim=$_GET["txtAnoFim"];}else{$AnoFim=date("Y");}
$dtini="$AnoIni-$MesIni-$DiaIni";
$dtfim="$AnoFim-$MesFim-$DiaFim";
if (isset($_GET["order"])){$order=$_GET["order"];}else{$order="";}
$sqlAg="SELECT peca.cod_fabrica AS cod, peca.descricao AS descr, sum( orc.qt ) AS solic, peca.custo as custo
FROM peca 
INNER JOIN orc ON orc.cod_peca = peca.cod
inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
WHERE (cod_orc_compra is null)
and (orc_decisao.aprova=1)
GROUP BY cod, descr
$order";
?>
<html>
<head>
<title></title>
</head>
<body>
<p align="center"><strong>Pedido de compra de peças aprovadas em orçamento</strong></p>
<form name="form1" method="post" action="">	
  <table width="799" border="1" align="center">
    <tr>
      <td width="165">Descri&ccedil;&atilde;o (numero) </td>
      <td width="163"><input type="text" name="textfield"></td>
      <td width="211">Data</td>
      <td width="232"><input type="text" name="textfield2"></td>
    </tr>
    <tr>
      <td>Fornecedor</td>
      <td><select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
              <option value="0">Selecione</option>
              <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
    </select></td>
      <td>Linha</td>
      <td><select name="select2" class="style5" id="select2"  tabindex="5" >
        <option value="0">Selecione</option>
        <?	  
$sql="select * from linha where ativo = 1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
      </select></td>
    </tr>
    <tr>
      <td>Cliente</td>
      <td><select name="select" class="style5" id="select"  tabindex="5" >
        <option value="0">Selecione</option>
        <?	  
$sql="select * from cliente where revenda = 1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Valor</td>
      <td><input type="text" name="textfield3"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">Observa&ccedil;&otilde;es.:
      <textarea name="txtObs" tabindex="10" cols="90" rows="4" id="txtObs"><? if(isset($obs)){print($obs);}?>
		</textarea></td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="Submit" value="Cadastrar">
  </p>
</form>
<p>&nbsp;</p>

<table width="809" border="1" align="center">
  <tr>
   <td width="71" class="style2"><a href="con_pedidoorc.php?order=order by cod&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>">C&oacute;d. Pe&ccedil;a </span></a></td>
   <td width="506"><a href="con_pedidoorc.php?order=order by descr&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>">Descricao</a></td>
    <td width="101"><strong><a href="con_pedidoorc.php?order=order by solic&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>"><strong>Qt Aprovada</strong></a></strong></td>
    <td width="103">Custo Unit.</td>
    <td width="103">Custo Total</td>
  </tr>
<?
$res=mysql_db_query ("$bd",$sqlAg,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
	<td><? print ($linha["cod"]);?></td>
	<td><? print ($linha["descr"]);?></td>
	<td><? print ($linha["solic"]);?></td>
	<td><? print ($linha["custo"]);?></td>
	<td><? $tot=$linha["custo"]*$linha["solic"]; print ($tot);?></td>
</tr>
<?
}	
?>
</table>
</body>
</html>
