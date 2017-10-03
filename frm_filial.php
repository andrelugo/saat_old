<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cod"])){
	$cod=$_GET["cod"];

	$btn="Alterar";
	$sql=mysql_query("select filial_cbd.cod as cod,filial_cbd.descricao as filial, cod_bandeira, cod_cliente, 
	filial_cbd.cidade as cidade, filial_cbd.telefone as telefone, filial_cbd.endereco as endereco ,contato, ativo,obs
	from filial_cbd 
	where filial_cbd.cod = $cod");
	$filial = mysql_result($sql,0,"filial");
	$cliente = mysql_result($sql,0,"cod_cliente");
	$bandeira = mysql_result($sql,0,"cod_bandeira");
	$cidade = mysql_result($sql,0,"cidade");
	$endereco = mysql_result($sql,0,"endereco");
	$telefone = mysql_result($sql,0,"telefone");	
	$contato = mysql_result($sql,0,"contato");
	$obs = mysql_result($sql,0,"obs");
	$ativo = mysql_result($sql,0,"ativo");
	$msg="Alteração da Filial $filial";
}else{
	$msg="Cadastro de Filial";	
	$btn="Cadastrar";
	$cod="";
}
?>
<html>
<head>
<title></title>
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	background-image: url(img/fundoadm.gif);
}
.style1 {color: #FF0000}
-->
</style></head>
<body>
<form name="form1" method="post" action="scr_filial.php">
<p><? print($msg)?></p>
  <table width="887" border="1">
    <tr>
      <td width="147">* Filial:</td>
      <td width="724" bgcolor="#FFFFFF">
<?
if(isset($filial)){
	$sqlFilial=mysql_query("select count(cod) as tot from cp where filial =$filial");
	$tot=mysql_result($sqlFilial,0,"tot");
	if ($tot>=1){
?>
<input name="txtDescricao" type="hidden" id="txtDescricao" value="<? print($filial);?>">
<? print($filial);?> <input name="txtCod" type="hidden" id="txtCod" value="<? print($cod);?>"> </td>
      <?
	}else{
?>
<input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<? if(isset($filial)){print($filial);}?>" size="50" maxlength="50">
</td>
<?
	}
}else{
?>
<input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<? if(isset($filial)){print($filial);}?>" size="50" maxlength="50"><span class="style1">Preencha com cuidado... n&atilde;o ser&aacute; possivel alterar ou apagar este campo ap&oacute;s salar!!!</span></td>
<?
}
?>
    </tr>
    <tr>
      <td>* Cliente:</td>
      <td><select name="cmbCliente" class="style5" id="select6"  tabindex="5" >
        <option value="0"></option>
        <?	  
$sql="select * from cliente where revenda = 1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Linha");
while ($linha = mysql_fetch_array($res)){
	if (isset($cliente)){
		if ($cliente==$linha[cod]){
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
    </tr>
	
<tr>
      <td>Bandeira:</td>
      <td><select name="cmbBandeira" class="style5" id="select6"  tabindex="5" >
        <option value="0"></option>
        <?	  
$sql="select * from bandeira_cbd";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Linha");
while ($linha = mysql_fetch_array($res)){
	if (isset($bandeira)){
		if ($bandeira==$linha[cod]){
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
    </tr>
    <tr>
      <td>Cidade:</td>
      <td><input name="txtCidade" type="text" id="txtCidade" tabindex="1" value="<? if(isset($cidade)){print($cidade);}?>" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Endere&ccedil;o</td>
      <td><input name="txtEndereco" type="text" id="txtEndereco" tabindex="1" value="<? if(isset($endereco)){print($endereco);}?>" size="100" maxlength="100"></td>
    </tr>
    <tr>
      <td>Telefone:</td>
      <td><input name="txtTelefone" type="text" id="txtTelefone" tabindex="1" value="<? if(isset($telefone)){print($telefone);}?>" size="50" maxlength="50"></td>
    </tr>



    <tr>
      <td height="31">Contato:</td>
      <td><input name="txtContato" type="text" id="txtContato" tabindex="1" value="<? if(isset($contato)){print($contato);}?>" size="50" maxlength="50"></td>
    </tr>
	<tr>
	<td>Observa&ccedil;&otilde;es:</td>
      <td><textarea name="txtObs" cols="90" rows="4" id="txtObs">
<? if(isset($obs)){print($obs);}?>
		</textarea></td>
    </tr>
<td>Ativo</td>
<td>
<input name="chkAtivo" type="checkbox" id="chkAtivo" value="1" <? if (isset($ativo)){if($ativo==1){print("checked");}}?> >
</td>
  </table>
  <p>
    <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
     <? if (isset($cod)){print("<input type='hidden' name='codigo' value='$cod'>");}?>
</p>
</form>
<p>Campos marcados com &quot;*&quot; (asterisco) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
</body>
</html>