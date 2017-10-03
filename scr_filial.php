<?
require_once("sis_valida.php");
require_once("sis_conn.php");

if (isset($_POST["txtCod"])){
	$cod = $_POST["txtCod"];
}else{
	$cod = "";
}
$descricao = $_POST["txtDescricao"];
$bandeira = $_POST["cmbBandeira"];
$telefone = $_POST["txtTelefone"];
$contato = $_POST["txtContato"];
$obs = $_POST["txtObs"];
$acao = $_POST["cmdEnviar"];
$cliente = $_POST["cmbCliente"];
$cidade = $_POST["txtCidade"];
$endereco = $_POST["txtEndereco"];

//if (isset($_GET["cod"])){

if (isset($_POST["chkAtivo"])){
	$ativo = $_POST["chkAtivo"];
}else{
	$ativo = 0;
}

$sqlCliente=mysql_query("select cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$codCliente=mysql_result($sqlCliente,0,"cod");
if ($codCliente==1){
	if ($descricao==""){
		die ("DESCRIÇÃO NÃO PREENCHIDA<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
}else{
	if ($descricao=="" || $bandeira=="" || $bandeira==0){
		die ("Campos obrigatórios NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}

}
$sqlr=mysql_query("select cod from filial_cbd where descricao = $descricao");
$row=mysql_num_rows($sqlr);
if ($acao=="Alterar"){
	if ($row==0){die ("INFELISMENTE NÃO É POSSIVEL ALTERAR O NUMERO DA LOJA...!!!");}
	$sql="update filial_cbd 
	set cod_cliente=$cliente, cod_bandeira='$bandeira',cidade='$cidade', endereco='$endereco',telefone='$telefone',contato='$contato',obs='$obs', ativo=$ativo
	where cod = $cod";
	$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
}else{	
	if ($row>0){die ("Descrição repetida, tente outra!");}
		$sql="insert into filial_cbd (descricao,cod_cliente, cod_bandeira, cidade,endereco,telefone, contato, obs, ativo)
		values ('$descricao','$cliente','$bandeira','$cidade','$endereco','$telefone','$contato','$obs','$ativo')";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
		$msg="O Cadastro da filial $descricao foi realizado com sucesso! Confira os dados e selecione uma das opções abaixo.";
}
?>

	<html>
	<head><title></title>
    </style></head>
	<body>
	<p><?print ($msg);?><br>
	</p>
	
  <table width="798" border="1">
    <tr>
      <td width="146">Filial:</td>
      <td width="636"><?print($descricao);?></td>
    </tr>
	<tr>
      <td>Cliente:</td>
      <td width="636"><?
	if(isset($cliente)){
		$sql="select descricao from cliente where cod = $cliente";
		$res=mysql_query($sql)or die(mysql_error());
		$result=mysql_result($res,0,"descricao");
		print($result);
	}
?></td>
    </tr>
    <tr>
      <td>Bandeira:</td>
      <td width="636"><?
	if(isset($bandeira)){
		if($bandeira<>0){
			$sql="select descricao from bandeira_cbd where cod = $bandeira";
			$res=mysql_query($sql)or die("Erro no Camando de pesquisa do nome da Banderia".mysql_error());
			$result=mysql_result($res,0,"descricao");
			print($result);
		}
	}
?></td>
    </tr>

    <tr>
      <td>Cidade:</td>
      <td>      <?print($cidade);?></td>
    </tr>
    <tr>
      <td>Endereço:</td>
      <td>      <?print($endereco);?></td>
    </tr>
	
    <tr>
      <td>Telefone:</td>
      <td>      <?print($telefone);?></td>
    </tr>
    <tr>
      <td>Contato:</td>
      <td><?print($contato);?></td>
    </tr>
    <tr>
      <td>Observa&ccedil;&otilde;es:</td>
      <td><textarea name="txtObs" cols="90" rows="4" id="txtObs" readonly style="background-image:url(img/FUNDO.GIF)">
<? if(isset($obs)){print($obs);}?>
		</textarea></td>
    <tr>
      <td>Ativo:</td>
      <td>
<? 
	if ($ativo==1){
		$Dativo="SIM";
	}else{
		$Dativo="NÃO";
	}
	print($Dativo);
?></td>
    </tr>


      </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_filial.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<? if($cod<>""){?>
	<a href="frm_filial.php?cod=<? print($cod); ?>" tabindex="2" accesskey="A">Alterar Dados</a></p>
	<? }?>
	</body></html>
	