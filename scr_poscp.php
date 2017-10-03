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
	$sql=mysql_query("select data_pronto,data_sai,cod_posicao,cp.cod as cp from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, então vamos ver se está pronto e se já não foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$sai=mysql_result($sql,$row-1,"data_sai");	
		$posatual=mysql_result($sql,$row-1,"cod_posicao");
		$cp=mysql_result($sql,$row-1,"cp");
		//Se já está na posição selecionada então emite aviso ;;02/07/07
		if($posatual==$motivo){
			$erro2="<font color='green'>ATENÇÃO: O barcode $barcode já estava cadastrado na posição selecionada!</font>";
		}
		//se estiver marcado como pronto então vamos ver se já não foi entregue, senão OK!
		if (isset($pronto)){
			//se a data da saida já foi preenchida então erro, pois já foi entregue, senão OK!
			if (isset($sai)){
				if($sai==0){
					if($pronto==0){
						$sql="update cp set data_sai=NULL, data_pronto=NULL where cod =$cp";
					}else{
						$sql="update cp set data_sai=NULL where cod=$cp";
					}
					$erro="<font color='blue'>O barcode $barcode foi recuperado da exclusão no iventário!</font>";
					mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVAÇÃO! $sql ".mysql_error());		
					Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
					exit;
				}else{
					$erro2="Este Barcode já foi Entregue em $sai faça uma consulta para ver o que houve!";
				}
			}
		}
	}else{
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_poscp.php?erro=$erro2&motivo=$motivo");
	}else{
		$erro="<font color='blue'>MOVIMENTAÇÃO do barcode $barcode realizada com sucesso!</font>";
		$sql="update cp set cod_posicao=$motivo where cod=$cp";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVAÇÃO! $sql ".mysql_error());		
		Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
	}
}else{
	Header("Location:frm_poscp.php?erro=$erro&motivo=$motivo");
}
?>
