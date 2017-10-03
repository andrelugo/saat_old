<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("select ");

$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm==0)
{
	Header("Location:con_pendencias.php");
}
?>
<html>
<head>
<title></title>
</head>
<frameset rows="*" cols="100,*" frameborder="no" framespacing="0">
	<frame src="mnu_admgeral.php" name="mnu_admFrame" id=mnu_admFrame scrolling="NO" noresize>
	<frame src="index_adm.php" name="adm_mainFrame">
</frameset><noframes></noframes>
<body>
</body>
</html>