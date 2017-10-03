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
<p align="center" class="style1">Mensagem SAAT II </p>
<p align="center" class="style1">&nbsp;</p>
<p align="center" class="style1">&nbsp;</p>
<form name="form2" method="post" action="scr_mensagem.php">
     <p align="center">T&iacute;tulo<?
$sqlRes = mysql_query("select l1 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
//print($res);
?>     
       <input name="txtL1" type="text" id="txtL1" value="<? print($res);?>" size="100" maxlength="100">
</p>
     <p align="center">Linha 1 <?
$sqlRes = mysql_query("select l2 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
//print($res);
?>
       <input name="txtL2" type="text" id="txtL2" value="<? print("$res");?>" size="100" maxlength="100">
  </p>
     <p align="center">Linha 2 <?
$sqlRes = mysql_query("select l3 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
//print($res);
?>
       <input name="txtL3" type="text" id="txtL3" value="<? print($res);?>" size="100" maxlength="100">
     </p>
     <p align="center">Linha 3 <?
$sqlRes = mysql_query("select l4 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
//print($res);
?>
       <input name="txtL4" type="text" id="txtL4" value="<? print($res);?>" size="100" maxlength="100">
  </p>
     <p align="center"><em>Autor</em>
       <?
$sqlRes = mysql_query("select l5 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
//print($res);
?>
       <input name="txtL5" type="text" id="txtL5" value="<? print($res);?>" size="100" maxlength="100"> 
     </p>
     <p align="center">       
       <input type="submit" name="Submit2" value="Gravar"> 
     </p>
     <p align="center">&nbsp;</p>
</form>
</body>
</html>
