<?
// Se um orçamento já possuir Pré-Nota então impedir a alteração da definição!
// Barcode pode existir e não haver orçamento
require_once("sis_valida.php");
require_once("sis_conn.php");
$barcode=$_POST["txtBarcode"];
$orc_coletivo=$_POST["txtOrcColetivo"];
$orc_cliente=$_POST["txtOrcCliente"];
if ($barcode=="" && $orc_coletivo=="" && $orc_cliente==""){$erro="Nenhum campo foi preenchido para definição";}
if (!$_POST["cmbDefinicao"]==""){$definicao=$_POST["cmbDefinicao"];}else{$erro="Definição não preenchida!";$definicao="";}
	$contOk=$_POST["contOk"];
	$contErro=$_POST["contErro"];
	$contTot=$_POST["contTot"];
	$contTot++;
	$pg=$_POST["pg"];
if (isset($erro)){
	$contErro++;
	Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&definicao=$definicao");
	exit;
}
// Caso seja Barcode então!!!
// Talvez incluir barcode por barcode em um id unico para poder gerar pre-nota
if($barcode<>""){
	$sql=mysql_query("select cod from cp where cp.barcode='$barcode' order by cod") or die ("erro1".$sql.mysql_error());
	$row=mysql_num_rows($sql);
	if ($row==0){
		$erro2="O código de barras $barcode não existe ou não foi devidamente cadastrado no sistema!";
	}else{
		$cp=mysql_result($sql,$row-1,"cod");
		$sql1="select cod,cod_orc_pre_nota as pre,(orc.qt * orc.valor) as valor from orc where cod_cp=$cp";
		$sqlO=mysql_db_query ("$bd",$sql1,$Link) or die ("$sql<br>".mysql_error());
		$rowO=mysql_num_rows($sqlO);
		if ($rowO==0){
			$erro2="Não existem orçamentos para o Barcode $barcode codigo cp = $cp";
		}else{
			$ok=0;$err=0;$total=0;
			while($linha=mysql_fetch_array($sqlO)){
				$orc=$linha["cod"];// Numero do oçamento chave primária de ORC
				$pre=$linha["pre"];// Numero da Pré-Nota Nos casos de orc reprovado temos que gerar um numero inválido de prénota para ele não alterar futuramente // temos que mudar de NULL p/ 0
				$total+=$linha["valor"];
				if($pre==NULL){
					mysql_query("update orc set data_decisao=now(),cod_colab_decide=$id,cod_decisao=$definicao where cod=$orc");
					$ok++;
				}else{
					$err++;
				}
				if($err==0){
					$totalF=number_format($total, 2, ',', '.');
					$erro="<FONT COLOR=BLUE>Todos os $rowO itens foram definidos para o barcode $barcode Valor R$ $totalF</FONT>";
				}else{
					if($ok==0){
						$erro2="ERRO: Nenhum item foi definido. $rowO encontrado(s) já possue(m) Pré-Nota!";
					}else{
						$erro="Apenas $ok Itens de $rowO definidos para o barcode $barcode! Pois $err já possuem inclusive Pré-Nota!";
					}
				}
			}
		}
	}
}else{//Se não for pelo Barcode então ver se é pelo Orc_Coletivo.
	if($orc_coletivo<>""){
		$res=mysql_query("select cod_orc_pre_nota as pre from orc where cod_orc_coletivo = $orc_coletivo order by cod_orc_pre_nota desc");
		$row=mysql_num_rows($res);
		if ($row==0){
			$erro2="O orçamento coletivo $orc_coletivo não existe ou não foi devidamente cadastrado no sistema!";
		}else{
			$res=mysql_query("select cod_orc_pre_nota as pre from orc where cod_orc_coletivo = $orc_coletivo and cod_orc_pre_nota is null");
			$row2=mysql_num_rows($res);
			if ($row2==0){
				$erro2="Impossível redefinir!!!O orçamento coletivo $orc_coletivo já possui Pré-Nota!";
			}else{
				mysql_query("update orc set data_decisao=now(),cod_colab_decide=$id,cod_decisao=$definicao 
				where cod_orc_coletivo=$orc_coletivo and cod_orc_pre_nota is null");
				$erro="<FONT COLOR=BLUE>$row2 de $row Itens definidos para o Orçamento coletivo nº $orc_coletivo</FONT>";
			}
		}
	}else{//Se não for pelo Orc_coletivo então ver se é pelo Numero de ORÇAMENTO NO CLIENTE.
		if($orc_cliente<>""){
			$sql=mysql_query("select cod from cp where cp.orc_cliente='$orc_cliente' order by cod") or die ("erroORC_CLIENTE".$sql.mysql_error());
			$row=mysql_num_rows($sql);
			if ($row==0){
				$erro2="O orçamento $orc_cliente não existe ou não foi devidamente cadastrado no sistema!";
			}else{
				$cp=mysql_result($sql,$row-1,"cod");
				$sql1="select cod,cod_orc_pre_nota as pre,(orc.qt * orc.valor) as valor  from orc where cod_cp=$cp";
				$sqlO=mysql_db_query ("$bd",$sql1,$Link) or die ("$sql<br>".mysql_error());
				$rowO=mysql_num_rows($sqlO);
				if ($rowO==0){
					$erro2="Não existem orçamentos para o Orçamento no cliente $orc_cliente codigo cp = $cp (IMPOSSIVEL OCORRER ESTE ERRO <BR><H1>AVISE AO ANALISTA DO SISTEMA</H1><BR>)";
				}else{
					$ok=0;$err=0;$total=0;
					while($linha=mysql_fetch_array($sqlO)){
						$orc=$linha["cod"];// Numero do oçamento chave primária de ORC
						$pre=$linha["pre"];// Numero da Pré-Nota Nos casos de orc reprovado temos que gerar um numero inválido de prénota para ele não alterar futuramente // temos que mudar de NULL p/ 0
						$total+=$linha["valor"];
						if($pre==NULL){
							mysql_query("update orc set data_decisao=now(),cod_colab_decide=$id,cod_decisao=$definicao where cod=$orc");
							$ok++;
						}else{
							$err++;
						}
						if($err==0){
							$totalF=number_format($total, 2, ',', '.');
							$erro="<FONT COLOR=BLUE>Todos os $rowO itens foram definidos para o orçamento $orc_cliente! Valor R$ $totalF</FONT>";
						}else{
							if($ok==0){
								$erro2="ERRO: Nenhum item foi definido. $rowO encontrado(s) já possue(m) Pré-Nota!";
							}else{
								$erro="Apenas $ok Itens de $rowO definidos para o orcçamento no cliente $orc_cliente! Pois $err já possuem inclusive Pré-Nota!";
							}
						}
					}
				}
			}
		}//fim orc_cliente
	}//fim else (orc_coletivo)
}
if (isset($erro2)){
	$contErro++;
	Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro2&definicao=$definicao");
}else{
	$contOk++;
	Header("Location:$pg?contTot=$contTot&contErro=$contErro&contOk=$contOk&erro=$erro&definicao=$definicao");
}
?>