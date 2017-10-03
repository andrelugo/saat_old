<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>
<body>
<p>
<center> 
<h1>CARGA DE DADOS DAS BASES SAAT</h1>
<p>&nbsp;</p>
<form name="upload" action="scr_carrega_saat.php" method="post" enctype="multipart/form-data" onsubmit="">
  <p>Digite o caminho, nome (com extensão) do arquivo:
    <input name="txtArquivo" type="file" id="txtArquivo" size="40"> 
    </p>
  <p>      <br> 
      <input type="submit" name="enviar" value="Enviar arquivo"> 
    </p>
</form> 
<p>&nbsp;</p>
<p>&nbsp;</p>
</center> 

</body>
</html>
