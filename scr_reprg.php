<?
//Podem ocorrer nesta pagina:
//um barcode n�o existir
//um barcode n�o estar pronto
//Um mesmo barcode ser baixado mais de uma vez
//Um barcode j� ter sido aprovado
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="N�mero do C�digo de Barras n�o preenchido";$barcode="";}
if (!$_POST["cmbMotivo"]=="0" || !$_POST["cmbMotivo"]==""){$motivo=$_POST["cmbMotivo"];}else{$erro="Motivo n�o preenchido";$motivo="";}
if (empty($erro)){
	$sql=mysql_query("select data_pronto,rh_user.nome as nome,data_sai from cp left
	join rh_user on rh_user.cod=cp.cod_tec
	where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, ent�o vamos ver se est� pronto e se j� n�o foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$pronto=mysql_result($sql,0,"data_pronto");	
		$sai=mysql_result($sql,0,"data_sai");	
		//se estiver marcado como pronto ent�o vamos ver se j� n�o foi entregue, sen�o OK!
		if (isset($pronto)){
			//se a data da saida j� foi preenchida ent�o erro, pois j� foi entregue, sen�o OK!
			if (isset($sai)){
				$erro2="Este Barcode j� foi Entregue em $sai fa�a uma consulta para ver o que houve!";
			}
		}else{
			$erro2="Este produto ainda n�o foi marcado como PRONTO pelo t�cnico!";
		}
	}else{
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_reprg.php?erro=$erro2&motivo=$motivo");
	}else{
		$nome=mysql_result($sql,0,"nome");	
		$erro="<font color='blue'>REPROVA��O do barcode $barcode realizada com sucesso!<br> T�cnico: $nome</font>";
		$sql="update cp set data_pronto=NULL,cod_cq=$id,reprova_cq=$motivo where barcode='$barcode'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVA��O! $sql ".mysql_error());		
		Header("Location:frm_reprg.php?erro=$erro&motivo=$motivo");
	}
}else{
	Header("Location:frm_reprg.php?erro=$erro&motivo=$motivo");
}
?>