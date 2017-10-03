<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style></head>
<body>
<p align="center" class="style1">BackUp SAAT II </p>
<p align="center" class="style1">&nbsp;</p>
<p align="center" class="style1">&nbsp;</p>
<form name="form2" method="post" action="scr_backup.php">
     <p align="center">Fazer Backup na Pasta
       <?
$sqlRes = mysql_query("select pasta_backup from base");
$res=mysql_result($sqlRes,0,"pasta_backup");
//print($res);
?>     
       <input name="txtPasta" type="text" id="txtPasta" value="<? print($res);?>">
       <input type="submit" name="Submit2" value="BackUp"> 
     </p>
     <p align="center">Obs.: A pasta de destino deve estar vazia O endere&ccedil;o da pasta deve aparecer no formato do exemplo abaixo. </p>
     <p align="center">Ex.: E:/Backup/ com barras normais </p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
