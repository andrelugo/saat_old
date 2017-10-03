<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$codf=$_GET["codf"];

if (empty($_GET["order"])){$order="order by cp.cod";}else{$order=$_GET["order"];}
if (empty($_GET["where"])){$where="cp.cod_fechamento_reg=$codf";}else{$where=$_GET["where"];}
$res2=mysql_query("select descricao,registro from fechamento_reg where cod=$codf");
$desc=mysql_result($res2,0,"descricao");
$descreg=mysql_result($res2,0,"registro");
?>
<html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 12px}
.style3 {font-size: 14px}
-->
</style>
</head>
<body topmargin="0" onLoad="document.form1.cmdOk.focus();">
<form name="form1" method="post" action="scr_reg_dig.php">
<p align="center" class="Titulo2"> <? print("Fechamento: ".$desc." <br>Registro de saídas: ".$descreg);?></p>
<?
 if (isset($_GET["erro"])){print("<br><h1><font color=red><center>".$_GET["erro"]."</font></h1></center>");}
	$sql="select modelo.descricao as modelo,cp.serie as serie,cp.folha_cq as folha,
	destino.descricao as destino,cp.cod_destino as codestino,cp.barcode as barcode, cp.cod as cod, cp.itm_fechamento_reg as itm,
	defeito.descricao as defeito, solucao.descricao as solucao, modelo.marca as marca, modelo.descricao as modelo,rh_user.nome as tecnico
	from cp inner join
	modelo on modelo.cod = cp.cod_modelo inner join
	destino on destino.cod = cp.cod_destino inner join
	defeito on defeito.cod = cp.cod_defeito inner join
	solucao on solucao.cod = cp.cod_solucao inner join
	rh_user on rh_user.cod = cp.cod_tec
	where $where 
	$order";
	$res=mysql_query($sql) or die($sql." <br>".mysql_error());
	$cont=0;
	while ($linha=mysql_fetch_array($res)){
	$barcode=$linha["barcode"];
	$itm=$linha["itm"];	
	$cod=$linha["cod"];
	$cont++;
	if ($itm<>""){$cor='#FF3366';}else{$cor='';}
	if ($cont==1){
?>
  <table width="886" border="5" align="center">
    <tr class="style2">
      <td width="434"><div align="center"><span class="style6">Destino</span></div></td>
      <td width="428"><div align="center"><span class="style6">Barcode</span></div></td>
    </tr>
    <tr bgcolor="<? print ($cor);?>">
      <td>
      <div align="center"><span class="style4">
        <select name="cmbDestino" class="caixaAZ1" id="select6"  tabindex="5" >
          <option value="0"></option>
          <?
$codDestino=$linha["codestino"];
$sql="select * from destino";
$res2=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Destino");
while ($linha1 = mysql_fetch_array($res2)){
	if (isset($codDestino)){
		if ($codDestino==$linha1[cod]){
		print ("<option value= $linha1[cod] selected> $linha1[descricao] </option>");
		}else{
		print ("<option value= $linha1[cod] > $linha1[descricao] </option>");
		}
	}else{
		print ("<option value= $linha1[cod] > $linha1[descricao] </option>");
	}
}
?>
        </select>
      </span></div></td>
      <td class="caixaAZ1"><div align="center"><strong><? print($linha["barcode"]);?></strong></div></td>
    </tr>
	<tr><td><div align="center">Série:</div></td><td><div align="center" class="caixaAZ2"><strong><? print($linha["serie"]);?></strong></div></td>
	</tr>
<tr bgcolor="#FFFFCC">
<td><div align="center">Defeito:</div></td>
<td><div align="center">Solução:</div></td>
</tr>
<tr>
<td class="caixaAZ1"><div align="center" class="caixaAZ2"><strong><? print($linha["defeito"]);?></strong></div></td>
<td class="caixaAZ1"><div align="center" class="caixaAZ2"><strong><? print($linha["solucao"]);?></strong></div></td>
</tr>
<tr bgcolor="#FFFFCC">
<td><div align="center">Peças ORÇAMENTO:</div></td>
<td><div align="center">Peças Garantia:</div></td>
</tr>
<tr class="Cabe&ccedil;alho">
<td>    <span class="style3">
<?
$sqlP="select descricao as peca, venda from orc inner join peca on peca.cod = orc.cod_peca where orc.cod_cp = $cod";
$res2=mysql_db_query ("$bd",$sqlP,$Link) or die (mysql_error());
$tot=0;
while ($linha2 = mysql_fetch_array($res2)){
	print("$linha2[peca]    R$ ".number_format($linha2["venda"], 2, ',', '.')."<br>");
	$tot=$tot+$linha2["venda"];
}
print("<br><font color='blue'><div align='right'>Total R$ ".number_format($tot, 2, ',', '.')."</div></font>");

?>
    </span>
    <div align="left" class="style3"></div>
</td>
  <td>    <span class="style3">
    <?
$sqlP="select descricao as peca from pedido inner join peca on peca.cod = pedido.cod_peca where pedido.cod_cp = $cod";
$res2=mysql_db_query ("$bd",$sqlP,$Link) or die (mysql_error());
while ($linha2 = mysql_fetch_array($res2)){
	print($linha2["peca"]."<br>");
}
?>
    </span>
    <div align="left" class="style3"></div></td><tr>
	  <td colspan="2"><div align="left"><? print($linha["marca"]." - ".$linha["modelo"]);?></div>
	  <div align="center">
	    <input type="hidden" name="cod" value="<? print($cod);?>">
	    <input type="hidden" name="codf" value="<? print($codf);?>">
	    <input type="hidden" name="order" value="<? print($order);?>">
	    <input type="submit" value="OK" id="cmdOk" name="cmdOk">
	    </div>
		<div align="right"><? print($linha["tecnico"]);?></div>
		</td>
    </tr>
  </table>
</form>
<br>
<table width="832" border="1" align="center">
	  <tr>
	    <td width="79"><a href="frm_reg_dig.php?codf=<? print ($codf);?>&order=order by modelo">Modelo</a></td>
	    <td width="102"><a href="frm_reg_dig.php?codf=<? print ($codf);?>&order=order by serie">S&eacute;rie</a></td>
	    <td width="48"><a href="frm_reg_dig.php?codf=<? print ($codf);?>&order=order by folha">Folha</a></td>
	    <td width="93"><a href="frm_reg_dig.php?codf=<? print ($codf);?>&order=order by destino">Destino</a></td>
	    <td width="130"><a href="frm_reg_dig.php?codf=<? print ($codf);?>&order=order by barcode">BARCODE</a></td>
		<td width="27">Itm</td>
		<td width="307"></td>
	  </tr>
	<?
		}else{
	?>
	  <tr bgcolor="<? print ($cor);?>">
    	<td><? print($linha["modelo"]);?></td>
    	<td><? print($linha["serie"]);?></td>
	    <td><? print($linha["folha"]);?></td>
		<td><? print($linha["destino"]);?></td>
	    <td><? print ("<a href='frm_reg_dig.php?codf=$codf&where=cp.cod=$cod'>$barcode</a>");?></td>
		<td><? print($itm);?></td>
		<td><? print ("<a href='scr_exclui_reg.php?codf=$codf&cod=$cod&order=$order'>Exculir</a>");?></td>
	  </tr>
	<?
		}
	}
	?></font>
</table>
</body>
</html>