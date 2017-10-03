<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["destino"])){$codDestino=$_GET["destino"];}else{$codDestino=0;}
if (isset($_GET["erro"])){$erro=$_GET["erro"];}
if (isset($_GET["contOk"])){$contOk=$_GET["contOk"];}else{$contOk=0;}
if (isset($_GET["contErro"])){$contErro=$_GET["contErro"];}else{$contErro=0;}
if (isset($_GET["contTot"])){$contTot=$_GET["contTot"];}else{$contTot=0;}
?>
<html>
<head>
<title>Controle de Qualidade</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="Titulo2"><span class="Titulo1">CONTROLE DE QUALIDADE</span><br>
Libera&ccedil;&atilde;o de Produtos Aprovados </p>
<?
if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font>");}
?>
<form name="form1" method="post" action="scr_sairg.php">
  <p align="center">
  Digite o numero do C&oacute;digo de Barras ou<br>
  <span class="style3">  PRESSIONE O GATILHO DO LEITOR</span></p>
  <table width="756" border="1" align="center">
    <tr>
      <td width="278"><span class="style4">C&oacute;digo de Barras:</span></td>
      <td width="468">
        <input name="txtBarcode" type="text" class="caixaAZ1" id="txtBarcode" maxlength="20">
      </td>
    </tr>
    <tr>
      <td class="style4">Destino:</td>
      <td class="style4"><select name="cmbDestino" class="caixaAZ1" id="select6"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from destino where cq=1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Destino");
while ($linha = mysql_fetch_array($res)){
	if (isset($codDestino)){
		if ($codDestino==$linha[cod]){
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
	</tr><tr><td colspan="2">Cadastrados: <?print ($contOk);?>	  <input type="hidden" name="contErro" value="<?print ($contErro);?>">
	  
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Erros: <?print ("<font color='red'>".$contErro."</font>");?>
	<input type="hidden" name="contOk" value="<?print ($contOk);?>">		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  Total de cliques: <?print ($contTot);?>
		  <input type="hidden" name="contTot" value="<?print ($contTot);?>"></td></tr>
  </table>
  <div align="center">
    <input name="cmdEnviar" type="submit" class="Titulo2" id="cmdEnviar3" value="APROVAR" >
    </p>
<input type="hidden" name="pg" value="frm_sairg.php">    </div>
</form>
</body>
</html>
