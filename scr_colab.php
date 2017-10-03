<?
require_once("sis_valida.php");
require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario são sensitive case!
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm==1)
{
	$login=$_POST["txtLogin"];
	$nome=$_POST["txtNome"];
	$senhas=md5($_POST["txtSenha"]);
	$senhap=($_POST["txtSenha"]);
	$tcontrato=$_POST["cmbContrato"];
	$salario=$_POST["txtSalario"];
	$endereco=$_POST["txtEndereco"];
	$bairro=$_POST["txtBairro"];
	$cep=$_POST["txtCep"];	
	$cpf=$_POST["txtCpf"];
	$rg=$_POST["txtRg"];
	$rf=$_POST["txtRegF"];
	$bancos=$_POST["txtBanco"];
	$agencia=$_POST["txtAgencia"];	
	$conta=$_POST["txtConta"];		
	$foneres=$_POST["txtTelRes"];		
	$fonecel=$_POST["txtTelCel"];		
	$cargo=$_POST["cmbCargo"];
//	$aAdm=$_POST["rbAdm"];
	$bg=$_POST["cmbBgColor"];
	$nasce=$_POST["txtAnoNasce"]."-".$_POST["txtMesNasce"]."-".$_POST["txtDiaNasce"];
	$admissao=$_POST["txtAnoAdm"]."-".$_POST["txtMesAdm"]."-".$_POST["txtDiaAdm"];
	$demissao=$_POST["txtAnoDem"]."-".$_POST["txtMesDem"]."-".$_POST["txtDiaDem"];
	$transporte=$_POST["txtTransporte"];
	$email=$_POST["txtEmail"];
	$linha=$_POST["cmbLinha"];
	if ($login=="" || $nome=="" || $senhap=="" || $tcontrato=="0" || $tcontrato==""|| $salario=="" || $cargo=="" || $cargo=="0" ){
		echo ("Campos obrigatórios NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}else{
		$sql=mysql_query("SELECT login,cod from rh_user
		where login='$login'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
		$row=mysql_num_rows($sql);

$acao=$_POST["cmdEnviar"];	
if ($acao=="Alterar"){

	if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){die ("Login Repetido, Tente outro LOGIN!");}
	
	$sql="update rh_user set login='$login' , senha='$senhas', nome='$nome',endereco='$endereco',
	banco='$bancos',agencia='$agencia',conta='$conta',cep='$cep',bairro='$bairro',
	telresidencia='$foneres',telcelular='$fonecel',cpf='$cpf',rg='$rg',registrof='$rf',salario='$salario',
	tipocontrato='$tcontrato',cargo='$cargo',bgcolor='$bg',data_nasce='$nasce',data_admissao='$admissao',data_demissao='$demissao',
	email='$email',transporte='$transporte',linhatec='$linha'
	where rh_user.cod = $_POST[codigo]";
	
	$msg="A Atualização dos dados de $nome foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
	
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
	$codigo =  $_POST["codigo"];

}else{	
	if ($row>0){die ("Login Repetido, Tente outro LOGIN!");}
	
	$sql="insert into rh_user (login, senha, nome,endereco,banco,agencia,conta,cep,bairro,
	telresidencia,telcelular,cpf,rg,registrof,salario,tipocontrato,cargo,bgcolor,data_nasce,data_admissao,data_demissao,
	email,transporte,linhatec)
	values ('$login','$senhas','$nome','$endereco','$bancos','$agencia','$conta','$cep','$bairro',
	'$foneres','$fonecel','$cpf','$rg','$rf','$salario','$tcontrato','$cargo','$bg','$nasce','$admissao','$demissao',
	'$email','$transporte','$linha')";
	
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());	
	
	$sql1=mysql_query("select max(cod) as cod from rh_user");
	$codigo = mysql_result($sql1,0,"cod") or die ("Erro na consulta de código".mysql_error());
$msg="O Cadastro de $nome foi realizado sob código $codigo com sucesso! Confira os dados e selecione uma das opções abaixo.";
}
?>

	<html>
	<head><title>Untitled Document</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	background-image: url(img/fundoadm.gif);
}
-->
</style></head>
	<body>
	<p><?print ($msg);?><br>
	</p>
	
  <table width="739" border="1">
    <tr>
      <td width="72">*Nome:</td>
      <td width="360"><?print($nome);?></td>
      <td width="136">Banco</td>
      <td width="143"><?print($bancos);?></td>
    </tr>
    <tr>
      <td>*Login:</td>
      <td><?print($login);?></td>
      <td>Ag&ecirc;ncia</td>
      <td><?print($agencia);?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td>Conta</td>
      <td><?print($conta);?></td>
    </tr>
    <tr>
      <td>Endere&ccedil;o</td>
      <td><?print($endereco);?></td>
      <td>Telefone res.: </td>
      <td><?print($foneres);?></td>
    </tr>
    <tr>
      <td>Bairro</td>
      <td><?print($bairro);?></td>
      <td>Telefone Cel: </td>
      <td><?print($fonecel);?></td>
    </tr>
    <tr>
      <td>CEP</td>
      <td><?print($cep);?></td>
      <td>*Tipo de Contrato</td>
      <td><?print($tcontrato);?></td>
    </tr>
    <tr>
      <td>CPF</td>
      <td><?print($cpf);?> </td>
      <td>Cargo</td>
      <td><?print($cargo);?></td>
    </tr>
    <tr>
      <td>RG</td>
      <td><?print($rg);?></td>
      <td>*Sal&aacute;rio Fixo / Bolsa </td>
      <td>R$<?print($salario);?></td>
    </tr>
    <tr>
      <td>Registro Funional </td>
      <td><?print($rf);?></td>
      <td>Vale Transporte:</td>
      <td><?print($transporte);?></td>
    </tr>
	<tr>
	<td>Fundo</td>
	<td bgcolor="<?print($bg);?>"></td>
	<td>Data de Nascimento </td>
	<td><?print($nasce);?></td>
	</tr>
	<tr>
	  <td>E-Mail:</td>
	  <td><?print($email);?></td>
	<td>Data da Admiss&atilde;o </td>
	<td><?print($admissao);?></td>
	</tr>	
	<tr>
	  <td>Acesso Administrativo</td>
	  <td><?if ($aAdm==0){$aAdm="Não";}else{$aAdm="Sim";}print($aAdm);?></td>
	<td>Data da Demiss&atilde;o </td>
	<td><?print($demissao);?></td>
	</tr>
  </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_colab.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_colab.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a></p>
	</body></html>
	
<?	
			
	}
}
else
{
	echo ("Não há privilégios de Administrador para sua conta!");
}
?>