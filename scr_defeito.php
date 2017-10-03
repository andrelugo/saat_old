<?
require_once("sis_valida.php");
require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario são sensitive case!
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm==1){
	$descricao = $_POST["txtDescricao"];
	$comentario = $_POST["txtComentario"];
	$linha = $_POST["cmbLinha"];
	$ativo = $_POST["chkAtivo"];
	
	$BRec = $_POST["txtBRec"];
	$BCon = $_POST["txtBCon"];
	$BCau = $_POST["txtBCau"];

	$ARec = $_POST["txtARec"];
	$ACon = $_POST["txtACon"];
	$ACau = $_POST["txtACau"];

//	if($BRec=="" || $BRec==0){$BRec="NULL";}
//	if($BCon=="" || $BCon==0){$BCon="NULL";}
//	if($BCau=="" || $BCau==0){$BCau="NULL";}
//	if($ARec=="" || $ARec==0){$ARec="NULL";}
//	if($ACon=="" || $ACon==0){$ACon="NULL";}
//	if($ACau=="" || $ACau==0){$ACau="NULL";}

	$FSec = $_POST["txtFSec"];
	$FRec = $_POST["txtFRec"];
	
	$acao=$_POST["cmdEnviar"];
	if ($descricao=="" || $linha==""){
		echo ("Campos obrigatórios (LINHA E DESCRIÇÃO) NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}else{
		$sql=mysql_query("SELECT cod from defeito
		where descricao='$descricao'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
		$row=mysql_num_rows($sql);
		if ($acao=="Alterar"){
			if (($row>0)&&(mysql_result($sql,0,"cod")!=$_POST["codigo"])){die ("Modelo Repetido, tente outro!");// verificar de onde vem este post
			}
			$sql="update defeito set descricao='$descricao',comentario='$comentario',linha='$linha',ativo='$ativo',
			cod_britaniareclamado='$BRec',cod_britaniaconstatado='$BCon',cod_britaniacausa='$BCau',
			cod_aulik_reclamado='$ARec',cod_aulik_constatado='$ACon',cod_aulik_causa='$ACau',
			cod_fixnetsecao='$FSec',cod_fixnetreclamacao='$FRec'
			where defeito.cod = $_POST[codigo]";
			$msg="A Atualização dos dados do $descricao foi realizada com sucesso! Confira os dados e selecione uma das opções abaixo.";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
			$codigo =  $_POST["codigo"];

		}else{	
			if ($row>0){die ("Descrição repetida, tente outra!");
			}
				$sql="insert into defeito (descricao,comentario,linha,ativo,
				cod_britaniareclamado,cod_britaniaconstatado,cod_britaniacausa,
				cod_aulik_reclamado,cod_aulik_constatado,cod_aulik_causa,
				cod_fixnetsecao,cod_fixnetreclamacao)
				values ('$descricao','$comentario','$linha','$ativo','$BRec','$BCon','$BCau','$ARec','$ACon','$ACau','$FSec','$FRec')";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
				$sql1=mysql_query("select max(cod) as cod from defeito");
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
      <td width="145">C&oacute;d Fix Net Se&ccedil;&atilde;o </td>
      <td width="137"><?print($FSec);?></td>
    </tr>
    <tr>
      <td>Coment&aacute;rio:</td>
      <td width="343"><?print($comentario);?></td>
      <td>C&oacute;d Fix Net Reclama&ccedil;&atilde;o </td>
      <td><?print($FRec);?></td>
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
      <td>C&oacute;d Britania Reclamado </td>
      <td><?print($BRec);?></td>
      <td>C&oacute;d Aulik Reclamado </td>
      <td><?print($ARec);?></td>
    <tr>
      <td>C&oacute;d Brit&acirc;nia Constatado </td>
      <td><?print($BCon);?></td>
      <td>C&oacute;d Aulik Constatado </td>
      <td><?print($ACon);?></td>
	</tr> 
    <tr>
      <td>C&oacute;d Brat&acirc;nia Causa </td>
      <td><?print($BCau);?></td>
      <td>C&oacute;d Aulik Causa</td>
      <td><?print($ACau);?></td>
	</tr> 
  </table>
	<p>&nbsp;</p>
	<p align="center">
	<a href="frm_defeito.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 
	<a href="frm_defeito.php?cod=<?print($codigo)?>" tabindex="2" accesskey="A">Alterar Dados</a></p>
	</body></html>
	
<?	
			
	}
}else{
	echo ("Não há privilégios de Administrador para sua conta!");
}
?>