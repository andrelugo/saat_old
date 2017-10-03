<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$fechamento=$_GET["fechamento"];
if($fechamento==""){die("Numero da cobrança não informado!");}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
.style2 {color: #660000}
.style3 {color: #000000}
-->
</style>
</head>
<body>
<div align="center" class="style1">
Rateio de Lojas C.B.D. </div>
<div align="center"><br>
<?
$sql="SELECT cod_orc_pre_nota, bandeira_cbd.descricao AS bandeira,bandeira_cbd.cod as cod_bandeira
FROM orc INNER 
JOIN cp ON cp.cod = orc.cod_cp INNER 
JOIN filial_cbd ON filial_cbd.descricao = cp.filial INNER 
JOIN bandeira_cbd ON bandeira_cbd.cod = filial_cbd.cod_bandeira
WHERE fechamento = $fechamento
GROUP BY cod_orc_pre_nota, bandeira, cod_bandeira
ORDER BY cod_orc_pre_nota
";
$sql="SELECT cod_orc_pre_nota, nota
FROM orc inner join orc_pre_nota on orc_pre_nota.cod = orc.cod_orc_pre_nota
WHERE fechamento = $fechamento
GROUP BY cod_orc_pre_nota
ORDER BY cod_orc_pre_nota";

$res1=mysql_query($sql);
$rows=mysql_num_rows($res1);
$vTotG=0;
if($rows==0){die("Nenhum resultado encontrado  para a cobrança $fechamento !");}
while ($linha=mysql_fetch_array($res1)){
	$pre=$linha["cod_orc_pre_nota"];
	$nota=$linha["nota"];
	//$codBandeira=$linha["cod_bandeira"];
?>
	Rateio para a Nota nº<? if($nota==NULL){print(" 00000");}else{print($nota);}?> Pré-Nota nº<? print($pre);?>  <? // print("Bandeira ".$linha["bandeira"]);?>
	<table width="244" border="1" align="center">
    	<tr>
      		<td width="116">Filial</td>
	    	<td width="112">Valor</td>
    	</tr>
	<? $sql="SELECT filial, sum( orc.valor * orc.qt ) AS tot
	FROM cp	INNER 
	JOIN orc ON orc.cod_cp = cp.cod inner
	join filial_cbd on filial_cbd.descricao = cp.filial
	WHERE cod_orc_pre_nota = $pre and filial_cbd.cod_bandeira = codBandeira
	GROUP BY filial
	ORDER BY filial";
	
	$sql="SELECT filial, sum( orc.valor * orc.qt ) AS tot
	FROM cp	INNER 
	JOIN orc ON orc.cod_cp = cp.cod inner
	join filial_cbd on filial_cbd.descricao = cp.filial
	WHERE cod_orc_pre_nota = $pre
	GROUP BY filial
	ORDER BY filial desc";
	$res2=mysql_query($sql);
	$vlTot=0;
	while ($linha2=mysql_fetch_array($res2)){
?>		 <tr>
			<td><? print($linha2["filial"]);?></td>
		    <td><? print("R$ ".number_format($linha2["tot"], 2, ',', '.'));?></td>
		</tr>
<? 	
		$vlTot=$vlTot+$linha2["tot"];
	}?>
	<tr>
		<td><strong>Total</strong></td>
		<td><? //print($vlTot);
			   print("R$ ".number_format($vlTot, 2, ',', '.'));?></td>
	</tr>
	</table>
<?
$vTotG=$vTotG+$vlTot;
}
print("Total em orçamento para a cobrança nº $fechamento = R$ $vTotG");
?>
</div>
</body>
</html>
