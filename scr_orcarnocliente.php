<? // Este script deve marcar na tabela cp o n�mero do or�amento no cliente, a data da marca��o e o valor total do or�amento
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=$_POST["cp"];
$vtot=$_POST["vtot"];
$limite=$_POST["limite"];
if (empty($_POST["chkDigitar"])){
		if (empty($_POST["txtOrc"])){$erro="N�mero do Or�amento n�o preenchido!";}else{$orc=$_POST["txtOrc"];$erro="";}
		if (!empty($_POST["txtBarcode"])){
			$barcode=$_POST["txtBarcode"];
			$res=mysql_query("select cod from cp where barcode = $barcode");
			$row=mysql_num_rows($res);
			if($row==0){
				$erro="Barcode n�o encontrado!";
				$onde="";
			}else{	
				$erro="Consultando um Barcode espec�fico!";
				$cod=mysql_result($res,$row-1,"cod");
				$onde="&revisa=$cod";
			}
		}else{
			$onde="";
		}//busca por um barcode para revis�o
		
		if ($erro==""){
			mysql_query("update cp set orc_cliente='$orc',total_orc='$vtot',data_orc=now() where cod='$cp'");
			Header("Location:frm_orcarnocliente.php?limite=$limite&$onde");
		}else{	
			Header("Location:frm_orcarnocliente.php?erro=$erro&limite=$limite&$onde");
		}
}else{// SE FOR APENAS DIGITA��O NA TELA DO CLIENTE N�O GRAVAR NUMERO DE OR�AMENTO APENAS ORGANIZAR A POSI��O DELE NA FILA DOS J� DIGITADOS
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