<?
$nome = $_COOKIE["nome"];

?><html>
<head>
<title>SAAT</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(fundo.jpg);
}
-->
</style></head>
<body>

<p align="center" class="style1">&nbsp;</p>
<p align="center" class="style1">
  <input type="image" border="0" name="imageField" src="logotipo.bmp">
</p>
<p align="center" class="Cabe&ccedil;alho">A mais de 25 anos prestando servi&ccedil;os de qualidade! </p>
<p align="center" class="style1">&nbsp;</p>
<p align="center" class="style1">&nbsp;</p>
<p align="center" class="Titulo2">Voc&ecirc; est&aacute; conectado ao servi&ccedil;o de informa&ccedil;&otilde;es </p>
<p align="center" class="Titulo2"> com o fornecedor <? print("$nome");?></p>
<p align="center">&nbsp;</p>
</body>
</html>