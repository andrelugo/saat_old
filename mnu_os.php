<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$mes=0;
$ano=date("Y");?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 16px}
body {
	background-image: url(img/fundo.gif);
}
.style5 {font-size: 24}
.style6 {font-size: 24px}
.style7 {font-size: 18px}
-->
</style>
</head>
<body>
<div align="center" class="style1">
  <p class="style6">Administra&ccedil;&atilde;o manual de Ordens de Servi&ccedil;o</p>
<hr>
<p class="style3"><span class="style5">(Nova Data)<br>
      <span class="style6"><a href="con_os_novadata.php">Enviar Ordens de Serviço</a> 	<br>
      <br>
      <a href="con_os_novadata_fin.php">Chamados Finalizados</a></span></span></p>
<hr>
<p class="style3"><span class="style5"><span class="style6"><a href="frm_os_define.php">Gravar n&uacute;mero de Ordem de Servi&ccedil;os / Chamados no sistema</a></span></span></p>
<p class="style3"><span class="style5"><span class="style6"><font color="#FF0000">
    
    </font> </span></span>
  </p>
<hr>
  <p class="style6">(Lenoxx / Fix Net)</p>
  
  <span class="style6"><a href="con_pedido_manual.php">Pedido de Pe&ccedil;as Manual</a><br>
<a href="con_os_manual.php">Ordem de Servi&ccedil;os Manual</a></span></div>
</body>
</html>
