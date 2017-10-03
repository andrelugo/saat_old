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
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
.style2 {color: #660000}
.style5 {font-size: 18px}
-->
</style>
</head>
<body>
<div align="center" class="style1">
  <p class="style2">Outros Cadastros</p>
  <p><a href="frm_os.php">Ordem de Servi&ccedil;o Autom&aacute;tica</a> <br>
    <span class="style5">(Para fornecedores cujo sistema n&atilde;o gera ordem de servi&ccedil;os, o Saat Faz)</span> </p>
  <?
//	$sqlCliente=mysql_query("select cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
//	$codCliente=mysql_result($sqlCliente,0,"cod");
//	if ($codCliente==2){
?>
  <p><a href="frm_filial.php">Cadastro de Filiais<br>
  </a>
    <span class="style5">(Para clientes corporativos que possuam mais de um ponto de venda de produtos)</span> </p>
<?
//	}
?>
</div>
</body>
</html>
