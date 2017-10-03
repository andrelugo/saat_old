<?
// ATENÇÃO ESTRE AS ASPAS ABAIXO É POSSIVEL CONFIGUAR O BANCO NORMALMENTE ACESSADO POR ESTA INSTÂNICIA DO SAAT
$banco_padrao="saat_cbd";
// CASO ESTAJA OCORRENDO ALGUM ERRO NA CONEXÃO COM O BANCO ESCREVA ENTRE A ASPAS ACIMA O NOME DO BANCO INSTALADO NO MYSQL.

if (isset($_GET["base"])){// SETANDO COKIE COM O NOME DO BD PARA USAR VARIAS BASES SEM TER QUE ALTERAR O NOME DO BANCO  NO HD.
	setcookie("base",$_GET["base"]);
}else{
	setcookie("base",$banco_padrao); // Caso não seja informado um banco no link que chega a esta página seta um banco default;
}
if (isset($_GET["msg"])){$msg=$_GET["msg"];print($msg);}
?>
<html>
<head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(../img/fundoadm.gif);
}
-->
</style></head>
<body onLoad="document.form1.txtUser.focus();" topmargin="0">
<form name="form1" method="POST" action="sis_aut.php">
  <p align="center" class="style1"><span class="Titulo1">ADMG - SAAT II - Penha Tv Color</span><br>
  <span class="style4"> Sistema de Administra&ccedil;&atilde;o de Asist&ecirc;ncia T&eacute;cnica </span></p>
  <p align="center" class="Titulo2">&nbsp;</p>
  <p align="center"><img src="../img/logo.gif" alt="Sistema de Controle Operacional Penha Tv Color" width="208" height="206"></p>
  <p align="center"><strong>Informe seu Login, Senha e frase secreta para entrar no sistema ADMG </strong></p>
  <p align="center">Login:
    <input name="txtUser" type="text" id="txtUser">
  </p>
  <p align="center">    Senha:
    <input name="txtSenha" type="password" id="txtSenha">
  </p>
  <p align="center">Frase:
    <input name="txtFrase" type="password" id="txtFrase">
<br>  
    <input type="submit" name="Submit" value="ENTRAR">
  </p>
</form>
<div align="center"><span class="Titulo2"><strong>M&oacute;dulo de Administra&ccedil;&atilde;o Geral</strong></span></div>
</body>
</html>