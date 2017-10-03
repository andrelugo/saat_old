<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$orc=$_GET["txtOrc"];
?>
<html>
<head>
<title></title>
<link href="" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-size: 18px}
-->
</style>
</head>
<body>
<div align="center">
  <p><span class="style1"><img src="img/timbre1.bmp"></span>

    <?
$sql="select cod_orc_pre_nota,data_abre,nota,data_nota,valor_tot 
from orc inner 
join orc_pre_nota on orc_pre_nota.cod = orc.cod_orc_pre_nota 
where cod_orc_coletivo = $orc
group by cod_orc_pre_nota";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows<>0){
?>
</p>
  <p>Este Or&ccedil;amento j&aacute; possui Pr&eacute; Notas -- Siginifica que esta deve ser uma 2&ordf; VIA </p>
  <table width="594" border="1">
  <tr>
    <td width="94">Pr&eacute;-Nota</td>
    <td width="113">Data/Pr&eacute;-Nota</td>
    <td width="124">Nota Fiscal </td>
    <td width="125">Data Nota Fiscal </td>
    <td width="104">Valor</td>
  </tr>
 <?
 while ($linha=mysql_fetch_array($res)){
 ?>
  <tr>
    <td><? print($linha["cod_orc_pre_nota"]);?></td>
    <td><? print($linha["data_abre"]);?></td>
    <td><? print($linha["nota"]);?></td>
    <td><? print($linha["data_nota"]);?></td>
    <td><? print($linha["valor_tot"]);?></td>
  </tr>
  <?
  }
  ?>
</table>
<?
}
?>
  <table width="900" border="1">
      <tr class="style1">
        <td width="56">Barcode</td>
        <td width="33">Filial</td>
	    <td width="47">Marca</td>
		<td width="68">Modelo</td>
		<td width="42">S&eacute;rie</td>
		<td width="442">Pe&ccedil;as</td>
		<td width="45">Valor</td>
		<td width="115">Forn.-Prod.</td>
    </tr>
	<td colspan="8"><div align="center" class="style2">Orçamento Coletivo nº <? print($orc);?> </div></td>
	
<?
$count = 0;
$totG = 0;
$totR = 0;
$sql="select cp.cod as cp,barcode,filial,modelo.marca as marca,modelo.descricao as modelo,serie,cod_produto_cliente
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner
join orc on orc.cod_cp = cp.cod
where orc.cod_orc_coletivo=$orc
group by barcode,filial,marca,modelo,serie,cod_produto_cliente,cp
order by cod_produto_cliente";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
	<td><? print($linha["barcode"]);?></td>
	<td><? print($linha["filial"]);?></td>
	<td><? print($linha["marca"]);?></td>
	<td><? print($linha["modelo"]);?></td>
	<td><? print($linha["serie"]);?></td>
	<td>
<?
$cp=$linha["cp"];
$peca="";
$vlT=0;
$vlR=0;
//Esta SQL busca somente os itens pertencntes ao orçamento informado, uma vez que podemos 
//gerar um orç em um dia e outro itm para o mesmo produto ser apontado em outro dia sendo 
//gerado outro numero de orçamento diferentes dos reprovados
$sql2="select peca.descricao as peca , DESTINO.DESCRICAO AS DESTINO,orc.valor as valor,orc.qt as qt, orc_decisao.aprova as aprova, orc_decisao.descricao as decisao
from peca inner
join orc on orc.cod_peca = peca.cod left
join orc_decisao on orc_decisao.cod = orc.cod_decisao
INNER JOIN DESTINO ON DESTINO.COD = ORC.COD_DESTINO
where orc.cod_cp = $cp
and cod_orc_coletivo= $orc";
$res2=mysql_db_query ("$bd",$sql2,$Link) or die (mysql_error());
$rowsp=mysql_num_rows($res2);
while ($linha2 = mysql_fetch_array($res2)){
	$msg="";
	$aprova=$linha2["aprova"];
	if($aprova==0 && $aprova<>NULL){
		$msg="<font color=red>$linha2[decisao]</font>";
		$vlR=$vlR+($linha2["valor"]*$linha2["qt"]);
		//print($linha2["valor"]*$linha2["qt"]);
	}
	print("<FONT COLOR='BLUE'>".$linha2["qt"]." ".$linha2["DESTINO"]." </FONT>".$linha2["peca"]." - R$ ".$linha2["valor"]." $msg<br>");
	$vlT=$vlT+($linha2["valor"]*$linha2["qt"]);
}
//print("$msg");
if ($rowsp==0){
	print("ERRO:Nenhum resultado encontrado!!!");
	$vlT=0;
}
?>
</td>
	<td><?	$vlTot=number_format($vlT, 2, ',', '.');
			 print("R$".$vlTot);?></td>
	<td><? print($linha["cod_produto_cliente"]);?></td>
</tr>
<?
		$count++;
		$totG=$totG+$vlT;
		$totR=$totR+$vlR;
		$totA=$totG-$totR;
}
?>
    <tr class="style3"><td colspan="5" class="Cabe&ccedil;alho"><div align="right"><span class="Cabe&ccedil;alho">Or&ccedil;amento  <? print(" ".$orc);?>TOTAL</span></div></td>
    <td><? print("$count");?> Produto(s) </td>
	<td colspan="2">
	<? $totGeral=number_format($totG, 2, ',', '.');
	 print("R$".$totGeral);?></td>
	</tr>
	<tr>
	<td colspan="8">
	<div align="center"><span class="style1">
	  <? $totGeralR=number_format($totR, 2, ',', '.');
	 print("Total Aprovado R$".$totA);?>
	  </span>
	  </div>
	<div align="center" class="style1"></div></td>	
	</td>
	  <div align="center" class="style1"></div>
	</tr>
		<tr>
	<td colspan="8">
	<div align="center"><span class="style1">
	  <? $totGeralR=number_format($totR, 2, ',', '.');
	 print("Total Reprovado R$".$totGeralR);?>
	  </span>
	  </div>
	<div align="center" class="style1"></div></td>	
	</td>
	  <div align="center" class="style1"></div>
	</tr>

  </table>
</div>
</body>
</html>
