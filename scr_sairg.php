<?
// Podem ocorrer nesta pagina:
// um barcode não existir
// um barcode não estar pronto
// Um mesmo barcode ser baixado mais de uma vez
//
// EM 05/08/2006
// CASO SEJA UM BARCODE NOVA DATA ENTÃO NÃO LIBERAR UM PRODUTO CASO SEJA UM COMPUTADOR 
// E ESTEJA SEM O NUMERO DE ORDEM DE SERVIÇOS DEFINIDA
// Esta consistência não foi colocada em src_pronto porque poderia atrazar o desempenha de consertos... Os produtos que ainda não
// possuirem numero de érie podem ser normalmente mebalado e aguardar sem maiores prejuisos a produção.
require_once("sis_valida.php");
require_once("sis_conn.php");
if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="Número do Código de Barras não preenchido";$barcode="";}
if (!$_POST["cmbDestino"]==""){$destino=$_POST["cmbDestino"];}else{$erro="Destino não preenchido";$destino="";}
	$contOk=$_POST["contOk"];
	$contErro=$_POST["contErro"];
	$contTot=$_POST["contTot"];
	$contTot++;
	$pg=$_POST["pg"];
// se não houver erro de preenchimento do formulario então ok senão volta ao form se realizar nada
if (empty($erro)){
	$sql=mysql_query("select cod,data_pronto,DATE_FORMAT(data_sai, '%d/%m/%Y as %k:%i:%s segundos') AS dd ,
	DATE_FORMAT(data_sai, '%d/%m/%Y') AS ddH ,cod_modelo, os_fornecedor from cp where cp.barcode='$barcode'") or die ("erro1".$sql.mysql_error());
	//se existir este barcode, então vamos ver se está pronto e se já não foi entregue!
	$row=mysql_num_rows($sql);
	if ($row>0){
		$pronto=mysql_result($sql,$row-1,"data_pronto");	
		$os=mysql_result($sql,$row-1,"os_fornecedor");	
		$sai=mysql_result($sql,$row-1,"dd");	
		$saiH=mysql_result($sql,$row-1,"ddH");
		$hoje=date("d/m/Y");
		$modelo=mysql_result($sql,$row-1,"cod_modelo");	
		$cod=mysql_result($sql,$row-1,"cod");	
		//se estiver marcado como pronto então vamos ver se já não foi entregue, senão OK!
		if (isset($pronto)){
			//se a data da saida já foi preenchida então erro, pois já foi entregue, senão OK!
			if (isset($sai)){
				if ($saiH==$hoje){
					$erro2="<font color	='blue'>Este produto foi Entregue HOJE as $sai </font>";
				}else{
					$erro2="Este Barcode foi Entregue em $sai faça uma consulta para ver o que houve!";
				}
			}//else{//Liberado em 25 de Setembro de 2005 pora liquidar acumulo de máquinas ...
			//O fornecedor Nova Data deve levar em consideração o registro de saídas para pagar pela OS
				//Verificando se já possui numero de Ordem de serviço na fábrica... senão não liberar
//				if(empty($os) || $os=="0" || $os==""){
//					$sqlOs=mysql_query("select linha.cortesia as cortesia, modelo.cod_fornecedor as fornecedor from linha inner join modelo on modelo.linha = linha.cod inner join cp on cp.cod_modelo = modelo.cod where cp.barcode = '$barcode'");
//					$cortesia=mysql_result($sqlOs,0,"cortesia");
//					$cod_fornecedor=mysql_result($sqlOs,0,"fornecedor");
//					$sqlOsAuto=mysql_query("select os_auto from fornecedor where cod=$cod_fornecedor");
//					$os_auto=mysql_result($sqlOsAuto,0,"os_auto");// 3 é o tipo de fornecedor cuja OS só é preenchida no final do mês quando elaboramos o relatório em PDF
//					if($cortesia==0 && $os_auto<>3){
//						$erro2="Este produto aguarda o cadastro do número de OS ou CHAMADO da fábrica. Avise ao Aux. Adm e posicione este produto em um PALLET com um aviso 'Aguardando O.S. fábrica'";
//					}
//				}
//			}
		}else{
			$erro2="Este produto ainda não foi marcado como PRONTO pelo técnico!";
		}
	}else{
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}
	if (isset($erro2)){
		$contErro++;
		Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro2&destino=$destino");
	}else{
		$contOk++;
		$erro="<font color='blue'>Saída do barcode $barcode realizada com sucesso!</font>";
//		$sql="update cp set data_sai=now(),cod_cq=$id,cod_destino=$destino where barcode='$barcode'";
		$sql="update cp set data_sai=now(),cod_cq=$id,cod_destino=$destino where cod='$cod'";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserção da data Pronto! $sql ".mysql_error());		
		Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
	}
}else{
	$contErro++;
	Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&destino=$destino");
}
?>