<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="N�mero do C�digo de Barras n�o preenchido";$barcode="";}
if (empty($erro)){
	$sql=mysql_query("select data_analize from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, ent�o vamos ver se est� pronto e se j� n�o foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$analize=mysql_result($sql,0,"data_analize");	
		if (isset($analize)){
				$erro2="Este Barcode j� foi Analizado em $analize impossivel excluir do sistema!";
		}
	}else{
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_excluircp.php?erro=$erro2");
	}else{
		$erro="<font color='blue'>O barcode $barcode foi exclu�do do sistema!</font>";
		$sql="delete from cp where barcode='$barcode' and data_analize is null";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVA��O! $sql ".mysql_error());		
		Header("Location:frm_excluircp.php?erro=$erro");
	}
}else{
	Header("Location:frm_excluircp.php?erro=$erro");
}
?>
