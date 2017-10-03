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

$sql="SELECT peca.cod_fabrica AS cod, peca.descricao AS descr, sum( orc.qt ) AS solic,peca.custo as custo,peca.qt as qt
FROM peca INNER JOIN orc ON orc.cod_peca = peca.cod
inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
WHERE data_decisao BETWEEN ('$dtini')AND('$dtfim')
and orc_decisao.aprova=1
and cod_orc_pedido is null
GROUP BY cod, descr
$order";

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
.style2 {color: #0000FF}
-->
</style></head>
<body>
<p align="center"><span class="style1">Selecione um per&iacute;odo para pesquisar no Banco de Dados quais as pe&ccedil;as foram or&ccedil;adas pela equipe t&eacute;cnica e aprovadas pelo cliente <br>
  </span></p>
<form name="form1" method="get" action="con_pedidoorc.php">
  <div align="center">
    <p>
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

    </p>
  </div>
</form>
<table width="960" border="1" align="center">
  <tr>
   <td width="62" class="style2"><a href="con_pedidoorc.php?order=order by cod&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>">C&oacute;d. Pe&ccedil;a </span></a></td>
   <td width="397"><a href="con_pedidoorc.php?order=order by descr&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>">Descricao</a></td>
    <td width="181"><strong><a href="con_pedidoorc.php?order=order by solic&<? print("txtDiaIni=$DiaIni&txtMesIni=$MesIni&txtAnoIni=$AnoIni&txtDiaFim=$DiaFim&txtMesFim=$MesFim&txtAnoFim=$AnoFim")?>">Qt Aprovada</strong></a></td>
	<td width="105">Preço Unitário</td>
	<td width="107">Total</td>
	<td width="68">Qtdade em estoque</td>
  </tr>
<?
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
$totg=0;
while ($linha = mysql_fetch_array($res)){
?>
<tr>
	<td><? print ($linha["cod"]);?></td>
	<td><? print ($linha["descr"]);?></td>
	<td><? print ($linha["solic"]);?></td>
	<td><? print ("R$ ".number_format($linha["custo"], 2, ',', '.'));?></td>
	<td><? $tot=$linha["custo"]*$linha["solic"];print ("R$ ".number_format($tot, 2, ',', '.'));?></td>
	<td><? print ($linha["qt"]);?></td>
</tr>
<?
$totg=$totg+$tot;
}	
?>
<tr>
	<td colspan="4">Total</td>
	<td colspan="2"><? print ("R$ ".number_format($totg, 2, ',', '.'));?></td>
</tr>
<tr>
	<td colspan="4"><div align="center" class="style1">Total da compra pr&eacute;-aprovada com desconto de 35%</div></td>
	<td colspan="2"><div align="center"><span class="style1">

    <span class="style2"><? $totgd=$totg*0.65;print ("R$ ".number_format($totgd, 2, ',', '.'));?></span></span></div></td>
</tr>
</table>
<form action="scr_pedidoorc.php" method="get">
  <div align="center">
    <p>Cadastrar n&uacute;mero do pedido
      <input name="txtPedido" type="text">
      <br>
      Valor: 
      <input name="txtValor" type="text">
      <br>
      <input type="submit" name="Submit2" value="Cadastrar">
      <input type="hidden" name="dtIni" value="<? print($dtini);?>">
      <input type="hidden" name="dtFim" value="<? print($dtfim);?>">
</p>
  </div>
</form>
<p><span class="style1">Este relat&oacute;rio deve ser impresso e nele registrado seu respectivo n&uacute;mero do pedido de compra no fornecedor.</span><br>
  Cada item deve ser cautelosamente avaliado antes da compra:<br>
  A) Deve se verificar se n&atilde;o h&aacute; disponibilidade no estoque da matriz e no local<br>
  B) Caso n&atilde;o possua o item em estoque deve ser verificado no ato da compra se o pre&ccedil;o de custo permanece igual ou inferior ao cadastrado no sistema.<br>
  C) Caso o pre&ccedil;o no fornecedor esteja maior que o cadastrado no sistema deve ser imediatamente comunicado a diretoria e ger&ecirc;ncia de estoque. E em seguida atualizado no SAAT. <br>
  D) Assim que o pedido for cadastrado um e-mail deve ser enviado para a diretoria contendo o n&uacute;mero do pedido no fornecedor, o per&iacute;odo de aprova&ccedil;&atilde;o (data inicial e data final), e os valores<br>
  do pedido aprovado no sistema e efetivamente pago no pedido do fornecedor. </p>
<p>&nbsp;</p>
</body>
</html>
