<? // Analizar 22/09/07
require_once("sis_valida.php");
require_once("sis_conn.php");
//$orc_coletivo=$_GET["coletivo"];
//Agora é usado o campo finalização....(27/08/2006)... Domingão... Aí meus Deus... Tomara que dê certo!!!
$fechamento=$_GET["fechamento"];
if($fechamento==""){die("Numero de Cobrança não preenchido");}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
.style3 {
	color: #FF0000;
	font-size: 18px;
}
-->
</style>
</head>
<body>
<form action="scr_gravar_nota.php" method="get" name="Form1">

<div align="center" class="style2">
<?
// Busca por todos os numeros de prénotas no banco cujo orc_coletivo seja igual ao fornecido
$sql="select cod_orc_pre_nota,data_abre,nota
from orc inner 
join orc_pre_nota on orc_pre_nota.cod = orc.cod_orc_pre_nota 
where cod_orc_pre_nota is not null and fechamento = $fechamento
group by orc.cod_orc_pre_nota";

$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$totG=0;
$i=0;//index das caixas de NF
while ($linha = mysql_fetch_array($res)){
	$nota=$linha["nota"];
	$pre=$linha["cod_orc_pre_nota"];
	$abre=$linha["data_abre"];
?>
  <p>Cobran&ccedil;a n&ordm; <? print($fechamento);?></p>
	<table width="836" border="1">
		<tr>
		  <td colspan="6"> <div align="center"><strong><a href="pdf_nfvenda.php?prenota=<? print($pre);?>" target="_blank">Pr&eacute;-Nota N&ordm;<? print($pre);?> Data: <? print($abre);?></a></strong></div></td>
	    </tr>
		<tr>
	      <td width="28"><div align="center"><strong>Itm</strong></div></td>
	      <td width="68"><div align="center"><strong>cod</strong></div></td>
	      <td width="474"><div align="center"><strong>Descri&ccedil;&atilde;o</strong></div></td>
	      <td width="49"><div align="center"><strong>Qtdade</strong></div></td>
		  <td width="91"><div align="center"><strong>Valor Unit</strong></div></td>
		  <td width="86"><div align="center"><strong>Valor Total</strong></div></td>
	    </tr>
	<?
	//para ver todas as peças do Orçamento coletivo execute a query abaixo.
	$sql2="SELECT peca.cod as cod, peca.descricao AS peca, orc.valor as venda, sum( orc.qt ) AS qt
	FROM orc
	INNER JOIN peca ON peca.cod = orc.cod_peca
	WHERE orc.cod_orc_pre_nota = $pre
	GROUP BY peca.cod,peca.descricao, orc.valor
	order by cod";

	$res2=mysql_db_query ("$bd",$sql2,$Link) or die (mysql_error());
	$itm=0;
	$totO=0;
	while ($linha2 = mysql_fetch_array($res2)){
		$itm++;
		$codigo=$linha2["cod"];
		$peca=$linha2["peca"];
		$qt=$linha2["qt"];
		$pv=$linha2["venda"];
		$venda="R$ ".number_format($pv, 2, ',', '.');
		$tot1=$qt*$pv;
		$tot="R$ ".number_format($tot1, 2, ',', '.');
		$totO=$totO+$tot1;
		$totG=$totG+$tot1;
		?>
	    <tr>
	      <td><? print($itm);?></td>
	      <td><? print($codigo);?></td>
	      <td><? print($peca);?></td>
	      <td><? print($qt);?></td>
		  <td><? print($venda);?></td>
		  <td><? print($tot);?></td>
	    </tr>
     <?
	}?>
	    <tr>
	      <td colspan="2"><strong>Nota Fiscal N&ordm;</strong></td>
	      <td><input type="text" name="<? print($pre);?>" <? if($nota<>""){print("value='$nota' readonly='1'");}?> >
          Data da Nota_______/_______/_______ 
          <input type="hidden" name="fechamento" value="<? print($fechamento);?>"></td>
	      <td>Total</td>
  	      <td colspan="2"><div align="right"><strong>
          <? $totO="R$ ".number_format($totO, 2, ',', '.');print($totO);?>
          </strong></div></td>
        </tr>
  </table>
<?
}?>
	
<input name="btn" type="submit" value="Gravar Notas / Visualizar RATEIO">
</form>

Total em pré-notas para a cobran&ccedil;a nº<? print($fechamento);?> é de <? $totG="R$ ".number_format($totG, 2, ',', '.');print($totG);?></div>

<div align="center" class="style3">Total em Orçamentos reprovados neste fechamneto <? $sql="SELECT sum( valor * qt ) AS totR
FROM orc
INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE orc.fechamento = $fechamento 
AND orc_decisao.aprova =0 "; 
$res=mysql_query($sql);
$totR=mysql_result($res,0,"totR");
$totR="R$ ".number_format($totR, 2, ',', '.');print($totR);
$sql="select cod_orc_coletivo 
from orc 
where fechamento = $fechamento
and cod_orc_coletivo is not null 
group by cod_orc_coletivo 
order by cod_orc_coletivo";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows==0){
	print("<br>Não há orçamentos coletivos inclusos nesta Cobrança<br>");
}else{
	?></div>
	<p><? print($rows);?> Orçamentos coletivos inclusos nesta cobran&ccedil;a:</p>
	<p><?
	while($linha=mysql_fetch_array($res)){
		print($linha["cod_orc_coletivo"]." &nbsp;");
	}
}
$sql="SELECT barcode, fechamento_reg.registro as registro
FROM orc
INNER JOIN cp ON cp.cod = orc.cod_cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
INNER JOIN linha ON linha.cod = modelo.linha
left join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
WHERE fechamento = $fechamento 
AND linha.orc_coletivo =0
GROUP BY barcode
ORDER BY barcode";
$res=mysql_query($sql) or die(mysql_error()."<br>$sql");
$rows=mysql_num_rows($res);
if($rows==0){
	print("<br>Não há orçamentos individuais inclusos nesta Cobrança<br>");
}else{
	?>
    </p>
	<p> <? print($rows);?> Orçamentos Individuais inclusos nesta cobran&ccedil;a </p>
	<table border="1">
			<tr>
			<td><strong>Registro</strong></td>
			<td><strong>Barcode</strong></td>
			</tr>
	<?
	$i=0;
	while($linha=mysql_fetch_array($res)){
		//$i++;
		//print($linha["barcode"]."&nbsp; ,");
		//if($i==7){
		//	$i=0;
		//	print("<br>");
		//}
		?>
		<tr>
			<td>  <? print($linha["registro"]);?></td>
			<td><? print($linha["barcode"]);?> </td>
		</tr>
		
<?
	}
	?></table>
	<?
}
?>
</body>
</html>
