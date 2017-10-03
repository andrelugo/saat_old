<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="Número do Código de Barras não preenchido";$barcode="";}
if (empty($erro)){
	$sql=mysql_query("select data_analize from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, então vamos ver se está pronto e se já não foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$analize=mysql_result($sql,0,"data_analize");	
		if (isset($analize)){
				$erro2="Este Barcode já foi Analizado em $analize impossivel excluir do sistema!";
		}
	}else{
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		Header("Location:frm_excluircp.php?erro=$erro2");
	}else{
		$erro="<font color='blue'>O barcode $barcode foi excluído do sistema!</font>";
		$sql="delete from cp where barcode='$barcode' and data_analize is null";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de REPROVAÇÃO! $sql ".mysql_error());		
		Header("Location:frm_excluircp.php?erro=$erro");
	}
}else{
	Header("Location:frm_excluircp.php?erro=$erro");
}
?>
