<?
require_once("sis_valida.php");
require_once("sis_conn.php");

if (isset($_GET["cod"])){
$cod=$_GET["cod"];
$msg="Alteração do Colaborador de código $cod <input name='codigo' type='hidden' value='$cod'>";
$btn="Alterar";
$sql=mysql_query("select nome,login,tipocontrato,salario,endereco,bairro,cep,cpf,rg,registrof,banco,
agencia,conta,telresidencia,telcelular,cargo,adm,rh_contrato.cod,rh_cargo.cod,
day(data_nasce)as dianasce,month(data_nasce)as mesnasce,date_format(data_nasce,'%y') as anonasce,
day(data_admissao)as diaadm,month(data_admissao)as mesadm,date_format(data_admissao,'%y') as anoadm,
day(data_demissao)as diadem,month(data_demissao)as mesdem,date_format(data_demissao,'%y') as anodem,
transporte,linhatec,email
from rh_user 
inner join rh_contrato on rh_contrato.cod = rh_user.tipocontrato
inner join rh_cargo on rh_cargo.cod = rh_user.cargo
where rh_user.cod = $cod");
	$nome = mysql_result($sql,0,"nome");	
	$login = mysql_result($sql,0,"login");	
	//$senhas=md5($_POST["txtSenha"]);
	$tcontrato = mysql_result($sql,0,"tipocontrato");	
	$salario = mysql_result($sql,0,"salario");
	$endereco = mysql_result($sql,0,"endereco");
	$bairro = mysql_result($sql,0,"bairro");
	$cep = mysql_result($sql,0,"cep");
	$cpf = mysql_result($sql,0,"cpf");
	$rg = mysql_result($sql,0,"rg");
	$rf = mysql_result($sql,0,"registrof");
	$bancos = mysql_result($sql,0,"banco");
	$agencia = mysql_result($sql,0,"agencia");
	$conta = mysql_result($sql,0,"conta");
	$foneres = mysql_result($sql,0,"telresidencia");
	$fonecel = mysql_result($sql,0,"telcelular");
	$cargo = mysql_result($sql,0,"cargo");
	$aAdm = mysql_result($sql,0,"adm");
	$codtcontrato = mysql_result($sql,0,"rh_contrato.cod");	
	$codcargo = mysql_result($sql,0,"rh_cargo.cod");
	$diaNasce = mysql_result($sql,0,"dianasce");
	$mesNasce = mysql_result($sql,0,"mesnasce");
	$anoNasce = mysql_result($sql,0,"anonasce");
	$diaAdm = mysql_result($sql,0,"diaadm");
	$mesAdm = mysql_result($sql,0,"mesadm");
	$anoAdm = mysql_result($sql,0,"anoadm");
	$diaDem = mysql_result($sql,0,"diadem");
	$mesDem = mysql_result($sql,0,"mesdem");
	$anoDem = mysql_result($sql,0,"anodem");
	$transporte = mysql_result($sql,0,"transporte");
	$linhap = mysql_result($sql,0,"linhatec");
	$email = mysql_result($sql,0,"email");
//$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de colaboradores");	

}else{
$msg="Cadastro de Colaborador";
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
<form name="form1" method="post" action="scr_colab.php">
<p><?print($msg)?></p>
 <table width="795" border="1">
    <tr>
      <td width="118">*Nome:</td>
      <td width="360"><input name="txtNome" type="text" id="txtNome" tabindex="1" value="<?if(isset($nome)){print($nome);}?>" size="60" maxlength="60"></td>
      <td width="130">Banco</td>
      <td width="159"><input name="txtBanco" type="text" id="txtBanco" tabindex="10"  size="20" maxlength="20" value="<?if(isset($bancos)){print($bancos);}?>" ></td>
    </tr>
    <tr>
      <td>*Login:</td>
      <td><input name="txtLogin" type="text" id="txtLogin" tabindex="2" value="<?if(isset($login)){print($login);}?>" size="20" maxlength="20" ></td>
      <td>Ag&ecirc;ncia</td>
      <td><input name="txtAgencia" type="text" id="txtAgencia" tabindex="11"  size="20" maxlength="20" value="<?if(isset($agencia)){print($agencia);}?>" ></td>
    </tr>
    <tr>
      <td>*Senha:</td>
      <td><input name="txtSenha" type="password" id="txtSenha" tabindex="3" size="20" maxlength="20"></td>
      <td>Conta</td>
      <td><input name="txtConta" type="text" id="txtConta"  tabindex="12" size="20" maxlength="20" value="<?if(isset($conta)){print($conta);}?>" ></td>
    </tr>
    <tr>
      <td>Endere&ccedil;o</td>
      <td><input name="txtEndereco"  tabindex="4" type="text" id="txtEndereco" size="60" maxlength="60" value="<?if(isset($endereco)){print($endereco);}?>" ></td>
      <td>Telefone res.: </td>
      <td><input name="txtTelRes" type="text" id="txtTelRes" size="20" tabindex="13"  maxlength="20" value="<?if(isset($foneres)){print($foneres);}?>" ></td>
    </tr>
    <tr>
      <td>Bairro</td>
      <td><input type="text" name="txtBairro" tabindex="5"  id="txtBairro" value="<?if(isset($bairro)){print($bairro);}?>" ></td>
      <td>Telefone Cel: </td>
      <td><input name="txtTelCel" type="text" id="txtTelCel" size="20"  tabindex="14" maxlength="20" value="<?if(isset($fonecel)){print($fonecel);}?>" ></td>
    </tr>
    <tr>
      <td>CEP</td>
      <td><input name="txtCep" type="text" tabindex="6"  id="txtCep" size="9" maxlength="9" value="<?if(isset($cep)){print($cep);}?>" ></td>
      <td>*Tipo de Contrato</td>
      <td><select name="cmbContrato" id="cmbContrato" tabindex="15" >
<?	  
$sql="select * from rh_contrato";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à rh_contrato");
$qtl=mysql_num_rows($res);
$count = 0;
while ($count < $qtl)
{
	$valor=mysql_result($res,$count,"cod");
	$descricao=mysql_result($res,$count,"descricao");
			  
	if (isset($codtcontrato)){
		if ($codtcontrato==$valor){
		print ("<option value= $valor selected> $descricao </option>");
		}else{
		print ("<option value= $valor > $descricao </option>");
		}
	}else{
	print ("<option value= $valor > $descricao </option>");	
	}
	$count++;
}
?>
    </select></td>
    </tr>
    <tr>
      <td>CPF</td>
      <td><input name="txtCpf" tabindex="7"  type="text" id="txtCpf" size="20" maxlength="20" value="<?if(isset($cpf)){print($cpf);}?>" >
      </td>
      <td>*Cargo</td>
      <td><select name="cmbCargo" id="cmbCargo"  tabindex="16" >
	  <option value="0"></option>
<?	  
$sql="select * from rh_cargo";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à rh_contrato");
while ($linha = mysql_fetch_array($res)){
	if (isset($codtcontrato)){
		if ($codcargo==$linha[cod]){
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
      <td>RG</td>
      <td><input name="txtRg" type="text" id="txtRg" tabindex="8"  size="20" maxlength="20" value="<?if(isset($rg)){print($rg);}?>" ></td>
      <td>*Sal&aacute;rio Fixo / Bolsa </td>
      <td>R$
        <input name="txtSalario" type="text" id="txtSalario"  tabindex="17" size="10" maxlength="10" value="<?if(isset($salario)){print($salario);}else{print('304');}?>" >
        ,00</td>
    </tr>
    <tr>
      <td>Registro Funional</td>
      <td><input name="txtRegF" type="text" tabindex="18"  id="txtRegF" size="20" maxlength="20" value="<?if(isset($rf)){print($rf);}?>" ></td>
      <td>Vale Transporte </td>
      <td><input name="txtTransporte" type="text" tabindex="18"  id="txtTransporte" size="20" maxlength="20" value="<?if(isset($transporte)){print($transporte);}?>" ></td>
    </tr>
    <tr>
      <td>Cor de Fundo</td>
      <td><select name="cmbBgColor" id="cmbBgColor">
        <option value="ffffff">Branco</option>
        <option value="#66ccff">Azul</option>
        <option value="#ffff99">Amarelo</option>
        <option value="#cc6666">Marrom</option>
      </select></td>
      <td>Data de Nascimento </td>
      <td><input name="txtDiaNasce" type="text" tabindex="19" id="txtDiaNasce"  value="<?if(isset($diaNasce) && $diaNasce <>'0'){print($diaNasce);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtDiaNasce.value.length==2){document.form1.txtMesNasce.focus();}">
/
  <input name="txtMesNasce" type="text" tabindex="20" id="txtMesNasce"  value="<?if(isset($mesNasce) && $mesNasce <>'0'){print($mesNasce);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesNasce.value.length==2){document.form1.txtAnoNasce.focus();}">
/
<input name="txtAnoNasce" type="text" tabindex="21" id="txtAnoNasce"  value="<?if(isset($anoNasce) && $anoNasce <>'0'){print($anoNasce);}?>" size="1" maxlength="2"></td>
    </tr>
	<tr>
	  <td>e-mail:</td>
	  <td><input name="txtEmail" type="text" tabindex="18"  id="txtEmail" size="50" maxlength="50" value="<?if(isset($email)){print($email);}?>" ></td>
	<td>Data da Admiss&atilde;o</td>
	<td><input name="txtDiaAdm" type="text" tabindex="22" id="txtDiaAdm"  value="<?if(isset($diaAdm) && $diaAdm <>'0'){print($diaAdm);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtDiaAdm.value.length==2){document.form1.txtMesAdm.focus();}">
/
  <input name="txtMesAdm" type="text" tabindex="23" id="txtMesAdm"  value="<?if(isset($mesAdm) && $mesAdm <>'0'){print($mesAdm);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesAdm.value.length==2){document.form1.txtAnoAdm.focus();}">
/
<input name="txtAnoAdm" type="text" tabindex="24" id="txtAnoAdm"  value="<?if(isset($anoAdm) && $anoAdm <>'0'){print($anoAdm);}?>" size="1" maxlength="2"></td></tr>
	    </tr>
<tr>
<td>Linha</td>
<td><select name="cmbLinha" class="style5" id="select6"  tabindex="5" >
            <option value="0">Todas</option>
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
<td>Data da Demiss&atilde;o </td>
<td><input name="txtDiaDem" type="text" tabindex="25" id="txtDiaDem2"  value="<?if(isset($diaDem) && $diaDem <>'0'){print($diaDem);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtDiaDem.value.length==2){document.form1.txtMesDem.focus();}">
/
  <input name="txtMesDem" type="text" tabindex="26" id="txtMesDem2"  value="<?if(isset($mesDem) && $mesDem <>'0'){print($mesDem);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesDem.value.length==2){document.form1.txtAnoDem.focus();}">
/
<input name="txtAnoDem" type="text" tabindex="27" id="txtAnoDem2"  value="<?if(isset($anoDem) && $anoDem <>'0'){print($anoDem);}?>" size="1" maxlength="2"></td>
</tr>

  </table>
  <p>
    <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
  </p>
</form>
<p>Campos marcados com &quot;*&quot; (asterisco) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
</body>
</html>