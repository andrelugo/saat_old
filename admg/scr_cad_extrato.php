<?
require_once("sis_valida.php");
require_once("sis_conn.php");

	$descricao=strtoupper($_POST["txtDescricao"]);
	$nota=strtoupper($_POST["txtNota"]);
	if($_POST["txtDataRecebe"]==""){$dataRecebe="NULL";}else{$dataRecebe="'".$_POST["txtDataRecebe"]."'";}
	if($_POST["txtDataExtrato"]==""){$dataExtrato="NULL";}else{$dataExtrato="'".$_POST["txtDataExtrato"]."'";}
	if($_POST["txtDataNota"]==""){$dataNota="NULL";}else{$dataNota="'".$_POST["txtDataNota"]."'";}
	$cod_fornecedor=($_POST["cmbFornecedor"]);
	$obs=strtoupper($_POST["txtObs"]);
	$acao=($_POST["cmdEnviar"]);
	if ($descricao=="" || $cod_fornecedor==0){
		die ("Campos obrigatórios NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
		$sql=mysql_query("SELECT cod from extrato_mo
		where descricao='$descricao'")or die(mysql_error());
		$row=mysql_num_rows($sql);
		if ($acao=="Alterar"){
			if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){die ("Modelo Repetido, tente outro!");// verificar de onde vem este post
			}
			$sql="update extrato_mo set descricao='$descricao',data_extrato=$dataExtrato,nota_fiscal='$nota',data_nota=$dataNota,
			data_pgto=$dataRecebe,cod_fornecedor='$cod_fornecedor',obs='$obs' where extrato_mo.cod = $_POST[codigo]";
			$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error()."<br>$sql");	
			$codigo =  $_POST["codigo"];
		}else{	
			if ($row>0){die ("Descrição repetida, tente outra!");
			}
				$sql="insert into extrato_mo (descricao, data_cad,cod_colab_cad,data_extrato,nota_fiscal,data_nota,data_pgto,cod_fornecedor,obs)
				values ('$descricao','now()','$id',$dataExtrato,'$nota',$dataNota,$dataRecebe,'$cod_fornecedor','$obs')";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
				$sql1=mysql_query("select max(cod) as cod from extrato_mo");
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
	<p><? print ($msg);?><br>
	</p>
	
  <table width="799" border="1">
    <tr>
      <td width="146">Extrato:</td>
      <td width="343"><?print($descricao);?></td>
    </tr>
    <tr>
      <td>Data Extrato </td>
      <td width="343"><?print($dataExtrato);?></td>
    </tr>
    <tr>
      <td>Nota:</td>
      <td><?print($nota);?></td>
    </tr>
    <tr>
      <td>Data da Nota </td>
      <td><?print($dataNota);?></td>
    </tr>
    <tr>
      <td>Data do Recebimento </td>
      <td><?print($dataRecebe);?></td>
    <tr>
      <td>Fornecedor</td>
      <td>
      <?
if(isset($cod_fornecedor)){
	$sqlf=mysql_query("select descricao from fornecedor where cod = $cod_fornecedor")
	or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
	$result=mysql_result($sqlf,0,"descricao");
	print($result);
}
?></td>
	</tr>
  </table>
	<p>SQL=<? print($sql);?></p>
	<p align="center">
	<a href="frm_cad_extrato.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_cad_extrato.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a> * <a href="frm_extrato_carga.php?cod=<? print("$codigo");?>">Carregar Extrato</a> </p>
	</body></html>	
