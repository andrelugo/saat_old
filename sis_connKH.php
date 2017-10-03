<?php
$servidor = "mysql.kinghost.net";
//$bd = "penhatv";
$bd = $_COOKIE["base"];// 	COOKIE SETADO NA PÁGIA INDEX.PHP NA PASTA 'WWW/SAATII'
$u = "penhatv";
$u = "$bd";
$a = 191817;
$Link=mysql_connect($servidor, $u, $a) or die ("Erro no Link");
$banco=mysql_select_db($bd)or die ("Erro no DB <br> Base = $bd");
//Adicionado em 16 junho de 2006
	$sqlCliente=mysql_query("select cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
	$tot = mysql_num_rows ($sqlCliente);
	if ($tot>0){
		$codCliente=mysql_result($sqlCliente,0,"cod");
	}else{
		$codCliente=0;
	}
?>
