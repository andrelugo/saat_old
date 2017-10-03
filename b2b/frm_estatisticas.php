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
if (isset($_GET["cmbModelo"])){
	$codModelo=$_GET["cmbModelo"]; 
	if ($codModelo==""){
		$wereModelo="";		
	}else{
		$wereModelo=" and cod_modelo=".$_GET["cmbModelo"];
	}
}else{
	$wereModelo="";
}
if (isset($_GET["cmbLoja"])){
	$codLoja=$_GET["cmbLoja"];
	if ($codLoja==""){
		$wereLoja="";		
	}else{
		$wereLoja=" and cp.filial=".$_GET["cmbLoja"];
	}
}else{
	$wereLoja="";
}

$sqlTotal=mysql_query("SELECT count( cp.cod ) AS qt FROM cp inner join modelo on modelo.cod = cp.cod_modelo 
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim') and modelo.cod_fornecedor=$id $wereModelo;")or die(mysql_error());
$Total=mysql_result($sqlTotal,0,"qt");
?>
<html>
<head>
<title>Formulário de Estatisticas</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="0">
<p align="center" class="Titulo2">Estatísticas </p>
<form name="form1" method="get" action="frm_estatisticas.php">
  <div align="center">
    <p class="caixaPR1">
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
------
<input type="submit" name="Submit" value="Pesquisar">
<input type="hidden" name="order" value="<? print($order);?>">
<br>
Modelo:
<select name="cmbModelo" class="caixaPR1" id="cmbModelo">
            <option value="">Todos</option>
  <?	  
$sql="select * from modelo where modelo.cod_fornecedor=$id";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Modelo");
while ($linha = mysql_fetch_array($res)){
	if (isset($codModelo)){
		if ($codModelo==$linha[cod]){
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
 <br>
 Loja:
 <select name="cmbLoja" class="caixaPR1" id="cmbLoja">
   <option value="">Todos</option>
   <?	  
$sql="select * from filial_cbd order by descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela filia_CBD");
while ($linha = mysql_fetch_array($res)){
	if (isset($codLoja)){
		if ($codLoja==$linha[descricao]){
		print ("<option value= $linha[descricao] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[descricao] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[descricao] > $linha[descricao] </option>");
	}
}
?>
 </select>
 <br>
    </p>
  </div>
</form>
<?
$sql=mysql_query("SELECT avg(DATEDIFF(data_sai , data_entra)) as media FROM cp where (data_sai BETWEEN '$dtini' AND '$dtfim') $wereLoja $wereModelo")or die(mysql_error());
$resul=mysql_result($sql,0,"media");
$medi = number_format($resul, 2, ',', '.') . "";
?>
<p align="center" class="Cabe&ccedil;alho">Tempo m&eacute;dio de Giro de mercadorias entregues neste período <span class="Titulo2"><? print($medi);?> dias</span></p>
<hr>
<div align="center"><span class="Titulo2">&Iacute;ndice de Modelos</span> </div>
<table width="713" border="1" align="center">
  <tr class="Cabe&ccedil;alho">
    <td width="121"><div align="center">Código</div></td>
    <td width="324"><div align="center">Modelo</div></td>
    <td width="70"><div align="center">Quantidade</div></td>
    <td width="80"><div align="center">%</div></td>
    <td width="84"><div align="center">Giro/Dias</div></td>
  </tr>
  <?
$countS = 0;
$sql="SELECT modelo.cod_produto_fornecedor as codfor, modelo.cod as cod, COUNT(cp.cod) as qt, modelo.descricao as descricao 
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
GROUP BY DESCRICAO,codfor,cod
order by qt desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
while ($linha = mysql_fetch_array($res)){

$sql=mysql_query("SELECT avg(DATEDIFF(data_sai , data_entra)) as media FROM cp where (data_sai BETWEEN '$dtini' AND '$dtfim') and cod_modelo=$linha[cod]")or die(mysql_error());
$result=mysql_result($sql,0,"media");
$med = number_format($result, 2, ',', '.') . "";


$tot=($linha["qt"]/$Total)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";

		print ("<tr><td> $linha[codfor] </td><td> $linha[descricao] </td><td> $linha[qt] </td><td>$Rvalor</td><td>$med</td></tr>");
		$countS = $countS+$linha["qt"];
}
?>
  <tr>
    <td><strong>TOTAL <? print($Total);?></strong></td>
    <td colspan="4"><strong><?print("$countS");?></strong></td>
  </tr>
</table>
<hr>
<div align="center">
    <span class="Titulo2">&Iacute;ndice de Destinos</span>
</div>
<div align="center">
<table width="460" border="1">
    <tr class="Cabe&ccedil;alho">
      <td width="312"><div align="center">Destino</div></td>
      <td width="70"><div align="center">Quantidade</div></td>
      <td width="56"><div align="center">%</div></td>
    </tr>
<?
$countE = 0;
$sql="SELECT count( cp.cod ) AS qt, destino.descricao AS descricao
FROM cp
INNER JOIN destino ON destino.cod = cp.cod_destino
inner join modelo on modelo.cod = cp.cod_modelo
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
GROUP BY descricao
order by qt desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt]</td>");
		$countE = $countE+$linha["qt"];
?>
<td>
<?
$tot=($linha["qt"]/$Total)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td></tr>

<?		
}
?>

    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="3"><strong><?print("$countE");?></strong></td>
    </tr>
  </table>
</div>
<hr> 
<div align="center" class="Titulo2">&Iacute;ndice de Defeitos
</div>
<table width="460" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="317"><div align="center">Defeito</div></td>
      <td width="70"><div align="center">Quantidade</div></td>
      <td width="51"><div align="center">%</div></td>
    </tr>
<?
$countS = 0;
$sql="SELECT COUNT(cp.cod) as qt,defeito.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join defeito on defeito.cod = cp.cod_defeito
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
GROUP BY DESCRICAO
order by qt desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td>");
		$countS = $countS+$linha["qt"];
?>
<td>
<?
$tot=($linha["qt"]/$Total)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td></tr>

<?		
}
?>

    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$countS");?></strong></td>
    </tr>
</table>
<hr>
<div align="center" class="Titulo2">&Iacute;ndice de Solu&ccedil;&otilde;es </div>
<table width="460" border="1" align="center">
  <tr class="Cabe&ccedil;alho">
    <td width="317"><div align="center">Defeito</div></td>
    <td width="70"><div align="center">Quantidade</div></td>
    <td width="51"><div align="center">%</div></td>
  </tr>
  <?
$countS = 0;
$sql="SELECT COUNT(cp.cod) as qt,solucao.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join solucao on solucao.cod = cp.cod_solucao
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
GROUP BY DESCRICAO
order by qt desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td>");
		$countS = $countS+$linha["qt"];
?>
  <td>
      <?
$tot=($linha["qt"]/$Total)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
  </tr>
  <?		
}
?>
  <tr>
    <td><strong>TOTAL</strong></td>
    <td colspan="2"><strong><?print("$countS");?></strong></td>
  </tr>
</table>
<hr>
<div align="center"><span class="Titulo2">Índice de Peças</span> em Garantia
</div>
<table width="790" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="101"><div align="center">Código</div></td>
      <td width="517"><div align="center">Peça</div></td>
      <td width="70"><div align="center">Quantidade</div></td>
      <td width="74"><div align="center">%</div></td>
    </tr>
<?
$sqlTotal2=mysql_query("SELECT sum( pedido.qt ) AS qt 
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo 
inner join pedido on pedido.cod_cp = cp.cod
inner join peca on peca.cod = pedido.cod_peca
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim') 
and modelo.cod_fornecedor=$id $wereModelo $wereLoja;")or die(mysql_error());
$Total2=mysql_result($sqlTotal2,0,"qt");

$countS = 0;
$sql="SELECT peca.cod_fabrica as codpeca ,sum(pedido.qt) as qt,peca.descricao as descricao
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join pedido on pedido.cod_cp = cp.cod
inner join peca on peca.cod = pedido.cod_peca
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
GROUP BY DESCRICAO, codpeca
order by qt desc;";

$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[codpeca] </td><td> $linha[descricao] </td><td> $linha[qt] </td>");
		$countS = $countS+$linha["qt"];
?>
<td>
<?
$tot=($linha["qt"]/$Total2)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td></tr>

<?		
}
?>

    <tr>
      <td><strong>TOTAL <? print($Total2);?></strong></td>
      <td colspan="3"><strong><?print("$countS");?></strong></td>
    </tr>
</table>

<br>
<hr>
<div align="center"><span class="Titulo2">Indice de Lojas</span></div>
<table width="325" border="1" align="center">
  <tr class="Cabe&ccedil;alho">
    <td width="57"><div align="center">Filial</div></td>
	<td width="134"><div align="center">Cidade</div></td>
    <td width="112"><div align="center">Total</div></td>
  </tr>
  <?
$sql="SELECT count(cp.cod) as tot, cp.filial as filial, filial_cbd.cidade as cidade
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join filial_cbd on filial_cbd.descricao = cp.filial
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja
group by filial
order by tot desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
$tot1=0;
while ($linha1 = mysql_fetch_array($res)){
	$tot1+=$linha1["tot"];
	?>
  <tr>
    <td><? print($linha1["filial"]);?></td>
	<td><? print($linha1["cidade"]);?></td>
    <td width="112"><? print($linha1["tot"]);?></td>
  </tr>
  <?
}
?>
  <tr>
    <td><strong>TOTAL </strong></td>
    <td colspan="2"><? print($tot1);?></td>
  </tr>
</table>
<br>
<div align="center"><span class="Titulo2">Lojas Detalhado </span></div>
<table width="951" border="1" align="center">
  <tr class="Cabe&ccedil;alho">
    <td width="10"><div align="center">N</div></td>
    <td width="65"><div align="center">Loja</div></td>
    <td width="100"><div align="center">Cidade</div></td>
    <td width="122"><div align="center">Barcode</div></td>
	<td width="122"><div align="center">Série</div></td>
	<td width="122"><div align="center">O.S.</div></td>
    <td width="58"><div align="center">Marca</div></td>
    <td width="67"><div align="center">Modelo</div></td>
    <td width="82"><div align="center">Defeito (Etiqueta) </div></td>
    <td width="204"><div align="center">Defeito Constatado </div></td>
    <td width="201"><div align="center">Solu&ccedil;&atilde;o</div></td>
	<td width="201"><div align="center">Observações</div></td>
  </tr>
  <?
$countS = 0;
$sql="SELECT filial, filial_cbd.cidade AS descloja, barcode, marca, modelo.descricao AS modelo, defeito_reclamado, defeito.descricao AS defeito, 
solucao.descricao AS solucao,serie,os_fornecedor,item_os_fornecedor,cp.obs as obs
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
INNER JOIN filial_cbd ON filial_cbd.descricao = cp.filial
INNER JOIN defeito ON defeito.cod = cp.cod_defeito
INNER JOIN solucao ON solucao.cod = cp.cod_solucao
WHERE (data_sai BETWEEN '$dtini' AND '$dtfim')
and modelo.cod_fornecedor=$id
$wereModelo $wereLoja";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql".mysql_error());
$count=0;
while ($linha = mysql_fetch_array($res)){
	$count++;
	?>
  <tr>
    <td><? print($count);?></td>
    <td><? print($linha["filial"]);?></td>
    <td><? print($linha["descloja"]);?></td>
    <td><? print($linha["barcode"]);?></td>
	<td><? print($linha["serie"]);?></td>
	<td><? print($linha["os_fornecedor"]."-".$linha["item_os_fornecedor"]);?></td>
    <td><? print($linha["marca"]);?></td>
    <td><? print($linha["modelo"]);?></td>
    <td><? print($linha["defeito_reclamado"]);?></td>
    <td><? print($linha["defeito"]);?></td>
    <td><? print($linha["solucao"]);?></td>
	<td><? print($linha["obs"]);?></td>
  </tr>
  <?
}
?>
  <tr>
    <td colspan="2"><strong>TOTAL </strong></td>
    <td colspan="7"><strong><? print($count);?></strong></td>
  </tr>
</table>
<div align="center"></div>
<p>&nbsp;</p>
</body>
</html>
