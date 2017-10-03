<? // Analizar 22/09/07
require_once("sis_valida.php");
require_once("sis_conn.php");
// tentando construir um modelo de processo para confecçõa de NF em 23/09/07 -- Ainda não sei se vai atender a todas as necessidades...
if (isset($_GET["fechamento"])){ 
	$fechamento = $_GET["fechamento"];
}else{
	$res=mysql_query("select fechamento from orc where fechamento is not null and cod_orc_pre_nota is null") or die(mysql_error());
	$row=mysql_num_rows($res);
	if($row==0){
		die("<br><br><br><br><h2>Nenhuma cobrança está aberta! Se deseja iniciar uma pre-nota clique <a href='scr_abre_cobranca.php'>aqui!</a>");
	}else{
		$fechamento=mysql_result($res,0,"fechamento");
	}
}
if(isset($_GET["msg"])){
	$msg=$_GET["msg"];
}else{
	$msg="";
}
?>
<html>
<head>
<title>Montando uma pré-notas individual</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="Titulo2"><span class="Titulo1">Montando pr&eacute;-notas para a cobrança n&uacute;mero <? print($fechamento);?></span><br> 
<?
if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font>");}
?>
</p>
<form name="form1" method="get" action="scr_pre_nota_individual.php">
  <table width="756" border="1" align="center">
    <tr>
      <td width="278"><span class="style4">Registro de Saídas:</span></td>
      <td width="468">
        <input name="txtRegistro" type="text" class="caixaPR1" id="txtRegistro" maxlength="20">
      </td>
    </tr>
  <tr>
      <td width="278"><span class="style4">Num.Or&ccedil;. no cliente:</span></td>
      <td width="468">
        <input name="txtOrcCliente" type="text" class="caixaPR1" id="txtOrcCliente" maxlength="20">
      </td>
    </tr>
    <tr>
      <td width="278"><span class="style4">C&oacute;digo de Barras:</span></td>
      <td width="468">
        <input name="txtBarcode" type="text" class="caixaPR1" id="txtBarcode" maxlength="20">
      </td>
    </tr>
  </table>
  <div align="center">
    <input name="cmdEnviar" type="submit" class="Titulo2" id="cmdEnviar3" value="Incluir" >
	ou 
	<input name="cmdEnviar" type="submit" class="Titulo2" id="cmdEnviar3" value="Excluir" >
    </p>
<input type="hidden" name="fechamento" value="<? print($fechamento);?>">    </div>
</form>
<?
print("<h2>".$msg."</h2>");
?>
<table width="836" border="1" align="center">
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
	$sql2="SELECT peca.cod AS cod, peca.descricao AS peca, orc.valor AS venda, sum( orc.qt ) AS qt
	FROM orc
	INNER JOIN peca ON peca.cod = orc.cod_peca
	WHERE orc.fechamento = $fechamento
	GROUP BY orc.valor, peca.cod, peca.descricao
	ORDER BY cod";

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
		//$totG=$totG+$tot1;
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
	      <td colspan="2">&nbsp;</td>
	      <td>&nbsp;</td>
	      <td>Total</td>
  	      <td colspan="2"><div align="right"><strong>
          <? $totO="R$ ".number_format($totO, 2, ',', '.');print($totO);?>
          </strong></div></td>
        </tr>
</table>

<?
$tot=0;
$cont=0;
$sql="select barcode, orc_cliente,cp.cod as cp, fechamento_reg.registro as registro
from cp inner join orc on orc.cod_cp = cp.cod 
left join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
where orc.fechamento = $fechamento group by barcode";
$res=mysql_query($sql);
?>
	<table border="1" align="center" bgcolor="#FFFFFF">
	<tr>
	<td width="125"><strong>Barcode</strong></td>
	<td width="125"><strong>Registro</strong></td>
	<td width="107"><strong>Orçamento</strong></td>
	<td width="102"><strong>Valor</strong></td>
	</tr>
	<? while ($linha = mysql_fetch_array($res)){
	$cont++;
			$cp=$linha["cp"];
			$resValor=mysql_query("select valor,qt from orc where cod_cp = $cp");
			$vl=0;
			while ($linhaValor = mysql_fetch_array($resValor)){
				$vl+=$linhaValor["valor"] * $linhaValor["qt"];
			}
	$tot+=$vl;
	?>
	<tr>
	<td><? print($linha["barcode"]);?></td>
	<td><? print($linha["registro"]);?></td>
	<td><? print($linha["orc_cliente"]);?></td>
	<td>R$ <? $vl2=number_format($vl, 2, ',', '.');
	print($vl2);?></td>
	</tr>
	<? }?>
	<tr>
	<td colspan="3"><strong>Total</strong></td>
	<td><strong>R$
      <? $vl3=number_format($tot, 2, ',', '.');
	 print($vl3);?>
	</strong></td>
	</tr>
	
	</table>
	<?
$resItm=mysql_query("select max_item_nota from base");// Número máximo de linhas suportado pelo corpo da nota
$linhas=mysql_result($resItm,0,"max_item_nota");
print(" O numero máximo de itens por nota é de $linhas - ");
$tnf=$itm/$linhas;
$tnf2=ceil($tnf);
print(" Serão necessárias $tnf2 nota(s) para esta cobrança!<br>");

if ($itm>0){
?>

<form name="form1" method="get" action="scr_pre_nota.php">
  <div align="center">
    <input name="cmdEnviar2" type="submit" class="Titulo2" id="cmdEnviar" value="Gerar Pr&eacute;-Notas" >
  
    <input type="hidden" name="fechamento" value="<? print($fechamento);?>">
  </div>
</form>
<?
}
?>
</body>
</html>
