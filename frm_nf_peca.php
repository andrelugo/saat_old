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
.style5 {color: #000000}
-->
</style>
</head>
<body>
<p>
<center>
  <h1>CARGA DE PE&Ccedil;AS ATENDIDAS </h1>
<form name="upload" action="scr_nf_peca.php" method="post" enctype="multipart/form-data" onsubmit="">
  <table width="800" border="1">
    <tr>
      <td>Fornecedor</td>
      <td><select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
        <option value="0"></option>
        <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linha = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linha[cod]){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
      </select></td>
      <td>Escolha o fornecedor da Nota </td>
    </tr>
    <tr>
      <td width="189">Nota Fiscal N&ordm; </td>
      <td width="142"><input name="txtNf" type="text" id="txtNf" size="20" maxlength="20">
</td>
      <td width="447">
          <div align="left">Descreva o n&uacute;mero da nota fiscal pr&eacute;viamente cadastrada </div></td>
    </tr>
  </table>
  <table width="800" border="1">
  <tr>
  <td>
    <input name="radiobutton" type="radio" value="osi" checked>
OS-Item / PE&Ccedil;A </td>
  <td><input name="radiobutton" type="radio" value="os">
OS / PE&Ccedil;A </td>
  <td><input name="radiobutton" type="radio" value="barcode">
Barcode / PE&Ccedil;A</td>
  <td><input name="radiobutton" type="radio" value="item">
Item-OS / PE&Ccedil;A</td>
  </tr>
  </table>
  
<p>Digite o caminho, nome (com extensão) do arquivo:
    <input name="txtArquivo" type="file" id="txtArquivo" size="40"> 
    <br> 
      <input type="submit" name="enviar" value="Enviar arquivo"> 
  </p>
  <p align="center">Delimitar pesquisa do caracter
      <input name="txtIni" type="text" value="0" size="2" maxlength="2">
  ao
  <input name="txtFim" type="text" value="99" size="2" maxlength="2">
  nas colunas OS e Barcode</p>
  <p align="center">Use de 1 a 11 para Lenoxx / 
  6 a 12 para Nova Data / 
  para Brit&acirc;nia deixe de 0 a 99 para n&atilde;o haverem cortes na OS </p>
  </form> <hr>
  <p align="center">Lay-Out do Arquivo (Carga de Nota Fiscal) </p>
<table width="484" border="1">
  <tr>
    <td width="92"><strong>OS - Item </strong></td>
    <td width="154"><strong>C&oacute;digo da Pe&ccedil;a </strong></td>
    <td width="90"><strong>Pre&ccedil;o</strong></td>
    <td width="120">&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Tabela Fabricante </td>
    <td>Custo da Pe&ccedil;a </td>
    <td>&nbsp;</td>
    </tr>
</table>
</center> 
<hr>
Após a carga da nota fiscal de peças em garantia com os numeros de O.S. e peça atendida, os técnicos terão a infomação de quais produtos devem ser consertados assim como
ocorrem quando um orçamento é aprovado!
</body>
</html>
