<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$desc = $_GET["desc"];
$forn = $_GET["forn"];
$modelo = $_GET["modelo"];
$orcamento = $_GET["orcamento"];
$cortesia = $_GET["cortesia"];
$garantia = $_GET["garantia"];
if ($desc==""){
die ("<h1>Nenhum Valor Preenchido!</h1>");
}
?>
<script>
function fecha(des,cod){
	window.opener.document.form1.txtCod.value=cod;
	window.opener.document.form1.txtDescricao.value=des;
	self.close();
}
</script>
<html>
<head>
<title>Pesquisa de Peças</title>
<style type="text/css">
<!--
.style1 {font-size: 18px}
.style6 {
	font-size: 30px;
	color: #0000FF;
}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>
<body onLoad='compt=setTimeout("self.close();",9999999)' topmargin="0">
<form name="form1" method="post" action="">
<table width="342" border="0">
  <tr>
    <td width="160"><input name="imageField" type="image" src="img/IC.gif" width="100" height="90" border="0">      </td>
    <td width="39"><input type="submit" name="Submit" value="Sair" onClick="javascript:self.close();"></td>
    <td width="121">&nbsp;</td>
  </tr>
</table>
<span class="style1"><span class="style6">Pesquisa de c&oacute;digo de Pe&ccedil;as<br>em
<?
if ($orcamento==1){print("ORÇAMENTO");}
if ($garantia==1){print("GARANTIA");}
if ($cortesia==1){print("CORTESIA");}
?>
</span></span><span class="style1"><br>
  descri&ccedil;&atilde;o:
  <?
print ($desc." e fornecedor".$forn);
?>
    </span><br>
  Clique em um dos resultados para transferir para o formul&aacute;rio!</p>
<table width="309" border="1">
  <tr>
    <td width="48"><div align="center"><strong>C&oacute;digo</strong></div></td>
    <td width="245"><div align="center"><strong>Descri&ccedil;&atilde;o</strong></div></td>
  </tr>
<?
if ($garantia==1 && $forn==1){
	$sql = "select * from peca 
	inner join mup on mup.cod_peca = peca.cod
	where peca.descricao like '%$desc%' and peca.garantia=1
	and mup.cod_modelo=$modelo";
}
if ($garantia==1 && $forn<>1){
	$sql = "select * from peca 
	where peca.descricao like '%$desc%' and peca.garantia=1
	and cod_fornecedor=$forn";
}
if ($orcamento==1){
	$sql = "SELECT * FROM peca WHERE (cod_fornecedor=$forn OR cod_fornecedor =0) AND (orcamento =1) AND descricao LIKE '%$desc%'";
}

$res=mysql_query ($sql) or die ("Erro na string SQL de consulta à tabela PECA".mysql_error());
if (mysql_num_rows($res)==0){
	die ("<h2> <font color='red'>Nenhum registro encontrado para a descrição:<br> $desc</h2>");
}
$count=0;
	$jvA="this.bgColor='#99ffff';" ;
	$jvB="this.bgColor='#ffffff';" ;
while ($linha = mysql_fetch_array($res)){
	$cod=$linha["cod_fabrica"];
	$des=$linha["descricao"];
	$pre=$linha["pre_aprova"];
	if ($pre==1){$cor=" bgcolor='#BEC716'";}else{$cor="";}
	$jv="javascript:fecha(document.form1.txtDesc$count.value,'$cod');";
	print ("<tr class='style5' onMouseOver=$jvA onMouseOut=$jvB><input name='txtDesc$count' type='hidden' value='$des'><td>$cod</td> <td $cor><a href=$jv>$des</a></td></tr>");
$count++;
}
?>
</table>
</form>
</body>
</html>