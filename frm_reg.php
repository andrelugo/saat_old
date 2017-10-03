<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["order"])){$order=$_GET["order"];}else{$order=" order by cod";}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundoadm.gif);
}
-->
</style></head>
<? 
if (isset ($_GET["foco"])){
	$foco = $_GET["foco"];
	if ($foco=="folha"){
?>
		<body onLoad="document.form1.txtFolha.focus();">
<?	}else{
?>
		<body onLoad="document.form1.txtBarcode.focus();">
<? 	}
}else{
?>
	<body>
<? 
}
?>
<? if (empty($_GET["codf"])){?>
		<center><h2>Escolha um Registro<br>
		  Para incluir ou excluir produtos </h2>
		</center>
		<table width="832" border="1">
	  <tr>
	    <td width="347">Fechamento</td>
	    <td width="64">Data</td>
	    <td width="88">Registro</td>
	    <td width="94">Tipo</td>
	    <td width="71">Qt Cadast.</td>
	    <td width="75">Qt Digitada </td>
	    <td width="47">C&oacute;digo</td>
	  </tr>
	<?
	$sql="select * from fechamento_reg where data_fecha is null order by cod";
	$res=mysql_query($sql);
	while ($linha=mysql_fetch_array($res)){
	
	$cod=$linha["cod"];
	$res2=mysql_query("select count(cod) as tot from cp where cod_fechamento_reg=$cod");
	$tot=mysql_result($res2,0,"tot");
	?>
	  <tr>
    	<td><? print($linha["descricao"]);?></td>
    	<td><? print($linha["data_abre"]);?></td>
	    <td><? print($linha["registro"]);?></td>
	    <td><? if (isset($linha["tipo"])){print($linha["tipo"]);}else{print("Todos");}?></td>
	    <td><? print($linha["qt_os"]);?></td>
		<td><? print($tot);?></td>
	    <td><a href="frm_reg.php?codf=<? print($linha["cod"]);?>"><? print($linha["cod"]);?></a></td>
	  </tr>
<?
}
?>
	</table>
<?
}else{

////////////////////////////Página 2///////////////////////////////////////////
$codf=$_GET["codf"];
?>
  <p>Incluindo ou excluindo produtos para a Finaliza&ccedil;&atilde;o n&ordm; <? print($codf);?>
    <br>
    Total de produtos neste fechamento: 
<?
$res=mysql_query("select count(cod) as tot from cp where cod_fechamento_reg=$codf");
$tot=mysql_result($res,0,"tot");
print($tot);
?> 
<a href="frm_reg_dig.php?codf=<? print($codf);?>">Digitar</a></p>
  <form name="form1" method="post" action="scr_reg.php">
	<input type="hidden" name="codf" value="<? print($codf);?>">
	<p align="center">N&uacute;mero da Folha: 
    <input type="text" name="txtFolha">
	</p>
	<p align="center"> N&uacute;mero do Barcode:
      <input type="text" name="txtBarcode">
      <br>
      <input name="botao2" type="submit" id="botao2" value="Incluir">
    </p>
  </form>
    <h2><font color="red"><center>
  <? if (isset($_GET["erro"])){print($_GET["erro"]);}?>
  <!-- Ultima parte exibe os produtos nesta finalização-->
</center>
    </font></h2>
    <hr>
		<table width="832" border="1" align="center">
	  <tr>
	    <td width="79"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by modelo">Modelo</a></td>
	    <td width="102"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by serie">S&eacute;rie</a></td>
	    <td width="72"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by orcamento">Or&ccedil;amento</a></td>
	    <td width="262"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by controler">Controler</a></td>
	    <td width="48"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by folha">Folha</a></td>
	    <td width="93"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by destino">Destino</a></td>
	    <td width="130"><a href="frm_reg.php?codf=<? print ($codf);?>&order=order by barcode">BARCODE</a></td>
		<td width=""></td>
	  </tr>
	<?
	$sql="select modelo.descricao as modelo,cp.serie as serie,cp.orc_cliente as orcamento,rh_user.nome as controler,cp.folha_cq as folha
	,destino.descricao as destino,cp.barcode as barcode, cp.cod as cod
	from cp inner join
	modelo on modelo.cod = cp.cod_modelo inner join
	rh_user on rh_user.cod = cp.cod_cq inner join
	destino on destino.cod = cp.cod_destino
	where cp.cod_fechamento_reg=$codf
	$order";
	$res=mysql_query($sql) or die(mysql_error());
	while ($linha=mysql_fetch_array($res)){
	?>
	  <tr>
    	<td><? print($linha["modelo"]);?></td>
    	<td><? print($linha["serie"]);?></td>
	    <td><? print($linha["orcamento"]);?></td>
	    <td><? print($linha["controler"]);?></td>
	    <td><? print($linha["folha"]);?></td>
		<td><? print($linha["destino"]);?></td>
	    <td><? print($linha["barcode"]);?></td>
		<td><? $cod=$linha["cod"];
		print ("<a href='scr_exclui_reg.php?codf=$codf&cod=$cod&order=$order'>Exculir</a>");?></td>
	  </tr>
	<?
	}
	?>
</table>
<?
}
?>
</body>
</html>
