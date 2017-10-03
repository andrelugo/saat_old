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
		$erro="Nenhum resultado encontrado na busca pelo registro de saídas $registro";
	}else{
		$cod=mysql_result($res,$rows-1,"cod");
		$where=" cp.cod_fechamento_reg='$cod'";
	}
}
////////////////////////////////////////////////BARCODE////////////////////////////////////////////////////////
if($barcode<>""){//Insersção pelo campo Barcode
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
			$erro="O barcode $barcode ainda não foi entregue ao cliente. Impossivel gerar pré-notas";
		}else{
			if($decisao<>1){
				$erro="<h1>O orçamento do barcode $barcode foi REPROVADO pelo cliente e não receberá pré-notas</h1>";
			}
		}
	}
}
////////////////////////////////////////////////ORC_CLIENTE////////////////////////////////////////////////////////
if($orcCliente<>""){//Inserção pelo campo número de orçamento no sistema do cliente
	$res2=mysql_query("select cp.cod as cod,data_sai,orc_decisao.aprova as decisao from cp 
	inner join orc on orc.cod_cp = cp.cod
	inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
	where orc_cliente = '$orcCliente'");
	$rows = mysql_num_rows($res2);
	if ($rows == 0){
		$erro="Nenhum resultado encontrado na busca pelo orçamento no sistema do cliente $orcCliente";
	}else{
		$cod=mysql_result($res2,$rows-1,"cod");
		$where=" cp.cod='$cod'";

		$sai=mysql_result($res2,$rows-1,"data_sai");
		$decisao=mysql_result($res2,$rows-1,"decisao");
		if ($sai==NULL){
			$erro="O orçamento $orcCliente ainda não foi entregue ao cliente. Impossivel gerar pré-notas";
		}else{
			if($decisao<>1){
				$erro="<h1>O orçamento $orcCliente foi REPROVADO pelo cliente e não receberá pré-notas</h1>";
			}
		}
	}
}
// Existe a possibilidade de editar um orçamento no módulo de administração do Saat por alguem que possuam privilégios de adg_geral...
// isto faz com que seja possivel ocorrerem inconsistencias no processo de confecção de pré-notas. As validações abaixo vão filtrar estes erros
	$sqlIndef="select barcode from orc inner join cp on cp.cod = orc.cod_cp where $where and cod_decisao = 0";
	$resIndef=mysql_query($sqlIndef) or die ("Erro na pesquisa por orçamentos indefinidos <br> $sqlIndef<br>".mysql_error());
	$rowsIndef=mysql_num_rows($resIndef);
	if ($rowsIndef<>0){
		$indef = mysql_result($resIndef,0,"barcode");
		$erro="O Barcode $indef possui itens indefinidos no orçamento.<br> Impossível incluir neste estado!!!<br><strong><font color=red>Nenhum registro foi incluso nesta operação</font></strong>";
	}
// Fim validações do módulo adm_geral
if (isset($erro)){// Se não houverem erros então realiza a inclusão ou exclusão do orçamento INDIVIDUAL no fechamento
	$msg=$erro;
}else{
	if ($acao=="Incluir"){
		$res=mysql_query("select fechamento from orc inner join cp on cp.cod = orc.cod_cp where $where"); // PROVALAVELMENTE TENHA QUE ACRESCENAR 'WHERE FECHAMENTO IS NULL' PARA OS CASOS ONDE HOUVER INCLUSÃO EM UM FECHAMENTO ATRAVÉS DO BARCODE E EM SEGUDA A INCLUSÃO DOS ITENS DO REGISTRO DE SAIDA EM OUTRO FECHAMENTO ATRAVÉS DO REISTRO DE SAÍDAS QUE CONTENHA ESTE BARCODE INCLUSO INDIVIDULAMENTEW MW OUTRA COBRANÇA
		$resfech=mysql_result($res,0,"fechamento");
		if ($resfech==NULL){
			$sql="update orc inner join cp on cp.cod = orc.cod_cp set fechamento = $fechamento where $where";
			$msg="OK- Cadastrado!";
			mysql_query($sql) or die ("$sql<br>".mysql_error());
		}else{
			if ($resfech==$fechamento){
				$msg="Este orçamento já estava incluso nesta cobrança!";
			}else{
				$msg="<font color='red'>Este orçamento já esta incluso na cobrança $resfech</font>";
			}
		}
	}
	if ($acao=="Excluir"){
		$res=mysql_query("select fechamento from orc inner join cp on cp.cod = orc.cod_cp where $where");
		$resfech=mysql_result($res,0,"fechamento");
		if($resfech<>$fechamento){
			if ($resfech==NULL){
				$msg="Não excluido! Este orçamento não está em nenhuma cobrança";
			}else{
				$msg="Não excluido! Este orçamento pertençe a cobrança de numero $resfech";
			}
		}else{
			$sql="update orc inner join cp on cp.cod = orc.cod_cp set fechamento = NULL where $where and fechamento = $fechamento";
			mysql_query($sql) or die ("$sql<br>".mysql_error());
			$msg="OK- Excluído!";
		}
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Header("Location:frm_pre_nota_individual.php?fechamento=$fechamento&msg=$msg");
?>