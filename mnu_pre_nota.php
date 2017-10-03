<? // Analizar 22/09/07
require_once("sis_valida.php");
require_once("sis_conn.php");
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
-->
</style>
</head>
<body>
<div align="center" class="style1">
<form name="form2" method="get" action="con_pre_nota.php">
  <p align="center"><span class="Titulo2">Visualizar Pr&eacute;-Notas da cobran&ccedil;a de n&ordm;
      <input type="text" name="fechamento"> 
    </span>    
    <input type="submit" name="Submit" value="Visuallizar">
  </p>
</form>
<!--
--------------------COMENTADA EM 04/10/2007 EM VIRTUDE DE NOVAS ATUALIZAÇÕES NO SISTEMA QUE PERMITEM A EMISSÃO DE PRÉ-NOTAS A PARTIR DO NUMERO DE REGISTRO DE SAIDA--------
<form name="form2" method="get" action="con_pecas_registro.php">
  <p align="center"><span class="Titulo2">Visualizar Pe&ccedil;as na registro de sa&iacute;da n&ordm;
      <input type="text" name="registro"> 
    </span> 
    <input type="submit" name="Submit" value="Visuallizar">
  </p>
</form>
-->
  <p><a href="frm_pre_nota_individual.php" class="style1">Montar uma pr&eacute;-notas</a>
  <hr>
  <p class="style2">Pr&eacute;-Notas Aguardando N&ordm; da Nota Fiscal de Cobran&ccedil;a</p>
  <?
$sql="SELECT orc_pre_nota.cod AS cod, data_abre, fechamento
FROM orc
INNER JOIN orc_pre_nota ON orc_pre_nota.cod = orc.cod_orc_pre_nota
WHERE nota IS NULL 
GROUP BY cod
";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows<>0){
?>
  <table width="398" border="1">
  <tr>
    <td width="65">Pr&eacute;-Nota</td>
    <td width="241">Data/Pr&eacute;-Nota</td>
    <td width="70">Cobrança</td>
  </tr>
 <?
 while ($linha=mysql_fetch_array($res)){
 ?>
  <tr>
    <td><? print($linha["cod"]);?></td>
    <td><? print($linha["data_abre"]);?></td>
    <td><? print($linha["fechamento"]);?></td>
  </tr>
  <?
  }
  ?>
</table>
<?
}else{
?>
 Não há pré-notas pendentes! 
  
<?
}
?>
  
  
  <p class="style2">Or&ccedil;amentos coletivos aguardando Pr&eacute;-Nota </p>
  <p class="style2">
    <?
$sql="SELECT cod_orc_coletivo, month( data_cad ) AS mes
FROM orc
WHERE cod_decisao =0
AND cod_orc_coletivo IS NOT NULL 
GROUP BY cod_orc_coletivo
";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows<>0){
?>
</p>
  <table width="250" border="1">
    <tr>
      <td width="148">Orçamento Coletivo nº</td>
      <td width="86">Mês</td>
    </tr>
    <?
 while ($linha=mysql_fetch_array($res)){
 ?>
    <tr>
      <td><? print($linha["cod_orc_coletivo"]);?></td>
      <td><? print($linha["mes"]);?></td>
    </tr>
    <?
  }
  ?>
  </table>
  <?
}else{
?>
N&atilde;o h&aacute; or&ccedil;amentos coletivos pendentes!
<?
}

$sql="select cp.orc_cliente as orc_cliente,DATE_FORMAT(cp.data_orc, '%d/%m/%Y as %k:%i:%s') AS data_orc,cp.cod AS cp, barcode, datediff(now(),data_entra) as dd, DATE_FORMAT(orc.data_imprime_individual, '%d/%m/%Y as %k:%i:%s') AS data_imprime,orc.cod_orc_individual,orc_decisao.descricao as decisao, orc_decisao.cod as coddecisao
from cp inner 
join orc on orc.cod_cp = cp.cod inner
join modelo on modelo.cod = cp.cod_modelo left
join linha on linha.cod = modelo.linha left
join orc_decisao on orc.cod_decisao = orc_decisao.cod
where linha.orc_coletivo=0 and orc.cod_orc_pre_nota is null
group by barcode
order by dd desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$rows=mysql_num_rows($res);
if($rows==0){
	print("Não há orçamentos individuais aguardando Pré-Nota!");
}else{
?>
	<p class="style2">Or&ccedil;amentos individuais aguardando Pr&eacute;-Nota</p>
	 <h4>Imprima or&ccedil;amentos coletivos quizenalmente. Isto vai eliminar a possibilidade de duplicidade.<br>
	   Observe a data de entrada dos produtos e siga este crit&eacute;rio.
     </h4>
	  <table width="702" border="1">
	    <tr>
	      <td width="85"><strong>Barcode</strong></td>
		  <td width="150"><strong>Controle de Impress&atilde;o </strong></td>
	      <td width="48"><strong>Dias Parado </strong></td>
		  <td width="177"><strong>Status</strong></td>
	    </tr>
	<?	while ($linha = mysql_fetch_array($res)){
			$barcode=$linha["barcode"];
			$orc=$linha["cod_orc_individual"];
			$imprime=$linha["data_imprime"];
			$dd=$linha["dd"];
			$cp=$linha["cp"];
			$codDecisao=$linha["coddecisao"];
			$orc_cliente=$linha["orc_cliente"];
			$dt_orc=$linha["data_orc"];
			if($codDecisao<=1){
				$status="Aguardando Definição";
			}else{
				$status=$linha["decisao"];
			}?>
<tr>
<? 
if($orc<>NULL){?>
	<td><? print("<a href=con_orc_individual.php?txtBarcode=$barcode target='_blank'>$barcode</a>")?></td>
	<td> Impresso em <? print($imprime);?> <br>Orçamento Individual nº <? print($orc);?></td><? 
}else{
	if($orc_cliente==NULL){?>
		<td><? print($barcode);?></td>
		<td><a href=scr_orc_individual.php?cp=<? print($cp);?>&barcode=<? print($barcode);?> target="_blank" onClick="javascript:history.go(0)"> <input name="" type="image" src="img/print.png" alt="IMPRIMIR">Aguardando impressão!!!</a></td><?
	}else{?>
		<td><? print("<a href=con_orc_individual.php?txtBarcode=$barcode target='_blank'>$barcode</a>");?></td>
		<td> Digitado em <? print($dt_orc);?> <br>Orçamento no Cliente nº <? print($orc_cliente);?></td><? 
	}		
}
?><td width="29"><? print($dd);?></td>
<td width="173"><? print($status);?></td>
</tr>
<?
		}?>
			    <tr>
		      <td colspan="3">Total <? print($rows);?></td>
		    </tr>
  </table>
<?
}
?>
</div>
</body>
</html>
