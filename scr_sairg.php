<?
// Podem ocorrer nesta pagina:
// um barcode n�o existir
// um barcode n�o estar pronto
// Um mesmo barcode ser baixado mais de uma vez
//
// EM 05/08/2006
// CASO SEJA UM BARCODE NOVA DATA ENT�O N�O LIBERAR UM PRODUTO CASO SEJA UM COMPUTADOR 
// E ESTEJA SEM O NUMERO DE ORDEM DE SERVI�OS DEFINIDA
// Esta consist�ncia n�o foi colocada em src_pronto porque poderia atrazar o desempenha de consertos... Os produtos que ainda n�o
// possuirem numero de �rie podem ser normalmente mebalado e aguardar sem maiores prejuisos a produ��o.
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="N�mero do C�digo de Barras n�o preenchido";$barcode="";}
if (!$_POST["cmbDestino"]==""){$destino=$_POST["cmbDestino"];}else{$erro="Destino n�o preenchido";$destino="";}
	$contOk=$_POST["contOk"];
	$contErro=$_POST["contErro"];
	$contTot=$_POST["contTot"];
	$contTot++;
	$pg=$_POST["pg"];
// se n�o houver erro de preenchimento do formulario ent�o ok sen�o volta ao form se realizar nada
if (empty($erro)){
	$sql=mysql_query("select cod,data_pronto,DATE_FORMAT(data_sai, '%d/%m/%Y as %k:%i:%s segundos') AS dd ,
	DATE_FORMAT(data_sai, '%d/%m/%Y') AS ddH ,cod_modelo, os_fornecedor from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, ent�o vamos ver se est� pronto e se j� n�o foi entregue!
	$row=mysql_num_rows($sql);
	if ($row>0){
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$os=mysql_result($sql,$row-1,"os_fornecedor");	
		$sai=mysql_result($sql,$row-1,"dd");	
		$saiH=mysql_result($sql,$row-1,"ddH");
		$hoje=date("d/m/Y");
		$modelo=mysql_result($sql,$row-1,"cod_modelo");	
		$cod=mysql_result($sql,$row-1,"cod");	
		//se estiver marcado como pronto ent�o vamos ver se j� n�o foi entregue, sen�o OK!
		if (isset($pronto)){
			//se a data da saida j� foi preenchida ent�o erro, pois j� foi entregue, sen�o OK!
			if (isset($sai)){
				if ($saiH==$hoje){
					$erro2="<font color	='blue'>Este produto foi Entregue HOJE as $sai </font>";
				}else{
					$erro2="Este Barcode foi Entregue em $sai fa�a uma consulta para ver o que houve!";
				}
			}//else{//Liberado em 25 de Setembro de 2005 pora liquidar acumulo de m�quinas ...
			//O fornecedor Nova Data deve levar em considera��o o registro de sa�das para pagar pela OS
				//Verificando se j� possui numero de Ordem de servi�o na f�brica... sen�o n�o liberar
//				if(empty($os) || $os=="0" || $os==""){
//					$sqlOs=mysql_query("select linha.cortesia as cortesia, modelo.cod_fornecedor as fornecedor from linha inner join modelo on modelo.linha = linha.cod inner join cp on cp.cod_modelo = modelo.cod where cp.barcode = '$barcode'");
//					$cortesia=mysql_result($sqlOs,0,"cortesia");
//					$cod_fornecedor=mysql_result($sqlOs,0,"fornecedor");
//					$sqlOsAuto=mysql_query("select os_auto from fornecedor where cod=$cod_fornecedor");
//					$os_auto=mysql_result($sqlOsAuto,0,"os_auto");// 3 � o tipo de fornecedor cuja OS s� � preenchida no final do m�s quando elaboramos o relat�rio em PDF
//					if($cortesia==0 && $os_auto<>3){
//						$erro2="Este produto aguarda o cadastro do n�mero de OS ou CHAMADO da f�brica. Avise ao Aux. Adm e posicione este produto em um PALLET com um aviso 'Aguardando O.S. f�brica'";
//					}
//				}
//			}
		}else{
			$erro2="Este produto ainda n�o foi marcado como PRONTO pelo t�cnico!";
		}
	}else{
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		$contErro++;
		Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro2&destino=$destino");
	}else{
		$contOk++;
		$erro="<font color='blue'>Sa�da do barcode $barcode realizada com sucesso!</font>";
//		$sql="update cp set data_sai=now(),cod_cq=$id,cod_destino=$destino where barcode='$barcode'";
		$sql="update cp set data_sai=now(),cod_cq=$id,cod_destino=$destino where cod='$cod'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inser��o da data Pronto! $sql ".mysql_error());		
		Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
	}
}else{
	$contErro++;
	Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
}
?>