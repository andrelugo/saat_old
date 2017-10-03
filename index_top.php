<html>
<head>
<title>Menu</title>
</head>
<body bgcolor="#ffffff" text="BLACK" background="img/Background.jpg" topmargin="0">
<table width="808" border="0" align="center">
    <td>
<script src="xaramenu.js"></script>
<script menumaker src="saat.js"></script>

<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("select nome from rh_user where cod = $id");
$nomea=mysql_result($res,0,"nome");
//$nomea = $_COOKIE["nome"];
$nomeb = trim($nomea); // Elimina possíveis espaços antes e depois da variavel nome completo
$nome = strtok($nomeb," ");// Corta o sobrenome separado por espaços exibindo somente a primira parte do nome

print ("Bom trabalho! &nbsp; $nome &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;");
print (date(" D d/m/y"));
?>
</td>
  </tr>
</table>
</body></html>