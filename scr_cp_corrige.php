<? require_once("sis_valida.php");
require_once("sis_conn.php");
$fornecedor=1;
$max_item_os=99;
$resbusca=mysql_query("SELECT cp.cod as cod,barcode, data_sai,serie,cod_tec,obs FROM `cp` inner join modelo on modelo.cod = cp.cod_modelo 
WHERE `os_fornecedor` IS NULL and data_analize is not null and modelo.cod_fornecedor = 1");
$row=mysql_num_rows($resbusca);
$corrigida=0;
$inventario=0;
while ($linha=mysql_fetch_array($resbusca)){
	$cp=$linha["cod"];
	$barcode=$linha["barcode"];
	$dataSai=$linha["data_sai"];
	$serie=$linha["serie"];
	$tec=$linha["cod_tec"];
	$obs=$linha["obs"];
	if($dataSai=="0000-00-00 00:00:00"){
		if ($serie==NULL){
			echo("CP $cp e barcode $barcode , serie $serie e técnico $tec FOI EXCLUIDO<br>");
			mysql_query("delete from cp where cp.cod=$cp");
		}else{
			$inventario++;
			echo("CP $cp não recebeu OS seu barcode é $barcode - Data de saída = $dataSai<br>");
			mysql_query("update cp set os_fornecedor='Cancelada' where cp.cod=$cp");
		}
	}else{
		$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
		$os=mysql_result($sql,0,"os");// Encontra a menor OS disponivel na tabela de OS
		$sql=mysql_query("select max(item_os_fornecedor) as item from cp where os_fornecedor = $os")or die("Selecionando Item".mysql_error());
		$item=mysql_result($sql,0,"item");// Encontra o maoir item cadastrado p/ a OS encontrada acima
		
		if ($item==NULL){// Se a pesquisa de item retorna NULL e o fornecedor utiliza itens então esta é o primeiro item da OS a ser utilizada
			$item=0;
		}else{
			if ($item==$max_item_os){// Se o maior item cadastrado para esta ordem é igual ao limite de itens por OS deste fornecedor então reseta Item 
				$item=0;
				$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os=$os")or die(mysql_error());
	
				$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
				$os=mysql_result($sql,0,"os");// Encontra a menor OS disponivel na tabela de OS
			}else{
				$item++;
			}
		}
		$obs=$obs."Esta os Foi corrigida através do script de correção de registros sem numero de OS do fabricante!";
		mysql_query("update cp set os_fornecedor=$os,item_os_fornecedor=$item , data_analize = data_pronto, obs='$obs' where cp.cod=$cp");
		$corrigida++;
		echo("CP $cp recebeu a OS $os-$item seu barcode é $barcode - Data de saída = $dataSai<br>");
	}
}// fim looping
echo("<h2> Encontrados $row OS e corrigidas $corrigida - $inventario estavam com data = 00/00/0000!<br>");
?>