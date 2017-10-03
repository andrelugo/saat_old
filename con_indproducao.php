<?
require_once("sis_valida.php");
require_once("sis_conn.php");

if (isset($_GET["txtDia"]) && $_GET["txtDia"]<>0){
	$dia=$_GET["txtDia"];
	$wSDia="and day(data_sai)=$dia";
	$wPDia="and day(data_pronto)=$dia";
}else{
	$dia="";
	$wSDia="";
	$wPDia="";
}
if (isset($_GET["txtMes"])){$mes=$_GET["txtMes"];}else{$mes=date("m");}
if (isset($_GET["txtAno"])){$ano=$_GET["txtAno"];}else{$ano=date("Y");}
if (isset($_GET["order"])){$order=$_GET["order"];}else{$order="order by cargo";}
if (isset($_GET["cmbColab"])){$cod_colab=$_GET["cmbColab"];}else{$cod_colab=0;}
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
.style5 {color: #000000}
.style6 {color: #006600}
.style7 {color: #FF0000}
-->
</style></head>
<body>
<p align="center"><span class="style1">Selecione um per&iacute;odo e um funcion&aacute;rio para realizar a busca no banco de dados<br>
</span></p>
<form name="form1" method="get" action="con_indproducao.php">
  <div align="center">
    <p>
      <input name="txtDia" type="text" id="txtDia" value="<? print($dia);?>" size="2" maxlength="2">
      de
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
Colaborador 
<select name="cmbColab" class="style5" id="select6"  tabindex="5" >
  <option value="0">TODOS</option>
  <?	  
$sql2="select * from rh_user where day(data_demissao)=0 order by nome";
$res2=mysql_db_query ("$bd",$sql2,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linha2 = mysql_fetch_array($res2)){
	if (isset($cod_colab)){
		if ($cod_colab==$linha2[cod]){
			print ("<option value= $linha2[cod] selected> $linha2[nome] </option>");
		}else{
			print ("<option value= $linha2[cod] > $linha2[nome] </option>");
		}
	}else{
		print ("<option value= $linha2[cod] > $linha2[nome] </option>");
	}
}
?>
</select>
-----
        
<input type="submit" name="Submit" value="Pesquisar">
<input type="hidden" name="order" value="<? print($order);?>">
<br>
    </p>
  </div>
</form>
<p align="center"><span class="style3">Indicador de Produ&ccedil;&atilde;o</span> <br>
<span class="style2">  </span></p>

<?
if ($cod_colab==0){
?>
	<table width="800" border="1" align="center">
	<tr>
	<td colspan="11"><div align="center"><span class="style2">TODOS</span></div></td>
	</tr>
	<tr>
	<td height="42"><a href="con_indproducao.php?<? print("txtMes=$mes&txtAno=$ano&order=order+by+nome");?>">Nome</a></td>
	<td><a href="con_indproducao.php?<? print("txtMes=$mes&txtAno=$ano&order=order+by+cargo");?>">Cargo</a></td>
	<td class="style1">Prod.<br>
CQ</td>
	<td class="style1">Prod. Tec</td>
	<td class="style7">Carência Tec</td>
	<td><span class="style6">Prontos Tec</span></td>
	<td width="48"><span class="style5">Pend.<br>
	  Atual</span></td>
	</tr>
<?	$sql="SELECT rh_user.cod as cod, rh_user.nome as nome, rh_cargo.descricao as cargo
	from rh_user inner 
	join rh_cargo on rh_cargo.cod = rh_user.cargo
	$order";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
	while ($linha = mysql_fetch_array($res)){
		$colab=$linha["cod"];
	
		$resu=mysql_query("select count(cp.cod) as tot, sum(carencia) as car FROM cp where cod_tec=$colab and month(data_sai) = $mes and year (data_sai)= $ano $wSDia");
		$totTec=mysql_result($resu,0,"tot");
		$totCarTec=mysql_result($resu,0,"car");

		$resu=mysql_query("select count(cp.cod) as tot FROM cp where cod_tec=$colab and month(data_pronto) = $mes and year (data_pronto)= $ano $wPDia");
		$prontosTec=mysql_result($resu,0,"tot");
	
		$resu=mysql_query("select count(cp.cod) as tot FROM cp where cod_cq=$colab and month(data_sai) = $mes and year (data_sai)= $ano $wSDia");
		$totCq=mysql_result($resu,0,"tot");
	
		$resu=mysql_query("select count(cp.cod) as tot FROM cp where cod_tec=$colab and data_sai is null and data_analize is not null");
		$totPend=mysql_result($resu,0,"tot");
	
		if($totTec<>0 || $totCq<>0 || $totPend<>0){
	?>
	<tr>
		<td width="184"><a href="con_indproducao.php?cmbColab=<? print ("$colab&txtAno=$ano&txtMes=$mes&txtDia=$dia");?>"><? print("$linha[nome]");?></a></td>
		<td width="186"><? print ($linha["cargo"]);?></td>
		<td width="123" class="style1"><? print ($totCq);?></td>
		<td width="88" class="style1"><? 
		$totTec=$totTec-$totCarTec;
		print ($totTec);?></td>
		<td width="73" class="style7"><? print ($totCarTec);?></td>
		<td width="52" class="style6"><? print ($prontosTec);?></td>
		<td class="style5"><? print ($totPend);?></td>
	
	</tr>
	<?
		}
	}	
	?>
	</table>
	
<?
}else{

$sqltec="select modelo.descricao as modelo,modelo.marca as marca, linha.descricao as linha, count(cp.cod) as entregues, sum(carencia) as carencia,modelo.tx_tec as txtec
from cp inner
join modelo on modelo.cod = cp.cod_modelo inner
join linha on linha.cod = modelo.linha 
where month(data_sai) = $mes and year(data_sai) = $ano $wSDia and cp.cod_tec = $cod_colab
group by modelo, linha
order by linha, modelo";
?>
<table width="886" border="1" align="center">
  <tr>
    <td colspan="7" class="style2"><div align="center">T&eacute;cnico</div></td>
  </tr>
  <tr>
    <td width="166">Marca</td>
	<td width="164">Modelo</td>
    <td width="197">Linha</td>
    <td width="82">Produzidos</td>
    <td width="65">Car&ecirc;ncia</td>
    <td width="66">R$ - Tx. T&eacute;c </td>
    <td width="100">R$ - Total</td>
  </tr>
<? 
$res=mysql_db_query ("$bd",$sqltec,$Link) or die ("Erro sqltec $sqltec <br> ".mysql_error());
$totent=0;
$totcar=0;
$totv=0;
while ($linha = mysql_fetch_array($res)){

?>
  <tr>
	<td><? print("$linha[marca]");?></td>
    <td><? print("$linha[modelo]");?></td>
    <td><? print("$linha[linha]");?></td>
    <td>&nbsp;<? 
	$totent=$totent+$linha["entregues"];
	print("$linha[entregues]");?></td>
    <td>&nbsp;<? print("$linha[carencia]");
	$totcar=$totcar+$linha["carencia"];?></td>
    <td><?  
	$txtec = "R$ " . number_format($linha["txtec"], 2, ',', '.'); 
	print($txtec);
	?></td>
    <td><? $valor=($linha["entregues"]-$linha["carencia"])*$linha["txtec"];	$valorF = "R$ " . number_format($valor, 2, ',', '.'); print($valorF);
	$totv=$totv+$valor;
	?></td>
  </tr>
<?
} ?>
  <tr>
    <td><span class="style1">TOTAL</span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="style1"><? print("$totent");?></span></td>
    <td><span class="style1"><font color="#FF0000"><? print("$totcar");?></font></span></td>
    <td>&nbsp;</td>
    <td><span class="style1"><?
	$totvF = "R$ " . number_format($totv, 2, ',', '.');
	print($totvF);
	?></span></td>
  </tr>
</table>
<p>&nbsp;</p>





<?
$sqltec="select modelo.descricao as modelo, linha.descricao as linha, count(cp.cod) as entregues, sum(carencia) as carencia,tx_cq
from cp inner
join modelo on modelo.cod = cp.cod_modelo inner
join linha on linha.cod = modelo.linha 
where month(data_sai) = $mes and year(data_sai) = $ano $wSDia and cp.cod_cq = $cod_colab
group by modelo, linha
order by linha, modelo";
?>
<table width="852" border="1" align="center">
  <tr>
    <td colspan="6" class="style2"><div align="center">Controle de Qualidade</div></td>
  </tr>
  <tr>
    <td width="161">Modelo</td>
    <td width="334">Linha</td>
    <td width="89">Entregues</td>
    <td width="66">Car&ecirc;ncia</td>
    <td width="86">R$ - Tx. CQ </td>
    <td width="76">R$ - Total</td>
  </tr>
<? 
$res=mysql_db_query ("$bd",$sqltec,$Link) or die ("Erro sqltec $sqltec <br> ".mysql_error());
$totent=0;
$totcar=0;
$totv=0;
while ($linha = mysql_fetch_array($res)){

?>
  <tr>
    <td><? print("$linha[modelo]");?></td>
    <td><? print("$linha[linha]");?></td>
    <td><? 
	$totent=$totent+$linha["entregues"];
	print("$linha[entregues]");?></td>
    <td>&nbsp;<? print("$linha[carencia]");
	$totcar=$totcar+$linha["carencia"];?></td>
    <td><? print("$linha[tx_cq]");?></td>
    <td><? $valor=($linha["entregues"]-$linha["carencia"])*$linha["tx_cq"]; print("$valor");
	$totv=$totv+$valor;
	?></td>
  </tr>
<?
} ?>
  <tr>
    <td><span class="style1">TOTAL</span></td>
    <td>&nbsp;</td>
    <td><span class="style1"><? print("$totent");?></span></td>
    <td><span class="style1"><font color="#FF0000"><? print("$totcar");?></font></span></td>
    <td>&nbsp;</td>
    <td><span class="style1"><? print("R$ $totv,00");?></span></td>
  </tr>
</table>
<p>&nbsp;</p>



<table width="640" border="1">
  <tr>
    <td colspan="5" class="style2"><div align="center">Controlador de Qualidade </div></td>
  </tr>
  <tr>
    <td width="473">Modelo</td>
    <td width="77">Embalados</td>
    <td width="54">Reprovados</td>
    <td width="1">&nbsp;</td>
    <td width="1">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="804" border="1">
  <tr>
    <td colspan="4" class="style2"><div align="center">Pe&ccedil;as trocadas em GARANTIA</div></td>
  </tr>
  <tr>
    <td width="103">C&oacute;digo</td>
    <td width="470">Descri&ccedil;&atilde;o</td>
    <td width="72">Qtdade</td>
    <td width="145">Tempo recebimento </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="839" border="1">
  <tr>
    <td colspan="6" class="style2"><div align="center">Pe&ccedil;as trocadas em OR&Ccedil;AMENTO </div></td>
  </tr>
  <tr>
    <td width="103">C&oacute;digo</td>
    <td width="359">Descri&ccedil;&atilde;o</td>
    <td width="56">Qtdade</td>
    <td width="55">Vl Unit. </td>
    <td width="104">R$ Aprovados</td>
    <td width="122">R$ Reprovados</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>Destino</p>
<p>Tempo de Giro de mercadorias</p>
<p>Produ&ccedil;&atilde;o nos ultimos 12 meses </p>
<p>Custo M.O. T&eacute;c = 16% dir e 30% total<br>
  Custo C.Q. = 5&uml;% dir e 9% total<br>
  Custo com ADM = 9% total<br>
  Outros custos = 5% total (telefone, almo&ccedil;o, transporte&quot;n&atilde;o folha&quot;, sa&uacute;de, contabilidade, seguro patrimonial, etc)<br>
  Impostos = 19,1% total com M.O.
  (5% ISS, 14,1% Simples Federeal, 0,38% CPMF) <br>
  Margem de lucro = 27,9 % </p>
<?
}
?>
<p>&nbsp;</p>
</body>
</html>
