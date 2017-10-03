<?
//Podem ocorrer nesta pagina:
//um barcode não existir
//um barcode não estar pronto
//Um mesmo barcode ser baixado mais de uma vez
//Um barcode já ter sido aprovado
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="Número do Código de Barras não preenchido";$barcode="";}
if (!$_POST["cmbMotivo"]=="0" || !$_POST["cmbMotivo"]==""){$motivo=$_POST["cmbMotivo"];}else{$erro="Motivo não preenchido";$motivo="";}
if (empty($erro)){
	$sql=mysql_query("select data_pronto,rh_user.nome as nome,data_sai from cp left
	join rh_user on rh_user.cod=cp.cod_tec
	where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, então vamos ver se está pronto e se já não foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$pronto=mysql_result($sql,0,"data_pronto");	
		$sai=mysql_result($sql,0,"data_sai");	
		//se estiver marcado como pronto então vamos ver se já não foi entregue, senão OK!
		if (isset($pronto)){
			//se a data da saida já foi preenchida então erro, pois já foi entregue, senão OK!
			if (isset($sai)){
				$erro2="Este Barcode já foi Entregue em $sai faça uma consulta para ver o que houve!";
			}
		}else{
			$erro2="Este produto ainda não foi marcado como PRONTO pelo técnico!";
		}
	}else{
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_reprg.php?erro=$erro2&motivo=$motivo");
	}else{
		$nome=mysql_result($sql,0,"nome");	
		$erro="<font color='blue'>REPROVAÇÃO do barcode $barcode realizada com sucesso!<br> Técnico: $nome</font>";
		$sql="update cp set data_pronto=NULL,cod_cq=$id,reprova_cq=$motivo where barcode='$barcode'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVAÇÃO! $sql ".mysql_error());		
		Header("Location:frm_reprg.php?erro=$erro&motivo=$motivo");
	}
}else{
	Header("Location:frm_reprg.php?erro=$erro&motivo=$motivo");
}
?>