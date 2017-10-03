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
<span class="style1">Clique sobre o cadastro de um Modelo para fazer altera&ccedil;&otilde;es</span>
<table width="1051" border="1">
  <tr>
    <td width="62" class="style2"><span class="style3">Código</span></td>
    <td width="173"><strong>Modelo</strong></td>
	<td width="158"><strong>Validação de Série</strong></td>
    <td width="335"><strong>Descri&ccedil;&atilde;o</strong></td>
    <td width="166"><strong>Marca</strong></td>
    <td width="99"><strong>Fabricante</strong></td>
	<td width="99"><strong>Cod. no Fornecedor</strong></td>
	<td width="99"><strong>EAN</strong></td>
	<td width="99"><strong>Cod. no Cliente</strong></td>
  </tr>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="select modelo.cod,modelo.descricao,tipo,marca,fornecedor.descricao as fabricante, expressao_regular.descricao as expressao, cod_produto_fornecedor,cod_produto_cliente,ean
from modelo 
left join expressao_regular on expressao_regular.cod = modelo.cod_expressao_regular
inner join fornecedor on fornecedor.cod = modelo.cod_fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
	print ("<tr>");
		print (" <td>$linha[cod]</td>");
		print ("<td><a href='frm_modelo.php?cod=$linha[cod]'>$linha[descricao]</a></td>");
		print ("<td>$linha[expressao]</td>");
		print ("<td>$linha[tipo]</td>");		
		print ("<td>$linha[marca]</td>");		
		print ("<td>$linha[fabricante]</td>");
		print ("<td>$linha[cod_produto_fornecedor]</td>");
		print ("<td>$linha[ean]</td>");
		print ("<td>$linha[cod_produto_cliente]</td>");
	print ("<tr>");
}	
?>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
