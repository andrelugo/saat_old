<?
require_once("sis_conn.php");
require_once("sis_valida.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>
	<?
		$res=mysql_query("select nome from rh_user where cod = $id");
		//$nome = $_COOKIE["nome"];
		$nome = mysql_result($res,0,"nome");
		echo " $nome -";
	?>
 	Sistema de Administração de Assistência Técnica
 </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <charset="iso-8859-1">
</head>

<frameset rows="50,*" cols="*" frameborder="no" framespacing="0">
  <frame src="index_top.php" name="topFrame" scrolling="NO" noresize>
  <frame src="index_main.php" name="mainFrame">
</frameset><body>
<noframes>
</noframes>

</body>
</html>
