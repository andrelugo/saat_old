<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style4 {
	font-size: 14px;
	font-style: italic;
}
.style5 {font-size: 9px}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>
<body>
<p align="center"><span class="style1">Produtos Pendentes de Conserto </span></p>
<p align="center"><span class="style1"></span><span class="style4">Ordenados pela data do c&oacute;digo de barras! </span></p>
<div align="center">
  <table width="806" border="1">
    <tr>
      <td width="61"><div align="center">Modelo</div></td>
      <td width="77"><div align="center">Técnico</div></td>
      <td width="41"><div align="center" class="style5">Dias parado </div></td>
      <td width="303"><div align="center">Garantia</div></td>
      <td width="290"><div align="center">Orçamento</div></td>
    </tr>
<?
$count = 0;
$sql="SELECT DATEDIFF(now(),data_barcode) AS dd,modelo.descricao as descricao,cp.cod, rh_user.nome as tec
FROM cp inner join
modelo on modelo.cod = cp.cod_modelo inner join
rh_user on rh_user.cod = cp.cod_tec
where data_pronto is null and data_analize is not null
order by dd desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à produtos parados na area técnica".mysql_error());
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
while ($linha = mysql_fetch_array($res)){
		$jv="con_cp.php?cp=$linha[cod]&msg=Alteração de ";
		if ($linha["dd"]>25){$dias="<font color='red' size=5>$linha[dd]</font>";}else{$dias=$linha["dd"];}
		print ("<tr onMouseOver=$jvA onMouseOut=$jvB><td><a href=$jv> $linha[descricao] </td><td> $linha[tec] </td><td>$dias</td>");
		$count++;
		
		$sql="select peca.descricao as pc from pedido inner join peca on peca.cod = pedido.cod_peca where pedido.cod_cp = $linha[cod]";
		$res2=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta às peças".mysql_error());
		$peca="";
		while ($linha2 = mysql_fetch_array($res2)){
			$peca = "$peca.$linha2[pc]<br>";
		}
			print ("<td>$peca</td>");
		$sqlo="select peca.descricao as pc from orc inner join peca on peca.cod = orc.cod_peca where orc.cod_cp = $linha[cod]";
		$res3=mysql_db_query ("$bd",$sqlo,$Link) or die ("Erro na string SQL de consulta às peças orçamento".mysql_error());
		$pecao="";
		while ($linha3 = mysql_fetch_array($res3)){
			$pecao = "$pecao.$linha3[pc]<br>";
		}
			print ("<td>$pecao</td></a>");
}
?>
    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="3"><strong><?print("$count");?></strong></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
</html>
