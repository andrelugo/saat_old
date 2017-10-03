<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm==1){
	$descricao=strtoupper($_POST["txtDescricao"]);
	$tipo=strtoupper($_POST["txtTipo"]);
	$cod_fornecedor=($_POST["cmbFornecedor"]);
	$marca=strtoupper($_POST["txtMarca"]);	
	$linha=$_POST["cmbLinha"];
	$er=$_POST["cmbExpressao"];
	$meta=str_replace(",",".",$_POST["txtMeta"]);
	$txTec=str_replace(",",".",$_POST["txtTec"]);
	$txCq=str_replace(",",".",$_POST["txtCq"]);
	$index=str_replace(",",".",$_POST["txtIndex"]);
	$codnoFornecedor=$_POST["txtCodFornecedor"];
	$codnoCliente=$_POST["txtCodCliente"];
	$acao=$_POST["cmdEnviar"];
	$ean=strtoupper($_POST["txtEan"]);
	if(isset($_POST["ativo"])){$ativo=$_POST["ativo"];}else{$ativo=0;}
	$tamCliente=strlen($codnoCliente);
	$tamean=strlen($ean);
	$tamFornecedor=strlen($codnoFornecedor);
	$vlProduto=str_replace(",",".",$_POST["txtVlProduto"]);
	$percentual=str_replace(",",".",$_POST["txtPercentual"]);
	
	if ($codnoCliente=="" || $codnoFornecedor=="" || $descricao=="" || $tipo=="" || $cod_fornecedor=="" || $cod_fornecedor=="0" || $marca=="" || $linha=="" || $linha=="0" || $meta=="" || $index=="" ){
		die ("Campos obrigatórios NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
//CONSISTENCIAS PARA EVITAR DUPLICIDADE NO CADASTRO
	//ean

	if($tamean>2){
		$pes=mysql_query("select * from modelo where ean like '$ean' and cod_expressao_regular=$er");
		$rows=mysql_num_rows($pes);
		if($rows>=1){
			if ($acao=="Alterar"){
				$cod=mysql_result($pes,0,"cod");
				if ($cod<>$_POST["codigo"]){
					$desc=mysql_result($pes,0,"descricao");
					die ("<H2>ERRO EAN E VALIDAÇÃO DO NUMERO DE SÉRIE REPETIDOS CONFLITANDO COM O CADASTRO DO MODELO $desc<br>
					 IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
					ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");				
				}
			}else{
				die ("<H2>ERRO EAN E VALIDAÇÃO DO NUMERO DE SÉRIE REPETIDOS - IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
				ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");
			}
		}
	}//fim ean
	//codcliente
	if($tamCliente>2){
		$pes=mysql_query("select * from modelo where cod_produto_cliente like '$codnoCliente' and cod_expressao_regular=$er");
		$rows=mysql_num_rows($pes);
		if($rows>=1){
			if ($acao=="Alterar"){
				$cod=mysql_result($pes,0,"cod");
				if ($cod<>$_POST["codigo"]){
					$desc=mysql_result($pes,0,"descricao");
					die ("<H2>ERRO CÓDIGO NO CLIENTE E VALIDAÇÃO DO NUMERO DE SÉRIE REPETIDOS CONFLITANDO COM O CODIGO NO CLIENTE CADASTRADO PARTA O MODELO $desc<br>
					 IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
					ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");				
				}
			}else{
				die ("<H2>ERRO CODIGO NO CLIENTE E VALIDAÇÃO DO NUMERO DE SÉRIE REPETIDOS - IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
				ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");
			}
		}
	}//fim codcliente
	//codFORNECEDOR
	if($tamFornecedor>2){
		$pes=mysql_query("select * from modelo where cod_produto_fornecedor like '$codnoFornecedor'");
		$rows=mysql_num_rows($pes);
		if($rows>=1){
			if ($acao=="Alterar"){
				$cod=mysql_result($pes,0,"cod");
				if ($cod<>$_POST["codigo"]){
					$desc=mysql_result($pes,0,"descricao");
					die ("<H2>ERRO CÓDIGO NO FORNECEDOR REPETIDO CONFLITANDO COM O CODIGO NO FORNECEDOR CADASTRADO PARTA O MODELO $desc<br>
					 IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
					ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");				
				}
			}else{
				die ("<H2>ERRO CODIGO NO FORNECEDOR REPETIDO - IMPOSSIVEL CADASTRAR NOVAMENTE!<BR> 
				ANALISE ANTES DE CADASTRAR PARA EVITAR DUPLICIDADES NO BASE DE DADOS!!!");
			}
		}
	}//fim CODFORNECEDOR
// FIM CONSISTÊNCIAS DUPLICIDADE

		$sql=mysql_query("SELECT cod from modelo
		where descricao='$descricao'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
		$row=mysql_num_rows($sql);
		if ($acao=="Alterar"){
			if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){die ("Modelo Repetido, tente outro!");// verificar de onde vem este post
			}
			$sql="update modelo set descricao='$descricao' , tipo='$tipo', cod_fornecedor='$cod_fornecedor', marca='$marca', linha='$linha',
			meta='$meta',tx_mo='$index', cod_produto_fornecedor='$codnoFornecedor', cod_produto_cliente='$codnoCliente',ean='$ean',ativo='$ativo',
			cod_expressao_regular='$er',custo_cliente='$vlProduto',perc_aprova='$percentual',tx_tec='$txTec',tx_cq='$txCq'
			where modelo.cod = $_POST[codigo]";
			$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
			$codigo =  $_POST["codigo"];

		}else{	
			if ($row>0){die ("Descrição repetida, tente outra!");
			}
				$sql="insert into modelo (descricao, tipo, cod_fornecedor,marca,linha,
				 meta,tx_mo,cod_produto_fornecedor,cod_produto_cliente,ean,ativo,cod_expressao_regular,custo_cliente,perc_aprova,tx_tec,tx_cq)
				values ('$descricao','$tipo','$cod_fornecedor','$marca','$linha',
				'$meta','$index','$codnoFornecedor','$codnoCliente','$ean',1,'$er','$vlProduto','$percentual','$txTec','$txCq')";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
				$sql1=mysql_query("select max(cod) as cod from modelo");
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
	<p><?print ($msg);?><br>
	</p>
	
  <table width="799" border="1">
    <tr>
      <td width="146">Modelo</td>
      <td width="343"><?print($descricao);?></td>
    </tr>
    <tr>
      <td>Tipo:</td>
      <td width="343"><?print($tipo);?></td>
    </tr>
    <tr>
      <td>Fornecedor</td>
      <td><?
if(isset($cod_fornecedor)){
	$sql=mysql_query("select descricao from fornecedor where cod = $cod_fornecedor")
	or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
	$result=mysql_result($sql,0,"descricao");
	print($result);
}
?></td>
    </tr>
    <tr>
      <td>Marca</td>
      <td><? print($marca);?></td>
    </tr>
    <tr>
      <td>Linha</td>
      <td><?
	if(isset($linha)){
		$sql=mysql_query("select descricao from linha where cod = $linha")
		or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
		$result=mysql_result($sql,0,"descricao");
		print($result);
	}
?></td>
    <tr>
      <td>C&oacute;digo no Fornecedor</td>
      <td><?print($codnoFornecedor);?></td>
	</tr> 
    <tr>
      <td>C&oacute;digo no Cliente </td>
      <td><?print($codnoCliente);?></td>
	</tr> 
	<tr>
	  <td>EAN</td>
	  <td><?print($ean);?></td>
	</tr>
	<tr>
	<td>Ativo</td>
	<td><? if ($ativo==1) {print("SIM");}else{print("NÃO");}?></td>
	</tr>
	<tr>
	<td>Valor do Produto</td>
	<td><?
	$vlProduto=number_format($vlProduto, 2, ',', '.');
	 print("R$ ".$vlProduto);?></td>
	</tr>
	<tr>
	<td>Percentual de Aprova&ccedil;&atilde;o </td>
	<td><? $percentual=number_format($percentual, 2, ',', '.');
	print($percentual." %");?></td>
	</tr>
	<tr>
	<td>Ponto de Equilibrio T&eacute;cnico </td>
	<td><? $txTec=number_format($txTec, 2, ',', '.');
	print("R$ ".$txTec);?></td>
	</tr>

	<tr>
	<td>Ponto de Equilibrio C.Q. </td>
	<td><? $txCq=number_format($txCq, 2, ',', '.');
	print("R$ ".$txCq);?></td>
	</tr>
	<tr>
	  <td>MO</td><td>
	  <? 
	  $index=number_format($index, 2, ',', '.');
	  print("R$ ".$index);?></td>
	</tr>
	<tr>
	  <td>Pontos</td>
	  <td><? $meta=number_format($meta, 2, ',', '.');
	  print($meta);?></td>
	</tr>
	
	
	
	
	
	
	
	


	
	
	
  </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_modelo.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_modelo.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a> * <a href="con_modelo.php">Alterar outros Modelos </a></p>
	</body></html>
	
<?	
			
//	}
}else{
	echo ("Não há privilégios de Administrador para sua conta!");
}
?>