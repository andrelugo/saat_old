<?
//Podem ocorrer nesta pagina:
//um barcode n�o existir
//um barcode estar pronto ou n�o estar analizado
//Um mesmo barcode ser baixado mais de uma vez
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="N�mero do C�digo de Barras n�o preenchido";$barcode="";}
if (!$_POST["cmbDestino"]==""){$destino=$_POST["cmbDestino"];}else{$erro="Destino n�o preenchido";$destino="";}
	$contOk=$_POST["contOk"];
	$contErro=$_POST["contErro"];
	$contTot=$_POST["contTot"];
	$contTot++;
// se n�o houver erro de preenchimento do formulario ent�o ok sen�o volta ao form se realizar nada
if (empty($erro)){
	$sql=mysql_query("select cp.cod as cp, data_pronto,data_analize,DATE_FORMAT(data_sai, '%d/%m/%Y as %k:%i:%s segundos') AS dd ,
	DATE_FORMAT(data_sai, '%d/%m/%Y') AS ddH ,cod_modelo from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, ent�o vamos ver se est� pronto e se j� n�o foi entregue!
	$row=mysql_num_rows($sql);
	if (!$row==0){
		$cp=mysql_result($sql,$row-1,"cp");
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$analise=mysql_result($sql,$row-1,"data_analize");
		$sai=mysql_result($sql,$row-1,"dd");	
		$saiH=mysql_result($sql,$row-1,"ddH");
		$hoje=date("d/m/Y");
		$modelo=mysql_result($sql,$row-1,"cod_modelo");	
		//se estiver marcado como pronto ent�o erro
		if (isset($pronto)){
			$erro2="<font color	='blue'>Este produto foi marcado como pronto! impossivel registrar sa�da como n�o Pronto</font>";
		}
		if (isset($sai)){
				if ($saiH==$hoje){
					$erro2="<font color	='blue'>Este produto foi Entregue HOJE as $sai </font>";
				}else{
					$erro2="Este Barcode foi Entregue em $sai fa�a uma consulta para ver o que houve!";
				}
		}
		if (empty($analise)){
			$erro2="<font color	='blue'>Este produto ainda n�o foi analisado por nenhum t�cnico! Impossivel registrar sa�da como n�o Pronto!</font>";
		}
	}else{
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		$contErro++;
		Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro2&destino=$destino");
	}else{
		$contOk++;
		$erro="<font color='blue'>Sa�da do barcode $barcode realizada com sucesso! CP $cp</font>";
		$sql="update cp set data_sai=now(),data_pronto='00-00-00',cod_cq=$id,cod_destino=$destino where cp.cod ='$cp'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inser��o da data Pronto! $sql ".mysql_error());		
		Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
	}
}else{
	$contErro++;
	Header("Location:con_pendenciatec.php?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
}
?>