<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="Titulo2">Consultar Controle de Produ&ccedil;&atilde;o:</p>
<form name="form1" method="post" action="con_prod.php">
  <table width="558" border="1" align="center">
    <tr>
      <td>Registro de sa&iacute;da:</td>
      <td class="caixaAZ2"><input name="txtRegistro" type="text" class="caixaAZ2" id="txtRegistro" size="25" maxlength="25"></td>
    </tr>
    <tr>
      <td width="281">N&uacute;mero de S&eacute;rie:</td>
      <td width="261" class="caixaAZ2"><input name="txtSerie" type="text" class="caixaAZ2" id="txtSerie"></td>
    </tr>
    <tr>
      <td>C&oacute;digo de Barras:</td>
      <td class="caixaAZ2"><input name="txtBarcode" type="text" class="caixaAZ2" id="txtBarcode"></td>
    </tr>
    <tr>
      <td>Or&ccedil;amento: </td>
      <td class="caixaAZ2"><input name="txtOrcamento" type="text" class="caixaAZ2" id="txtOrcamento2"></td>
    </tr>
    <tr>
      <td>Ordem de Servi&ccedil;o </td>
      <td class="caixaAZ2"><input name="txtOs" type="text" class="caixaAZ2" id="txtOs" size="7" maxlength="7">
      <input name="txtOsItem" type="text" class="caixaAZ2" id="txtOsItem" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td>Extrato: </td>
      <td class="caixaAZ2"><span class="caixaAZ1">
        <select name="cmbExtrato" id="cmbExtrato"  tabindex="5" >
		<option></option>
          <?
		$sql="select * from extrato_mo where data_pgto is null and cod_fornecedor = $id order by cod desc";
		$res=mysql_query($sql) or die(mysql_error()."$sql");
		while ($linha = mysql_fetch_array($res)){
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
?>
        </select>
      </span></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input name="cmdBuscar" type="submit" class="caixaAZ2" id="cmdBuscar" value="Buscar">
      </div></td>
    </tr>
  </table>
  <div align="center"></div>
</form>
<hr>
</body>
</html>
