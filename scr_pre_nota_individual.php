<? 
require_once("sis_valida.php");
require_once("sis_conn.php");
$msg="";
$fechamento = $_GET["fechamento"];
$acao = $_GET["cmdEnviar"];

$barcode=trim($_GET["txtBarcode"]); 
$orcCliente=trim($_GET["txtOrcCliente"]);
$registro=trim($_GET["txtRegistro"]);

if ($orcCliente=="" && $barcode=="" && $registro==""){$erro=" Nenhum campo preenchido para incluir! ";}
////////////////////////////////////////////////REGISTRO////////////////////////////////////////////////////////
if($registro<>""){
	$res=mysql_query("select cod from fechamento_reg where registro = '$registro'") or die (mysql_error());
	$rows = mysql_num_rows($res);
	if ($rows == 0){
		$erro="Nenhum resultado encontrado na busca pelo registro de sa�das $registro";
	}else{
		$cod=mysql_result($res,$rows-1,"cod");
		$where=" cp.cod_fechamento_reg='$cod'";
	}
}
////////////////////////////////////////////////BARCODE////////////////////////////////////////////////////////
if($barcode<>""){//Insers��o pelo campo Barcode
	$res2=mysql_query("select cp.cod as cod,data_sai,orc_decisao.aprova as decisao from cp 
	left join orc on orc.cod_cp = cp.cod
	left join orc_decisao on orc_decisao.cod = orc.cod_decisao
	where cp.barcode = '$barcode'") or die (mysql_error());
	$rows = mysql_num_rows($res2);
	if ($rows == 0){
		$erro="Nenhum resultado encontrado na busca pelo barcode $barcode";
	}else{
		$cod=mysql_result($res2,$rows-1,"cod");
		$where=" cp.cod='$cod'";
		$sai=mysql_result($res2,$rows-1,"data_sai");
		$decisao=mysql_result($res2,$rows-1,"decisao");
		if ($sai==NULL){
			$erro="O barcode $barcode ainda n�o foi entregue ao cliente. Impossivel gerar pr�-notas";
		}else{
			if($decisao<>1){
				$erro="<h1>O or�amento do barcode $barcode foi REPROVADO pelo cliente e n�o receber� pr�-notas</h1>";
			}
		}
	}
}
////////////////////////////////////////////////ORC_CLIENTE////////////////////////////////////////////////////////
if($orcCliente<>""){//Inser��o pelo campo n�mero de or�amento no sistema do cliente
	$res2=mysql_query("select cp.cod as cod,data_sai,orc_decisao.aprova as decisao from cp 
	inner join orc on orc.cod_cp = cp.cod
	inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
	where orc_cliente = '$orcCliente'");
	$rows = mysql_num_rows($res2);
	if ($rows == 0){
		$erro="Nenhum resultado encontrado na busca pelo or�amento no sistema do cliente $orcCliente";
	}else{
		$cod=mysql_result($res2,$rows-1,"cod");
		$where=" cp.cod='$cod'";

		$sai=mysql_result($res2,$rows-1,"data_sai");
		$decisao=mysql_result($res2,$rows-1,"decisao");
		if ($sai==NULL){
			$erro="O or�amento $orcCliente ainda n�o foi entregue ao cliente. Impossivel gerar pr�-notas";
		}else{
			if($decisao<>1){
				$erro="<h1>O or�amento $orcCliente foi REPROVADO pelo cliente e n�o receber� pr�-notas</h1>";
			}
		}
	}
}
// Existe a possibilidade de editar um or�amento no m�dulo de administra��o do Saat por alguem que possuam privil�gios de adg_geral...
// isto faz com que seja possivel ocorrerem inconsistencias no processo de confec��o de pr�-notas. As valida��es abaixo v�o filtrar estes erros
	$sqlIndef="select barcode from orc inner join cp on cp.cod = orc.cod_cp where $where and cod_decisao = 0";
	$resIndef=mysql_query($sqlIndef) or die ("Erro na pesquisa por or�amentos indefinidos <br> $sqlIndef<br>".mysql_error());
	$rowsIndef=mysql_num_rows($resIndef);
	if ($rowsIndef<>0){
		$indef = mysql_result($resIndef,0,"barcode");
		$erro="O Barcode $indef possui itens indefinidos no or�amento.<br> Imposs�vel incluir neste estado!!!<br><strong><font color=red>Nenhum registro foi incluso nesta opera��o</font></strong>";
	}
// Fim valida��es do m�dulo adm_geral
if (isset($erro)){// Se n�o houverem erros ent�o realiza a inclus�o ou exclus�o do or�amento INDIVIDUAL no fechamento
	$msg=$erro;
}else{
	if ($acao=="Incluir"){
		$res=mysql_query("select fechamento from orc inner join cp on cp.cod = orc.cod_cp where $where"); // PROVALAVELMENTE TENHA QUE ACRESCENAR 'WHERE FECHAMENTO IS NULL' PARA OS CASOS ONDE HOUVER INCLUS�O EM UM FECHAMENTO ATRAV�S DO BARCODE E EM SEGUDA A INCLUS�O DOS ITENS DO REGISTRO DE SAIDA EM OUTRO FECHAMENTO ATRAV�S DO REISTRO DE SA�DAS QUE CONTENHA ESTE BARCODE INCLUSO INDIVIDULAMENTEW MW OUTRA COBRAN�A
		$resfech=mysql_result($res,0,"fechamento");
		if ($resfech==NULL){
			$sql="update orc inner join cp on cp.cod = orc.cod_cp set fechamento = $fechamento where $where";
			$msg="OK- Cadastrado!";
			mysql_query($sql) or die ("$sql<br>".mysql_error());
		}else{
			if ($resfech==$fechamento){
				$msg="Este or�amento j� estava incluso nesta cobran�a!";
			}else{
				$msg="<font color='red'>Este or�amento j� esta incluso na cobran�a $resfech</font>";
			}
		}
	}
	if ($acao=="Excluir"){
		$res=mysql_query("select fechamento from orc inner join cp on cp.cod = orc.cod_cp where $where");
		$resfech=mysql_result($res,0,"fechamento");
		if($resfech<>$fechamento){
			if ($resfech==NULL){
				$msg="N�o excluido! Este or�amento n�o est� em nenhuma cobran�a";
			}else{
				$msg="N�o excluido! Este or�amento perten�e a cobran�a de numero $resfech";
			}
		}else{
			$sql="update orc inner join cp on cp.cod = orc.cod_cp set fechamento = NULL where $where and fechamento = $fechamento";
			mysql_query($sql) or die ("$sql<br>".mysql_error());
			$msg="OK- Exclu�do!";
		}
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Header("Location:frm_pre_nota_individual.php?fechamento=$fechamento&msg=$msg");
?>