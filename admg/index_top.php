<html>
<head>
<title>Menu</title>
</head>
<body bgcolor="#ffffff" text="BLACK" background="fundo.jpg" topmargin="0">
<table width="808" border="0" align="center">
<tr><td width="790">
<a href="mnu_extrato.php" target="mainFrame">Extrato Fornecedor</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="mnu_despesas.php" target="mainFrame">Registro de Despesas</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="mnu_balanco.php" target="mainFrame">Balanços</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="mnu_relatorios.php" target="mainFrame">+ Reletórios</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="mnu_iventario.php" target="mainFrame">Inventário</a><br>
<?
$nome = $_COOKIE["nome"];
print (" $nome");
print (date(" D d/m/Y"));
?></td>
</tr>
</table>
</body></html>