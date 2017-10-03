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
<span class="style1">Clique sobre o cadastro de um Defeito para fazer altera&ccedil;&otilde;es</span>
<table width="800" border="1">
  <tr>
    <td width="223" class="style2"><span class="style3">Descric&atilde;o</span></td>
    <td width="290"><strong>Coment&aacute;rio</strong></td>
    <td width="79"><strong>Brit&acirc;nia Reclamado </strong></td>
    <td width="80"><strong>Brit&acirc;nia Constatado </strong></td>
    <td width="62"><strong>Brit&acirc;nia Causa </strong></td>

    <td width="79"><strong>Aulik Reclamado </strong></td>
    <td width="80"><strong>Aulik Constatado </strong></td>
    <td width="62"><strong>Aulik Causa </strong></td>

    <td width="80"><strong>FixNet Seção </strong></td>
    <td width="62"><strong>FixNet Reclamação</strong></td>

    <td width="7">Ativo</td>
    <td width="13">&nbsp;</td>
  </tr>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="select * from defeito";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de defeitos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td><a href='frm_defeito.php?cod=$linha[cod]'>$linha[descricao]</td>");
		print ("<td>$linha[comentario]</td>");		
		print ("<td>$linha[cod_britaniareclamado]</td>");		
		print ("<td>$linha[cod_britaniaconstatado]</td>");		
		print ("<td>$linha[cod_britaniacausa]</td>");

		print ("<td>$linha[cod_aulik_reclamado]</td>");		
		print ("<td>$linha[cod_aulik_constatado]</td>");		
		print ("<td>$linha[cod_aulik_causa]</td>");
		
		print ("<td>$linha[cod_fixnetsecao]</td>");
		print ("<td>$linha[cod_fixnetreclamacao]</td>");

		print ("<td>$linha[ativo]</td></tr>");		
}	
?>

</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
