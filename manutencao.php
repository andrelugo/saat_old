<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["tabela"])){
	$tab=$_GET["tabela"];
	$sql="repair table $tab";mysql_query($sql) or die(mysql_error()."<br> $sql");
	$sql="optimize table $tab";mysql_query($sql) or die(mysql_error()."<br> $sql");
	$msg="<h2><font color=red>Tabela $tab reparada e Otimizada!</font></h2>";
}else{
	$msg="<h2><font color=red> Nenhuma tabela foi selecionada para manutenção!</font></h2>";
}
Header("Location:index.php?msg=$msg");
?>