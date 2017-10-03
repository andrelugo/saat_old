<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["erro"])){$erro=$_GET["erro"];}
if (isset($_GET["motivo"])){$motivo=$_GET["motivo"];}
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
-->
</style>
</head>

<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="style4">CONTROLE DE QUALIDADE<br>
  <span class="style8">Reprova&ccedil;&atilde;o de Produtos</span></p>
<?
if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font>");}
?>
<form name="form1" method="post" action="scr_reprg.php">
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
    <tr>
      <td class="style4">MOTIVO:</td>
      <td><span class="style2">
        <select name="cmbMotivo" class="style2" id="select6"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from reprova";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Solucão");
while ($linha = mysql_fetch_array($res)){
	if (isset($motivo)){
		if ($motivo==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
          </select>
      </span></td>
    </tr>
  </table>
  <p align="center" class="style3"><br> 

	<input name="cmdEnviar" type="submit" class="style2" id="cmdEnviar" value="REPROVAR" >
</p>
  <p align="center" class="style5">&nbsp;  </p>
</form>
<p align="center" class="style5">&nbsp;</p>
</body>
</html>
