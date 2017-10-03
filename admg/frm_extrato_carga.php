<?
require_once("sis_valida.php");
require_once("sis_conn.php");
//if ($id<>1){die("Não logado!");}
$cod=$_GET["cod"];
$sql="select extrato_mo.descricao as extrato,fornecedor.descricao as fornecedor, extrato_mo.cod as cod
from extrato_mo inner join fornecedor on fornecedor.cod = extrato_mo.cod_fornecedor
where extrato_mo.cod = $cod";
$res=mysql_query($sql) or die (mysql_error()."<br> $sql");
$extrato=mysql_result($res,0,"extrato");
$fornecedor=mysql_result($res,0,"fornecedor");
$cod=mysql_result($res,0,"cod");
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
<form name="upload" action="scr_extrato_carga.php" method="post" enctype="multipart/form-data" onsubmit="">
  <p align="center" class="style2"><strong>Carga do  extrato de pagamento no sistema SAAT</strong><br>
  Monte um arquivo de texto com as colunas<strong> OS e VALOR</strong></p>
  <table width="428" border="1" align="center">
    <tr>
      <td width="157">Extrato</td>
      <td width="255"><? print($extrato);?></td>
    </tr>
    <tr>
      <td>Fornecedor</td>
      <td><? print($fornecedor);?></td>
    </tr>
  </table>
  <p align="center">Digite o caminho, nome (com extens&atilde;o) do arquivo:
  <input type="hidden" value="<? print($cod);?>" name="txtCod">
      <input name="txtArquivo" type="file" id="txtArquivo" size="40">
      <br>
      <input type="submit" name="enviar" value="Enviar arquivo">
</p>
  <p align="center">Selecione o Lay-Out do Arquivo :     </p>
  <p align="center">
    <input name="radiobutton" type="radio" value="os">
  OS M.O. <br>
  <input name="radiobutton" type="radio" value="osi">
OS-Item / M.O. <br>
    <input name="radiobutton" type="radio" value="barcode">
    Barcode / M.O.<br>
    <input name="radiobutton" type="radio" value="item">
  Item / OS / M.O.</p>
  <p align="center">Delimitar pesquisa do caracter
    <input name="txtIni" type="text" value="0" size="2" maxlength="2">
    ao
    <input name="txtFim" type="text" value="99" size="2" maxlength="2"> 
    nas colunas OS e Barcode</p>
  <p align="center">Use de 1 a 11 para Lenoxx<br>
  6 a 12 para Nova Data<br>
  para Brit&acirc;nia deixe de 0 a 99 para n&atilde;o haverem cortes na OS </p>
</form>
<p align="center">&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
