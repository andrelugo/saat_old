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
	$sql=mysql_query("select data_pronto,data_sai,cod_posicao,cp.cod as cp from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, ent�o vamos ver se est� pronto e se j� n�o foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$sai=mysql_result($sql,$row-1,"data_sai");	
		$posatual=mysql_result($sql,$row-1,"cod_posicao");
		$cp=mysql_result($sql,$row-1,"cp");
		//Se j� est� na posi��o selecionada ent�o emite aviso ;;02/07/07
		if($posatual==$motivo){
			$erro2="<font color='green'>ATEN��O: O barcode $barcode j� estava cadastrado na posi��o selecionada!</font>";
		}
		//se estiver marcado como pronto ent�o vamos ver se j� n�o foi entregue, sen�o OK!
		if (isset($pronto)){
			//se a data da saida j� foi preenchida ent�o erro, pois j� foi entregue, sen�o OK!
			if (isset($sai)){
				if($sai==0){
					if($pronto==0){
						$sql="update cp set data_sai=NULL, data_pronto=NULL where cod =$cp";
					}else{
						$sql="update cp set data_sai=NULL where cod=$cp";
					}
					$erro="<font color='blue'>O barcode $barcode foi recuperado da exclus�o no ivent�rio!</font>";
					mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVA��O! $sql ".mysql_error());		
					Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
					exit;
				}else{
					$erro2="Este Barcode j� foi Entregue em $sai fa�a uma consulta para ver o que houve!";
				}
			}
		}
	}else{
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_poscp.php?erro=$erro2&motivo=$motivo");
	}else{
		$erro="<font color='blue'>MOVIMENTA��O do barcode $barcode realizada com sucesso!</font>";
		$sql="update cp set cod_posicao=$motivo where cod=$cp";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVA��O! $sql ".mysql_error());		
		Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
	}
}else{
	Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
}
?>
