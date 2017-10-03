<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cod"])){
	$cod=$_GET["cod"];
	$msg="Alteração do Produto de código $cod <input name='codigo' type='hidden' value='$cod'>";
	$btn="Alterar";
	$sql=mysql_query("select * from defeito where cod = $cod");
	$descricao = mysql_result($sql,0,"descricao");	
	$comentario = mysql_result($sql,0,"comentario");
	$linhap = mysql_result($sql,0,"linha");	
	$ativo = mysql_result($sql,0,"ativo");

	$BRec = mysql_result($sql,0,"cod_britaniareclamado");
	$BCon = mysql_result($sql,0,"cod_britaniaconstatado");
	$BCau = mysql_result($sql,0,"cod_britaniacausa");

	$ARec = mysql_result($sql,0,"cod_aulik_reclamado");
	$ACon = mysql_result($sql,0,"cod_aulik_constatado");
	$ACau = mysql_result($sql,0,"cod_aulik_causa");

	$FSec = mysql_result($sql,0,"cod_fixnetsecao");
	$FRec = mysql_result($sql,0,"cod_fixnetreclamacao");
}else{
$msg="Cadastro de Produto";
$btn="Cadastrar";
}
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	background-image: url(img/fundoadm.gif);
}
-->
</style></head>

<body>
<form name="form1" method="post" action="scr_defeito.php">
<p><?print($msg)?></p>
  <table width="801" border="1">
    <tr>
      <td width="150">*Descri&ccedil;&atilde;o:</td>
      <td width="301"><input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<?if(isset($descricao)){print($descricao);}?>" size="50" maxlength="50"></td>
      <td width="168">C&oacute;digo Fix Net Se&ccedil;&atilde;o </td>
      <td width="154"><input name="txtFSec" type="text" id="txtFSec"  value="<?if(isset($FSec)){print($FSec);}?>" size="5" maxlength="5"></td>
    </tr>
    <tr>
      <td>Coment&aacute;rio:</td>
      <td><input name="txtComentario" type="text" id="txtComentario" tabindex="2" size="50" maxlength="100" value="<?if(isset($comentario)){print($comentario);}?>" ></td>
      <td>C&oacute;digo Fix Net Reclama&ccedil;&atilde;o </td>
      <td><input name="txtFRec" type="text" id="txtFRec" tabindex="11"  size="5" maxlength="5" value="<?if(isset($FRec)){print($FRec);}?>" ></td>
    </tr>
    <tr>
      <td>*Linha</td>
      <td><select name="cmbLinha" class="style5" id="select6"  tabindex="5" >
        <option value="0"></option>
        <?	  
$sql="select * from linha";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Linha");
while ($linha = mysql_fetch_array($res)){
	if (isset($linhap)){
		if ($linhap==$linha[cod]){
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="31">*Ativo</td>
      <td><input name="chkAtivo" type="checkbox" id="chkAtivo" value="1" checked></td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
	<tr>
	<td>*C&oacute;digo Brit&acirc;nia Reclamado </td>
      <td><input name="txtBRec" type="text" id="txtBRec" size="11" maxlength="11" value="<?if(isset($BRec)){print($BRec);}?>" ></td>
      <td>*C&oacute;digo Aulik Reclamado </td>
      <td><input name="txtARec" type="text" id="txtARec" size="11" maxlength="11" value="<?if(isset($ARec)){print($ARec);}?>" ></td>
	</tr>
		<tr>
	<td>*C&oacute;digo Brit&acirc;nia Constatado </td>
      <td><input name="txtBCon" type="text" id="txtBCon" size="11" maxlength="11" value="<?if(isset($BCon)){print($BCon);}?>"></td>
	  <td>*C&oacute;digo Aulik Constatado </td>
      <td><input name="txtACon" type="text" id="txtACon" size="11" maxlength="11" value="<?if(isset($ACon)){print($ACon);}?>"></td>
		</tr>
	<tr>
	  <td>*C&oacute;digo Brit&acirc;nia Causa </td>
	  <td><input name="txtBCau" type="text" id="txtCodCliente2" size="11" maxlength="11" value="<?if(isset($BCau)){print($BCau);}?>"></td>
	<td>*C&oacute;digo Aulik Causa</td>
	<td><input name="txtACau" type="text" id="txtACau" size="11" maxlength="11" value="<?if(isset($ACau)){print($ACau);}?>"></td>
	</tr>
  </table>
  <p>
    <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
     <? if (isset($cod)){print("<input type='hidden' name='codigo' value='$cod'>");}?>
</p>
</form>
<p>Campos marcados com &quot;*&quot; (asterisco) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
</body>
</html>