<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["erro"])){$erro=$_GET["erro"];print("<h1><font color='red'>".$erro)."</font></h1>";}
if (isset($_GET["cp"])){$cp=$_GET["cp"];}else{$cp="";}
if (isset($_GET["os"])){$os=$_GET["os"];}else{$os="";}
if (isset($_GET["serie"])){$serie=$_GET["serie"];}else{$serie="";}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(img/FUNDO.GIF);
}
.style1 {
	font-size: 24px;
	font-weight: bold;
	color: #0000FF;
}
.style2 {color: #FF0000}
-->
</style>
<? if ($serie<>""){?>
<body onLoad="document.form1.txtSerie.focus();">
<? }else{?>
<body onLoad="document.form1.txtCp.focus();">
<? }?>
 <p align="center">	Administra&ccedil;&atilde;o Manual de Ordens de servi&ccedil;o pelo sistema SAAT II
</p>
 <p align="center" class="style1">Nova Data 
   <?
$sql="select count(cp.cod) as qt from cp inner join modelo on modelo.cod = cp.cod_modelo
where os_fornecedor=0 and cod_fornecedor = 3";
$sql="SELECT count( cp.cod ) AS qt
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
INNER JOIN linha ON linha.cod = modelo.linha
WHERE os_fornecedor = '0'
AND cod_fornecedor =3
AND linha.cortesia =0";
$sqlQt=mysql_query($sql);
$cont=mysql_result($sqlQt,0,"qt");
print("(".$cont." Chamados pendentes da NOVA DATA)");?> 
 </p>
<div align="center">
  <form name="form1" method="post" action="scr_os_novadata.php">
    <table width="439" border="1">
      <tr>
        <td width="269">Controle de Prdu&ccedil;&atilde;o : </td>		
        <td width="154"><input name="txtCp" type="text" id="txtCp" value="<? print($cp);?>" tabindex="3"></td>
      </tr>
      <tr>
        <td><p>S&eacute;rie</p>        </td>
        <td><input name="txtSerie" type="text" id="txtSerie" tabindex="1" value="<? print($serie);?>" size="0" maxlength="0"></td>
      </tr>
    </table>
    <p class="style1"> Ordem de Servi&ccedil;os (Chamado/OS/ETC) :<br>
      <input name="txtOs" type="text" class="style1" id="txtOs" value="<? print($os);?>" tabindex="2">
    </p>
    <p>
      <input type="submit" name="Submit" value="Cadastrar">    
    </p>
  </form>
  <form name="upload" action="scr_os_novadata_carga.php" method="post" enctype="multipart/form-data" onsubmit="">
    <p class="style2">Enviar chamados por arquivo de Carga<br>
      Monte um arquivo de texto com as colunas CP e Numero do chamado</p>
    <p>Digite o caminho, nome (com extens&atilde;o) do arquivo:
        <input name="txtArquivo" type="file" id="txtArquivo" size="40">
      <br>
      <input type="submit" name="enviar" value="Enviar arquivo">
    </p>
  </form>
  <hr>
  <form name="upload" action="scr_os_define_carga.php" method="post" enctype="multipart/form-data" onsubmit="">
    <p><strong>Enviar O.S. por arquivo de Carga<br>
    Monte um arquivo de texto com as colunas O.S. , BARCODE e S&Eacute;RIE.(OS Item na 4&ordf; coluna se houver) </strong></p>
    <p>Digite o caminho, nome (com extens&atilde;o) do arquivo:
        <input name="txtArquivo" type="file" id="txtArquivo" size="40">
      <br>
      <input type="submit" name="enviar" value="Enviar arquivo">
    </p>
  </form>
</div>
</body>
</html>