<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	font-size: 24px;
	font-weight: bold;
	color: #0000FF;
}
-->
</style></head>

<body onLoad="document.form1.txtFolha.focus();">
<div align="center" class="style2">Página Impress&atilde;o de Registros </div>
<hr>
<form name="form3" method="get" action="pdf_res_fechamento_reg.php">
  <p><span class="style1">Registro de Sa&iacute;da (modelo 1)(Impresso diariamente). N&ordm;: </span>
      <input name="txtFolha" type="text" id="txtFolha">
      <input type="submit" name="Submit6" value="Gerar PDF">
  </p>
</form>
<form name="form2" method="get" action="pdf_fechamento_reg.php">
  <span class="style1"> Registro de Sa&iacute;da (modelo 2)......................................... N&ordm;: </span>
  <input name="txtFolha" type="text" id="txtFolha">
  <input type="submit" name="Submit" value="Gerar PDF">
</form>
<form name="form4" method="get" action="pdf_penha_fechamento_reg.php">
  <span class="style1"> Resumo do Registro de Sa&iacute;da (T&eacute;c,CQ e Modelo) ..... N&ordm;: </span>
  <input name="txtFolha" type="text" id="txtFolha">
  <input type="submit" name="Submit5" value="Gerar PDF"> 
</form>
<hr>
<form name="form1" method="get" action="pdf_folhacq.php">
  <span class="style1"> Folha do Controle de Qualidade N&ordm;: </span>
  <input name="txtFolha" type="text" id="txtFolha">
  <input type="submit" name="Submit" value="Gerar PDF">
</form>
<hr>
<form name="form1" method="get" action="pdf_orc_coletivo.php" target="_blank" >
  <div align="left" class="style1"> Or&ccedil;amento Coletivo n&ordm;:
      <input type="text" name="txtOrc">
      <input type="submit" name="Submit3" value="Imprimir">
  (PDF)</div>
</form>
<form name="form2" method="get" action="con_orc_coletivo.php" target="_blank" >
  <div align="left" class="style1">Or&ccedil;amento Coletivo n&ordm;:
      <input type="text" name="txtOrc">
      <input type="submit" name="Submit4" value="Imprimir">
  (HTML)</div>
</form>
<hr>
<form name="form3" method="get" action="con_barcode_nf_entra.php" target="_blank">
  <p><span class="style1">C&oacute;digo de Barras de Nota de EntradaN&ordm;: </span>
    <input name="txtNota" type="text" id="txtNota">
    <input type="submit" name="Submit" value="Gerar BARCODE">
  </p>
</form>

<p>&nbsp;</p>
</body>
</html>
