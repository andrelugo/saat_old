<?
// Se um or�amento j� possuir Pr�-Nota ent�o impedir a altera��o da defini��o!
// Barcode pode existir e n�o haver or�amento
require_once("sis_valida.php");
require_once("sis_conn.php");
$barcode=$_POST["txtBarcode"];
$orc_coletivo=$_POST["txtOrcColetivo"];
$orc_cliente=$_POST["txtOrcCliente"];
if ($barcode=="" && $orc_coletivo=="" && $orc_cliente==""){$erro="Nenhum campo foi preenchido para defini��o";}
if (!$_POST["cmbDefinicao"]==""){$definicao=$_POST["cmbDefinicao"];}else{$erro="Defini��o n�o preenchida!";$definicao="";}
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
// Caso seja Barcode ent�o!!!
// Talvez incluir barcode por barcode em um id unico para poder gerar pre-nota
if($barcode<>""){
	$sql=mysql_query("select cod from cp where cp.barcode='$barcode' order by cod") or die ("erro1".$sql.mysql_error());
	$row=mysql_num_rows($sql);
	if ($row==0){
		$erro2="O c�digo de barras $barcode n�o existe ou n�o foi devidamente cadastrado no sistema!";
	}else{
		$cp=mysql_result($sql,$row-1,"cod");
		$sql1="select cod,cod_orc_pre_nota as pre,(orc.qt * orc.valor) as valor from orc where cod_cp=$cp";
		$sqlO=mysql_db_query ("$bd",$sql1,$Link) or die ("$sql<br>".mysql_error());
		$rowO=mysql_num_rows($sqlO);
		if ($rowO==0){
			$erro2="N�o existem or�amentos para o Barcode $barcode codigo cp = $cp";
		}else{
			$ok=0;$err=0;$total=0;
			while($linha=mysql_fetch_array($sqlO)){
				$orc=$linha["cod"];// Numero do o�amento chave prim�ria de ORC
				$pre=$linha["pre"];// Numero da Pr�-Nota Nos casos de orc reprovado temos que gerar um numero inv�lido de pr�nota para ele n�o alterar futuramente // temos que mudar de NULL p/ 0
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
						$erro2="ERRO: Nenhum item foi definido. $rowO encontrado(s) j� possue(m) Pr�-Nota!";
					}else{
						$erro="Apenas $ok Itens de $rowO definidos para o barcode $barcode! Pois $err j� possuem inclusive Pr�-Nota!";
					}
				}
			}
		}
	}
}else{//Se n�o for pelo Barcode ent�o ver se � pelo Orc_Coletivo.
	if($orc_coletivo<>""){
		$res=mysql_query("select cod_orc_pre_nota as pre from orc where cod_orc_coletivo = $orc_coletivo order by cod_orc_pre_nota desc");
		$row=mysql_num_rows($res);
		if ($row==0){
			$erro2="O or�amento coletivo $orc_coletivo n�o existe ou n�o foi devidamente cadastrado no sistema!";
		}else{
			$res=mysql_query("select cod_orc_pre_nota as pre from orc where cod_orc_coletivo = $orc_coletivo and cod_orc_pre_nota is null");
			$row2=mysql_num_rows($res);
			if ($row2==0){
				$erro2="Imposs�vel redefinir!!!O or�amento coletivo $orc_coletivo j� possui Pr�-Nota!";
			}else{
				mysql_query("update orc set data_decisao=now(),cod_colab_decide=$id,cod_decisao=$definicao 
				where cod_orc_coletivo=$orc_coletivo and cod_orc_pre_nota is null");
				$erro="<FONT COLOR=BLUE>$row2 de $row Itens definidos para o Or�amento coletivo n� $orc_coletivo</FONT>";
			}
		}
	}else{//Se n�o for pelo Orc_coletivo ent�o ver se � pelo Numero de OR�AMENTO NO CLIENTE.
		if($orc_cliente<>""){
			$sql=mysql_query("select cod from cp where cp.orc_cliente='$orc_cliente' order by cod") or die ("erroORC_CLIENTE".$sql.mysql_error());
			$row=mysql_num_rows($sql);
			if ($row==0){
				$erro2="O or�amento $orc_cliente n�o existe ou n�o foi devidamente cadastrado no sistema!";
			}else{
				$cp=mysql_result($sql,$row-1,"cod");
				$sql1="select cod,cod_orc_pre_nota as pre,(orc.qt * orc.valor) as valor  from orc where cod_cp=$cp";
				$sqlO=mysql_db_query ("$bd",$sql1,$Link) or die ("$sql<br>".mysql_error());
				$rowO=mysql_num_rows($sqlO);
				if ($rowO==0){
					$erro2="N�o existem or�amentos para o Or�amento no cliente $orc_cliente codigo cp = $cp (IMPOSSIVEL OCORRER ESTE ERRO <BR><H1>AVISE AO ANALISTA DO SISTEMA</H1><BR>)";
				}else{
					$ok=0;$err=0;$total=0;
					while($linha=mysql_fetch_array($sqlO)){
						$orc=$linha["cod"];// Numero do o�amento chave prim�ria de ORC
						$pre=$linha["pre"];// Numero da Pr�-Nota Nos casos de orc reprovado temos que gerar um numero inv�lido de pr�nota para ele n�o alterar futuramente // temos que mudar de NULL p/ 0
						$total+=$linha["valor"];
						if($pre==NULL){
							mysql_query("update orc set data_decisao=now(),cod_colab_decide=$id,cod_decisao=$definicao where cod=$orc");
							$ok++;
						}else{
							$err++;
						}
						if($err==0){
							$totalF=number_format($total, 2, ',', '.');
							$erro="<FONT COLOR=BLUE>Todos os $rowO itens foram definidos para o or�amento $orc_cliente! Valor R$ $totalF</FONT>";
						}else{
							if($ok==0){
								$erro2="ERRO: Nenhum item foi definido. $rowO encontrado(s) j� possue(m) Pr�-Nota!";
							}else{
								$erro="Apenas $ok Itens de $rowO definidos para o orc�amento no cliente $orc_cliente! Pois $err j� possuem inclusive Pr�-Nota!";
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