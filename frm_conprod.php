<html>
<head>
<title>Menu de consultas</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="caixaAZ2">Consultar Controle de Produ&ccedil;&atilde;o:</p>
<form name="form1" method="post" action="con_prod.php">
  <table width="558" border="1" align="center">
    <tr>
      <td>Controle de Produ&ccedil;&atilde;o :</td>
      <td class="caixaAZ2"><input name="txtCp" type="text" class="caixaAZ2" id="txtCp" size="25" maxlength="25"></td>
    </tr>
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
      <td>Folha Contr. Qualidade:</td>
      <td class="caixaAZ2"><input name="txtFolha" type="text" class="caixaAZ2" id="txtFolha"></td>
    </tr>
    <tr>
      <td>Ordem de Servi&ccedil;o </td>
      <td class="caixaAZ2"><input name="txtOs" type="text" class="caixaAZ2" id="txtOs" size="13" maxlength="16">
      <input name="txtOsItem" type="text" class="caixaAZ2" id="txtOsItem" size="3" maxlength="4"></td>
    </tr>
    <tr>
      <td>Data de entrada: </td>
      <td class="caixaAZ2"><input name="txtdEntrada" type="text" class="caixaAZ2" id="txtdEntrada" size="2" maxlength="2">
        /
        <input name="txtmEntrada" type="text" class="caixaAZ2" id="txtmEntrada" size="2" maxlength="2">
        /
      <input name="txtaEntrada" type="text" class="caixaAZ2" id="txtaEntrada" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td>Data de Analize</td>
      <td class="caixaAZ2"><input name="txtdAnalize" type="text" class="caixaAZ2" id="txtdAnalize" size="2" maxlength="2">
        /
        <input name="txtmAnalize" type="text" class="caixaAZ2" id="txtAnalize2" size="2" maxlength="2">
        /
      <input name="txtaAnalize" type="text" class="caixaAZ2" id="txtAnalize3" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td>Data de Certifica&ccedil;&atilde;o Qualidade </td>
      <td class="caixaAZ2"><input name="txtdSaida" type="text" class="caixaAZ2" id="txtdSaida" size="2" maxlength="2">
      /
      <input name="txtmSaida" type="text" class="caixaAZ2" id="txtSaida2" size="2" maxlength="2">
      /
      <input name="txtaSaida" type="text" class="caixaAZ2" id="txtSaida3" size="2" maxlength="2"></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input name="cmdBuscar" type="submit" class="caixaAZ2" id="cmdBuscar" value="Buscar">
      </div></td>
    </tr>
  </table>
  <div align="center"></div>
</form>
<form name="upload" action="con_txt_status.php" method="post" enctype="multipart/form-data" onsubmit="">
    <p align="center" class="style2"><strong>Relet&oacute;rio de Status de Produtos</strong><br>
      Digite o caminho, nome (com extens&atilde;o) do arquivo:
      <input name="txtArquivo" type="file" id="txtArquivo" size="40">      
      <input type="submit" name="enviar" value="Enviar arquivo">
</p>
    <p align="center" class="style2">Consultar por: </p>
    <p align="center" class="style2">
      <input name="rdT" type="radio" value="ba">
    Barcode      </p>
    <p align="center" class="style2">
    <input name="rdT" type="radio" value="os">
  Ordem de servi&ccedil;os      </p>
    <p align="center" class="style2">
    <input name="rdT" type="radio" value="cp">
  Controle de Produ&ccedil;&atilde;o </p>
    <p align="center" class="style2">Delimiar campo iniciando pesquisa no caracter 
      <input name="cIni" type="text" size="3" maxlength="3">
 e at&eacute; os 
 <input name="cTot" type="text" size="3" maxlength="3">
caracteres seguintes </p>
</form>
<p>&nbsp; </p>
</body>
</html>
