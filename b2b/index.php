<?
// ATEN��O ESTRE AS ASPAS ABAIXO � POSSIVEL CONFIGUAR O BANCO NORMALMENTE ACESSADO POR ESTA INST�NICIA DO SAAT
$banco_padrao="saat_cbd";
// CASO ESTAJA OCORRENDO ALGUM ERRO NA CONEX�O COM O BANCO ESCREVA ENTRE A ASPAS ACIMA O NOME DO BANCO INSTALADO NO MYSQL.

if (isset($_GET["base"])){// SETANDO COKIE COM O NOME DO BD PARA USAR VARIAS BASES SEM TER QUE ALTERAR O NOME DO BANCO  NO HD.
	setcookie("base",$_GET["base"]);
}else{
	setcookie("base",$banco_padrao); // Caso n�o seja informado um banco no link que chega a esta p�gina seta um banco default;
}
if (isset($_GET["msg"])){$msg=$_GET["msg"];print($msg);}
?>
<html>
<head>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtUser.focus();" topmargin="0">
<form name="form1" method="POST" action="sis_aut.php">
  <p align="center" class="style1"><span class="Titulo1">SAAT - Penha Tv Color</span><br>
  <span class="style4"> Sistema de Administra&ccedil;&atilde;o de Asist&ecirc;ncia T&eacute;cnica </span></p>
  <p align="center" class="Titulo2">&nbsp;</p>
  <p align="center"><img src="logo.gif" alt="Sistema de Controle Operacional Penha Tv Color" width="208" height="206"></p>
  <p align="center"><strong>Informe seu Login e Senha para entrar no sistema </strong></p>
  <p align="center">Login:
    <input name="txtUser" type="text" id="txtUser">
  </p>
  <p align="center">    Senha:
    <input name="txtSenha" type="password" id="txtSenha">
    <br>  
    <input type="submit" name="Submit" value="ENTRAR">
  </p>
</form>
<div align="center"><span class="Titulo2"><strong>Business to Business</strong></span> </div>
</body>
</html>