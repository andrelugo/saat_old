<? if (isset($_GET["order"])){$order=$_GET["order"];}else{$order="";}
	if (isset($_GET["where"])){
		$w=$_GET["where"];
		$where="where peca.cod_fabrica like '%$w%' or peca.descricao like '%$w%'";
	}else{
		$where="where peca.descricao='A'";
		$w="";
	}
?>
<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
	color: #0000FF;
}
.style2 {color: #0000FF}
.style3 {
	color: #000000;
	font-weight: bold;
}
body {
	background-image: url(img/fundoadm.gif);
}
-->
</style>
</head>
<body>
<form name="form1" method="get" action="con_peca.php">
Descrição/C&oacute;digo
<input name="where" type="text" value="<? print("$w");?>">
<input type="submit" name="Submit" value="Submit">
</form>
<table width="800" border="1">
  <tr>
   <td width="70" class="style2"><span class="style3"><a href="con_peca.php?order=order by peca.cod&where=<? print($w);?>"><strong>Código SAAT</span></a></td>
   <td width="70" class="style2"><span class="style3"><a href="con_peca.php?order=order by cod_fabrica&where=<? print($w);?>"><strong>Código Fabrica </span></a></td>
    <td width="389"><a href="con_peca.php?order=order by descricao&where=<? print($w);?>">Descricao</a></td>
    <td width="71"><a href="con_peca.php?order=order by cod_fornecedor&where=<? print($w);?>">Fornecedor</a></td>
    <td width="44">P. Venda </td>
    <td width="26"><strong><a href="con_peca.php?order=order by qt&where=<? print($w);?>">Est.</a></strong></td>
    <td width="30"><strong>Est. Min </strong></td>
    <td width="100"><strong><a href="con_peca.php?order=order by orcamento&where=<? print($w);?>">Tipo</a></strong></td>
	<td width="18"><a href="con_peca.php?order=order by retornavel&where=<? print($w);?>">R</a></td>
  </tr>
<?
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="select peca.cod as cod,cod_fabrica,peca.descricao,fornecedor.descricao as fornecedor,venda,qt,qtmin,
orcamento,cortesia,garantia,retornavel
from peca 
LEFT join fornecedor on fornecedor.cod = peca.cod_fornecedor 
$where
$order";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de peças".mysql_error()."<br>$sql");
while ($linha = mysql_fetch_array($res)){
	$ret=$linha["retornavel"];
	if ($ret==1){$r="Sim";$cor="#FF0000";}else{$r="Não";$cor="#FFFFFF";}
	$orc=$linha["orcamento"];
	$cort=$linha["cortesia"];
	$gar=$linha["garantia"];
	if ($orc==1){$orca="Orcamento";}else{$orca="";}
	if ($cort==1){$corte="Cortesia";}else{$corte="";}
	if ($gar==1){$gara="Garantia";}else{$gara="";}
		print ("<tr onMouseOver=$jvA onMouseOut=$jvB>
		<td>$linha[cod]</td>
		 <td>$linha[cod_fabrica]</td>
		<td><a href='frm_peca.php?cod=$linha[cod]'>$linha[descricao]</a></td>
		<td>$linha[fornecedor]</td>
		<td>$linha[venda]</td>
		<td>$linha[qt]</td>
		<td>$linha[qtmin]</td>
		<td>$orca $corte $gara</td>
		<td bgcolor='$cor'>$r</td>
		<tr>");
}	
?>
</table>
</body>
</html>
