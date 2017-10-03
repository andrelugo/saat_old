<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["erro"])){$erro=$_GET["erro"];}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style2 {font-size: 24px}
.style3 {color: #FF0000}
.style4 {
	font-size: 24px;
	font-weight: bold;
}
.style5 {
	font-size: 12px;
	font-style: italic;
}
.style8 {
	font-size: 36px;
	color: #FF0000;
}
.style9 {font-size: 18px}
-->
</style>
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="style4"><span class="style8">Excluir controle de Produ&ccedil;&atilde;o</span></p>
<?
if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font>");}
?>
<form name="form1" method="post" action="scr_excluircp.php">
  <p align="center">
  Digite o numero do C&oacute;digo de Barras ou</p>
  <p align="center" class="style3">PRESSIONE O GATILHO DO LEITOR </p>
  <table width="599" border="0" align="center">
    <tr>
      <td width="276"><span class="style4">C&oacute;digo de Barras:</span></td>
      <td width="307">
        <input name="txtBarcode" type="text" class="style2" id="txtBarcode" maxlength="20">
        <input name="cmdEnviar2" type="hidden" class="style2" id="cmdEnviar2" value="Entrar Barras"></td>
    </tr>
  </table>
  <p align="center" class="style3"><br> 

	<input name="cmdEnviar" type="submit" class="style2" id="cmdEnviar" value="Marcar" >
</p>
  <p align="center" class="style5">&nbsp;  </p>
</form>
<p align="center" class="style5 style9">Esta tela remove do sistema somente produtos que n&atilde;o foram avaliados pelo dpto.T&eacute;cnico </p>
</body>
</html>
