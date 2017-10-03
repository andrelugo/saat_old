<? // Este script deve marcar na tabela cp o número do orçamento no cliente, a data da marcação e o valor total do orçamento
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=$_POST["cp"];
$vtot=$_POST["vtot"];
$limite=$_POST["limite"];
if (empty($_POST["chkDigitar"])){
		if (empty($_POST["txtOrc"])){$erro="Número do Orçamento não preenchido!";}else{$orc=$_POST["txtOrc"];$erro="";}
		if (!empty($_POST["txtBarcode"])){
			$barcode=$_POST["txtBarcode"];
			$res=mysql_query("select cod from cp where barcode = $barcode");
			$row=mysql_num_rows($res);
			if($row==0){
				$erro="Barcode não encontrado!";
				$onde="";
			}else{	
				$erro="Consultando um Barcode específico!";
				$cod=mysql_result($res,$row-1,"cod");
				$onde="&revisa=$cod";
			}
		}else{
			$onde="";
		}//busca por um barcode para revisão
		
		if ($erro==""){
			mysql_query("update cp set orc_cliente='$orc',total_orc='$vtot',data_orc=now() where cod='$cp'");
			Header("Location:frm_orcarnocliente.php?limite=$limite&$onde");
		}else{	
			Header("Location:frm_orcarnocliente.php?erro=$erro&limite=$limite&$onde");
		}
}else{// SE FOR APENAS DIGITAÇÃO NA TELA DO CLIENTE NÃO GRAVAR NUMERO DE ORÇAMENTO APENAS ORGANIZAR A POSIÇÃO DELE NA FILA DOS JÁ DIGITADOS
		$res=mysql_query("SELECT max( dig_orc ) AS orc_dig
		FROM cp
		INNER JOIN modelo ON modelo.cod = cp.cod_modelo
		INNER JOIN linha ON linha.cod = modelo.linha
		WHERE cp.orc_cliente IS NULL
		AND linha.orc_coletivo =0
		AND DATEDIFF(now(),data_analize)<>0") or die(mysql_error());
		$orcdig=mysql_result($res,0,"orc_dig");
		$orcdig++;
		mysql_query("update cp set dig_orc = $orcdig where cod = $cp")or die(mysql_error());
		
		Header("Location:frm_orcarnocliente.php?erro=&limite=$limite&digitar=1");
}
?>