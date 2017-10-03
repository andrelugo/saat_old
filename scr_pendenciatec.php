<?
//Podem ocorrer nesta pagina:
//um barcode não existir
//um barcode estar pronto ou não estar analizado
//Um mesmo barcode ser baixado mais de uma vez
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="Número do Código de Barras não preenchido";$barcode="";}
if (!$_POST["cmbDestino"]==""){$destino=$_POST["cmbDestino"];}else{$erro="Destino não preenchido";$destino="";}
	$contOk=$_POST["contOk"];
	$contErro=$_POST["contErro"];
	$contTot=$_POST["contTot"];
	$contTot++;
// se não houver erro de preenchimento do formulario então ok senão volta ao form se realizar nada
if (empty($erro)){
	$sql=mysql_query("select cp.cod as cp, data_pronto,data_analize,DATE_FORMAT(data_sai, '%d/%m/%Y as %k:%i:%s segundos') AS dd ,
	DATE_FORMAT(data_sai, '%d/%m/%Y') AS ddH ,cod_modelo from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, então vamos ver se está pronto e se já não foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$cp=mysql_result($sql,$row-1,"cp");
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$analise=mysql_result($sql,$row-1,"data_analize");
		$sai=mysql_result($sql,$row-1,"dd");	
		$saiH=mysql_result($sql,$row-1,"ddH");
		$hoje=date("d/m/Y");
		$modelo=mysql_result($sql,$row-1,"cod_modelo");	
		//se estiver marcado como pronto então erro
		if (isset($pronto)){
			$erro2="<font color	='blue'>Este produto foi marcado como pronto! impossivel registrar saída como nâo Pronto</font>";
		}
		if (isset($sai)){
				if ($saiH==$hoje){
					$erro2="<font color	='blue'>Este produto foi Entregue HOJE as $sai </font>";
				}else{
					$erro2="Este Barcode foi Entregue em $sai faça uma consulta para ver o que houve!";
				}
		}
		if (empty($analise)){
			$erro2="<font color	='blue'>Este produto ainda não foi analisado por nenhum técnico! Impossivel registrar saída como nâo Pronto!</font>";
		}
	}else{
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		$contErro++;
		Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro2&destino=$destino");
	}else{
		$contOk++;
		$erro="<font color='blue'>Saída do barcode $barcode realizada com sucesso! CP $cp</font>";
		$sql="update cp set data_sai=now(),data_pronto='00-00-00',cod_cq=$id,cod_destino=$destino where cp.cod ='$cp'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserção da data Pronto! $sql ".mysql_error());		
		Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
	}
}else{
	$contErro++;
	Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
}
?>