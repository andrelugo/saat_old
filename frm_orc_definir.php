<? // Analizar 22/09/07
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["definicao"])){$codDestino=$_GET["definicao"];}else{$codDestino=0;}
if (isset($_GET["erro"])){$erro=$_GET["erro"];}
if (isset($_GET["contOk"])){$contOk=$_GET["contOk"];}else{$contOk=0;}
if (isset($_GET["contErro"])){$contErro=$_GET["contErro"];}else{$contErro=0;}
if (isset($_GET["contTot"])){$contTot=$_GET["contTot"];}else{$contTot=0;}
?>
<html>
<head>
<title>Definição de Orçamento</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.form1.txtBarcode.focus();">
<p align="center" class="Titulo2"><span class="Titulo1">Defini&ccedil;&atilde;o de Or&ccedil;amentos</span><br> 
<?
if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font>");}
?>
</p>
<form name="form1" method="post" action="scr_orc_definir.php">
  <p align="center">
  Digite o n&uacute;mero do Or&ccedil;amento Coletivo ou <br>
  Digite o n&uacute;mero do C&oacute;digo de Barras pressionando o <span class="style3">Gatilho do Leitor </span></p>
  <table width="756" border="1" align="center">
  <tr>
      <td width="278"><span class="style4">Or&ccedil;amento Coletivo :</span></td>
      <td width="468">
        <input name="txtOrcColetivo" type="text" class="caixaAZ1" id="txtOrcColetivo" maxlength="20">
      </td>
    </tr>
	<tr>
      <td width="278"><span class="style4">Orçamento no cliente:</span></td>
      <td width="468">
        <input name="txtOrcCliente" type="text" class="caixaAZ1" id="txtOrcCliente" maxlength="20">
      </td>
    </tr>
    <tr>
      <td width="278"><span class="style4">C&oacute;digo de Barras:</span></td>
      <td width="468">
        <input name="txtBarcode" type="text" class="caixaAZ1" id="txtBarcode" maxlength="20">
      </td>
    </tr>
    <tr>
      <td class="style4">Defini&ccedil;&atilde;o:</td>
      <td class="style4"><select name="cmbDefinicao" class="caixaAZ2" id="cmbDefinicao"  tabindex="5" >
            <option value="0"></option>
			<option value="1">Marcar como Pré-Aprovado e não gerar Pré-Notas Agora</option>
            <?	  
$sql="select * from orc_decisao where ativo=1 and cod>1";
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
	</tr><tr>
	  <td colspan="2">Definidos: <?print ($contOk);?>	  <input type="hidden" name="contErro" value="<?print ($contErro);?>">
	  
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Erros: <?print ("<font color='red'>".$contErro."</font>");?>
	<input type="hidden" name="contOk" value="<?print ($contOk);?>">		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  Total de cliques: <?print ($contTot);?>
		  <input type="hidden" name="contTot" value="<?print ($contTot);?>"></td></tr>
  </table>
  <div align="center">
    <input name="cmdEnviar" type="submit" class="Titulo2" id="cmdEnviar3" value="Definir" >
    </p>
<input type="hidden" name="pg" value="frm_orc_definir.php">    </div>
</form>

<p>Após definir um orçamento é possivel liberar um produto. Caso um produto não tenha a definição do orçamento será bloqueado quando o técnico tentar marcá-lo como pronto!</p>
<p>E após liberar um produto com orçamento aprovado é possível gerar a pré-notas para impressão! Parar isso vá em Administração > Orçamento > Pré-Notas </p>
</body>
</html>
