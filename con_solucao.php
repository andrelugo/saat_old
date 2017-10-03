<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<span class="style1">Clique sobre o cadastro de uma Solu&ccedil;&atilde;o para fazer altera&ccedil;&otilde;es</span>
<table width="800" border="1">
  <tr>
    <td width="223" class="style2"><span class="style3">Descric&atilde;o</span></td>
    <td width="290"><strong>Coment&aacute;rio</strong></td>
    <td width="127"><strong>C&oacute;d Brit&acirc;nia </strong></td>
    <td width="127"><strong>C&oacute;d Aulik </strong></td>
    <td width="32">Ativo</td>
    <td width="62">&nbsp;</td>
    <td width="7">&nbsp;</td>
    <td width="13">&nbsp;</td>
  </tr>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="select * from solucao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de defeitos".mysql_error());
while ($linha = mysql_fetch_array($res)){
	print ("<tr>");
		print ("<td><a href='frm_solucao.php?cod=$linha[cod]'>$linha[descricao]</td></a>");
		print ("<td>$linha[comentario]</td>");		
		print ("<td>$linha[cod_britania]</td>");		
		print ("<td>$linha[cod_aulik]</td>");		
		print ("<td>$linha[ativo]</td></tr>");		
}	
?>
</table>
</body>
</html>
