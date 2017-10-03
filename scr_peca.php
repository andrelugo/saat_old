<?
require_once("sis_conn.php");// Cuidado! O nome dos objetos do formulario são sensitive case!
require_once("sis_valida.php");
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm<>1){
	die("Não há privilégios de Administrador para sua conta!");
}
	$descricao=$_POST["txtDescricao"];
	$codFab=$_POST["txtCodFab"];
	$cod_fornecedor=($_POST["cmbFornecedor"]);
	$linha=($_POST["cmbLinha"]);
	$custo= number_format(($_POST["txtCusto"]), 2, '.','');
	$ipi=number_format(($_POST["txtIpi"]), 2, '.','')/100;
	$simples=number_format(($_POST["txtSimples"]), 2, '.','')/100;
	$icms=number_format(($_POST["txtIcms"]), 2, '.','')/100;
	$difIcms=number_format(($_POST["txtDifIcms"]), 2, '.','')/100;
	$cpmf=number_format(($_POST["txtCpmf"]), 2, '.','')/100;
	$lucro=number_format(($_POST["txtLucro"]), 2, '.','')/100;
	$perda=number_format(($_POST["txtPerda"]), 2, '.','')/100;
	if(isset($_POST["rdOrcamento"])){$orcamento=($_POST["rdOrcamento"]);}else{$orcamento=0;}
	if(isset($_POST["rdGarantia"])){$garantia=($_POST["rdGarantia"]);}else{$garantia=0;}
	if(isset($_POST["chkRetornavel"])){$retornavel=($_POST["chkRetornavel"]);}else{$retornavel=0;}
	if(isset($_POST["chkPre"])){$pre=($_POST["chkPre"]);}else{$pre=0;}
	if(isset($_POST["codigo"])){$codpeca=($_POST["codigo"]);}else{$codpeca=0;}
	$acao=$_POST["cmdEnviar"];
//////CONSISTÊNCIAS DE PREENCHIMENTO
if ($cod_fornecedor==""){die("<h2>Fornecedor não escolhido!");}
if ($cod_fornecedor=="0" && $garantia==1){die("<h2>Fornecedor não pode ser 'TODOS' para peça em garantia!<br> 
	Selecione um fornecedor ao cadastrar uma peça que pode ser utilizada em garantia!");}
if ($linha=="0"){die("Linha para peça não selecionda");}
if ($custo==""){die("Custo da peça não preenchido");}
if ($ipi==""){die("IPI não prenchido");}
if ($orcamento=="" && $garantia==""){die("Orçamento e Garantia não preenchidos concomitantemente!");}
if ($codFab=="" && $garantia==1){die("Código do Produto no fornecedor não Preenchido para peça em garantia!<br> 
Selecione um fornecedor ao cadastrar uma peça que pode ser utilizada em garantia!
Esta informação é imprescindivel no Banco de Dados, pois permitirá o intercambio de informações com os fornecedores, <br>
Alem de ser utilizada, pelo sistema, nos formulários pedido e orc");}
if ($cod_fornecedor==1){
	$tamanho=strlen($codFab);
	if ($tamanho<6){die("Tamanho do Código no Fabricante Britânia menor que 6");}
}
if ($cod_fornecedor=="0" && $orcamento==1 && $codFab==""){
	$codF=mysql_query("SELECT max(cod_fabrica) as codFab FROM peca where cod_fornecedor=0");
	$codFab=mysql_result($codF,0,"codFab");
	$codFab++;
}
//////FIM  CONSISTÊNCIAS DE PREENCHIMENTO
// CALCULO DO PREÇO DE VENDA
	$pp=sprintf(str_replace(',','.',$custo));// SUBSTITUI  VIRGULA POR PONTO . ,
	$custoTot=(($pp+($ipi*$pp))*(1+$difIcms))*(1+$cpmf);
	$pv=$custoTot/(1-($lucro+$icms+$simples+$perda));
// FIM CALCULO PREÇO VENDA
// reconstruindo os dados para gravar no banco
	$ipi=$ipi*100;
	$simples=$simples*100;
	$icms=$icms*100;
	$difIcms=$difIcms*100;
	$cpmf=$cpmf*100;
	$lucro=$lucro*100;
	$perda=$perda*100;
//
$sql=mysql_query("SELECT cod from peca where cod_fabrica='$codFab'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
$row=mysql_num_rows($sql);
if ($acao=="Alterar"){
	if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){
		die ("Código da peça repetido, tente novamente! CodPeca=$codFab");// verificar de onde vem este post
	}
	$sql="update peca set descricao='$descricao',cod_fabrica='$codFab',cod_fornecedor='$cod_fornecedor',linha='$linha',custo='$custo',
	ipi='$ipi',simples='$simples',icms='$icms',lucro='$lucro',perda='$perda',cpmf='$cpmf',dif_icms='$difIcms',
	orcamento='$orcamento', garantia='$garantia',retornavel='$retornavel',
	pre_aprova='$pre',venda='$pv' where peca.cod = $codpeca";
	
	$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());
	$codigo =  $_POST["codigo"];
}else{	
	$sql=mysql_query("SELECT descricao from peca where descricao='$descricao'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
	$rowDesc=mysql_num_rows($sql);
	if ($rowDesc>0){die ("<h2> $rowDesc Resultado(s) encontrado(s) para a descrição $descricao <br> tente novamente!  CodPeca=$codFab");
	}
	if ($row>0){die ("Código da peça repetido, tente novamente!  CodPeca=$codFab");
	}
	if ($cod_fornecedor==1){
		die("O cadastro de peças para o fornecedor BRITÂNIA deve ser realizado através de carga! para este fornecedor somente a alteração de peças funcionará nesta tela!");
	}
	$sql="insert into peca (descricao, cod_fabrica, cod_fornecedor,linha,custo,ipi,simples,icms,lucro,perda,cpmf,dif_icms,
	orcamento,garantia,retornavel,pre_aprova,venda)
	values ('$descricao','$codFab','$cod_fornecedor','$linha','$custo','$ipi','$simples','$icms','$lucro','$perda','$cpmf','$difIcms',
	'$orcamento','$garantia','$retornavel','$pre','$pv')";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
		$sql1=mysql_query("select max(cod) as cod from peca");
		$codigo = mysql_result($sql1,0,"cod") or die ("Erro na consulta de código".mysql_error());
		$msg="O Cadastro do $descricao foi realizado sob código $codigo com sucesso! Confira os dados e selecione uma das opções abaixo.";
}
?>
<html>
	<head><title>Untitled Document</title><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(img/fundoadm.gif);
}
-->
</style></head>
	<body>
	<p>
	<? print ($msg);?><br>
	</p>
	
  <table width="739" border="1">
    <tr>
      <td width="142">Descri&ccedil;&atilde;o</td>
      <td width="289"><?print($descricao);?></td>
      <td width="137">Orcamento</td>
      <td width="143"><?if ($orcamento==1){$imp="SIM";}else{$imp="NÃO";}print($imp);?></td>
    </tr>
    <tr>
      <td>C&oacute;digo no Fornecedor</td>
      <td width="289"><?print($codFab);?></td>
      <td>Garantia</td>
      <td><?if ($garantia==1){$imp="SIM";}else{$imp="NÃO";}print($imp);?></td>
    </tr>
    <tr>
      <td>Fornecedor</td>
<td>
<?
if(isset($cod_fornecedor)){
	if ($cod_fornecedor==0){
		$result="TODOS";
	}else{
		$sql=mysql_query("select descricao from fornecedor where cod = $cod_fornecedor")
		or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
		$result=mysql_result($sql,0,"descricao");
	}
	print($result);
}
?>
</td>
      <td>Pr&eacute;-Aprovado</td>
      <td><? if ($pre==1){$imp="SIM";}else{$imp="N&Atilde;O";}print($imp);?></td>
    </tr>
    <tr>
      <td>Custo</td>
      <td><?print($custo);?></td>
      <td>Retorn&aacute;vel</td>
      <td><?if ($retornavel==1){$imp="SIM";}else{$imp="NÃO";}print($imp);?></td>
    </tr>
    <tr>
      <td>Venda</td>
      <td><?print($pv);?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	      <tr>
      <td>IPI</td>
      <td><?print($ipi);?></td>
      <td>Linha</td>
      <td>
<?
if(isset($linha)){
	$sql=mysql_query("select descricao from linha where cod = $linha")
	or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
	$result=mysql_result($sql,0,"descricao");
	print($result);
}
?>
</td>
</tr>
	<tr>
		<td>&nbsp;</td>
	    <td>&nbsp;</td>
	  <td></td>
	  <td></td></tr>
  </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_peca.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_peca.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a> </p>
	</body>
</html>