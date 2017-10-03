<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundoadm.gif);
}
.style2 {color: #FF0000}
-->
</style></head>

<body>
<p align="center">GERAR ARQUIVO DE CARGA</p>
<p align="center">PARA O SISTEMA TELECONTROL </p>
<p align="center">&nbsp;</p>
<p align="center">Este formul&aacute;rio gera um arquivo de carga que deve ser carregado diariamente no servidor do fabricante</p>
<p align="center">&Egrave; atrav&eacute;s dele que ordens de servi&ccedil;o s&atilde;o geradas e os pedidos de pe&ccedil;as realizados
</p>
<p align="center" class="style2">Ordens de servi&ccedil;os com extrato de M&atilde;o-de-Obra n&atilde;o ser&atilde;o reenviadas neste arquivo </p>
<p align="center"></p>
<form action="scr_carga_telecontrol.php" method="get" enctype="multipart/form-data" name="form1">
  <div align="center">
    <table width="607" border="1">
	  <tr>
	  <td>&nbsp;</td>
	  <td>Tipo de Busca </td>
	  <td>Par&acirc;metro</td>
	  </tr>
      <tr>
        <td width="26"><input name="rd" type="radio" value="u" checked></td>
        <td width="171">&Uacute;ltimos XX Dias </td>
        <td width="388"><input name="dias" type="text" id="dias4" value="10" size="4" maxlength="4"></td>
      </tr>
      <tr>
        <td><input name="rd" type="radio" value="p"></td>
        <td>Per&iacute;odo (dd/mm/aaaa)</td>
        <td>Data Inicial
          <input name="txtDtIni" type="text" id="txtDtIni" size="10" maxlength="12">
          --Data Final 
          <input name="txtDtFim" type="text" id="txtDtFim" size="10" maxlength="12">          </td>
      </tr>
      <tr>
        <td><input name="rd" type="radio" value="r"></td>
        <td>Registro de sa&iacute;da </td>
        <td><input name="txtRegistro" type="text" id="txtRegistro" size="20" maxlength="20"></td>
      </tr>
    </table>
    <p>Fornecedor: 
      <select name="cmbFornecedor" class="caixaPR1" id="cmbFornecedor">
        <option value="0"></option>
        <?	  
$sql="select * from fornecedor where cod_telecontrol is not NULL order by descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
}
?>
      </select>
    </p>
    <p>      <input type="submit" name="Submit" value="Gerar Arquivo">
    </p>
    <p>
    <input type="checkbox" name="chkFinalizar" value="1">
    Somente Finaliza&ccedil;&atilde;o (N&atilde;o gerar pedido de Pe&ccedil;as ) <br>
    </p>
  </div>
</form>
<hr>
<hr>
<hr>
<p>&nbsp;</p>
<div align="center">Carga no Saat - Internet </div>
<form action="scr_geracarga_saat.php" method="get" enctype="multipart/form-data" name="form1">
  <div align="center">
  
    <p>&nbsp;</p>
    <p>
      <input type="submit" name="Submit2" value="Gerar Arquivo">
      <br>
    </p>
  </div>
</form>
<p>&nbsp;</p>
</body>
</html>
