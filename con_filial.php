<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
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
<span class="style1">Clique sobre o cadastro de uma filial para fazer altera&ccedil;&otilde;es</span>
<table width="797" border="1">
  <tr>
  	<td width="17">N</td>
    <td width="86" class="style2"><span class="style3">Descric&atilde;o</span></td>
    <td width="158"><strong>Cidade</strong></td>
    <td width="139"><strong>Cliente</strong></td>
    <td width="126"><strong>Bandeira</strong></td>
    <td width="105"><strong>Contato</strong></td>
    <td width="120"><strong>Telefone</strong></td>
    <td width="10"><strong>Ativo</strong></td>
  </tr>
<?
$sql="select filial_cbd.cod as cod,filial_cbd.descricao as filial, bandeira_cbd.descricao as bandeira, cliente.descricao as cliente, filial_cbd.cidade as cidade, filial_cbd.telefone as telefone, filial_cbd.endereco as endereco ,contato, ativo
from filial_cbd 
left join bandeira_cbd on bandeira_cbd.cod = filial_cbd.cod_bandeira
left join cliente on cliente.cod = filial_cbd.cod_cliente
";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$itm=0;
while ($linha = mysql_fetch_array($res)){
$itm++;
?>
<tr>
<td><? print($itm);?></td>
<td><? print ("<a href='frm_filial.php?cod=$linha[cod]'>$linha[filial]");?></td>
<td><? print ("$linha[cidade]");?></td>
<td><? print ("$linha[cliente]");?></td>
<td><? print ("$linha[bandeira]");?></td>
<td><? print ("$linha[contato]");?></td>
<td><? print ("$linha[telefone]");?></td>
<td><? print ("$linha[ativo]");?></td>
</tr>
<?
}	
?>
</table>
</body>
</html>