<?
require_once("sis_valida.php");
require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario são sensitive case!
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm==1){
	$descricao = $_POST["txtDescricao"];
	$comentario = $_POST["txtComentario"];
	$linha = $_POST["cmbLinha"];
	if(isset($_POST['chkAtivo'])){$ativo = $_POST['chkAtivo'];}else{$ativo=0;}
	$britania = $_POST["txtBritania"];
	$aulik = $_POST["txtAulik"];
	$acao=$_POST["cmdEnviar"];
	if ($descricao=="" || $linha==""){
		echo ("Campos obrigatórios (descrição e linha) NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}else{
		$sql=mysql_query("SELECT cod from solucao
		where descricao='$descricao'")or die("Erro no Camando SQL pág scr_solucao.php".mysql_error());
		$row=mysql_num_rows($sql);
		if ($acao=="Alterar"){
			if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){die ("Soluçõa Repetida, tente outra!");// verificar de onde vem este post
			}
			$sql="update solucao set descricao='$descricao',comentario='$comentario',linha='$linha',ativo='$ativo',
			cod_britania='$britania',cod_aulik='$aulik'
			where solucao.cod = $_POST[codigo]";
			$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
			$codigo =  $_POST["codigo"];
		}else{	
			if ($row>0){die ("Descrição repetida, tente outra!");
			}
				$sql="insert into solucao (descricao,comentario,linha,ativo,cod_britania,cod_aulik)
				values ('$descricao','$comentario','$linha','$ativo','$britania','$aulik')";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
				$sql1=mysql_query("select max(cod) as cod from solucao");
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
      <td width="146">Descri&ccedil;&atilde;o:</td>
      <td width="343"><?print($descricao);?></td>
      <td width="145">&nbsp;</td>
      <td width="137">&nbsp;</td>
    </tr>
    <tr>
      <td>Coment&aacute;rio:</td>
      <td width="343"><?print($comentario);?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Linha:</td>
      <td><?
	if(isset($linha)){
	$sql=mysql_query("select descricao from linha where cod = $linha")
	or die("Erro no Camando de pesquisa do nome do Fornecedor".mysql_error());
	$result=mysql_result($sql,0,"descricao");
	print($result);
	}
?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Ativo:</td>
      <td><?print($ativo);?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>C&oacute;d Britania Solu&ccedil;&atilde;o</td>
      <td><?print($britania);?></td>
      <td>C&oacute;d Britania Solu&ccedil;&atilde;o</td>
      <td><?print($aulik);?></td>
    </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_solucao.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_solucao.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a></p>
	</body></html>
	
<?	
			
	}
}else{
	echo ("Não há privilégios de Administrador para sua conta!");
}
?>