<?
// deve buscar as ordens de serviços geradas(data_entra) nos ultimos 10 dias ou liberadas(data_sai) nos ultimos 10 dias
//por que enviar-mos somente os ultimos 10 dias de os abertas, se um produto ficar 20 dias
//para ser finalizado, sua ordem de serviço fica sem fechamento no site
require_once("sis_valida.php");
require_once("sis_conn.php");
$arquivo='bkp/ordens.txt';
$dias=trim($_GET["dias"]);
$dtIni=trim($_GET["txtDtIni"]);
$dtFim=trim($_GET["txtDtFim"]);
$registro=trim($_GET["txtRegistro"]);

if (isset($_GET["chkFinalizar"])){$finalizar=$_GET["chkFinalizar"];$msgf="<br><font color=red><h2> SOMENTE FINALIZAÇÃO DE OS! </h2></font><br>";}else{$finalizar=0;$msgf="";}

//die($finalizar);

if (empty($_GET["rd"])){die("<h1>Selecione o tipo de consulta (Últimos dias,Período ou registro)");}else{$tpes=$_GET["rd"];}
$codFornecedor=$_GET["cmbFornecedor"];
if ($codFornecedor==0){die("<h1>Fornecedor não selecionado");}
// CLASURA WHERE///
if($tpes=="u"){//POR QUANTIDADE DE DIAS PASSADOS
	if ($dias==""){die("<h1>Quantidade de dias para pesquisa não informado!");}
	$where="((cp.data_analize between DATE_SUB(NOW(),INTERVAL $dias DAY) and DATE_SUB(NOW(),INTERVAL 1 DAY))
		or (data_sai between DATE_SUB(NOW(),INTERVAL $dias DAY) and DATE_SUB(NOW(),INTERVAL 1 DAY)))
		and modelo.cod_fornecedor=$codFornecedor";
	$msg="com $dias dias";
}
if($tpes=="p"){// SE FOR POR PERIODO
	if ($dtFim==""){die("<h1>Data final não informada!");}	if ($dtIni==""){die("<h1>Data inicial não informada!");}// data no formato inputada no formulário
	$diaI=substr($dtIni,0,2);	$mesI=substr($dtIni,3,2);	$anoI=substr($dtIni,6,4);
	$diaF=substr($dtFim,0,2);	$mesF=substr($dtFim,3,2);	$anoF=substr($dtFim,6,4);
	$dtIni2="$anoI-$mesI-$diaI";	$dtFim2="$anoF-$mesF-$diaF";// Data Formatada p/ AAAA-MM-DD
	
	$adtIni=explode(",",$dtIni);
	
	$where="((cp.data_sai BETWEEN '$dtIni2' and '$dtFim2')
	or (data_analize BETWEEN '$dtIni2' and '$dtFim2'))
	and modelo.cod_fornecedor=$codFornecedor";
	$msg="com data de análise ou saida entre $dtIni2 e $dtFim2";
}
if($tpes=="r"){//POR REGISTRO DE SAÍDAS
	if ($registro==""){die("<h1>Registro não informado!");}
	$sqlreg="select cod from fechamento_reg where registro like '$registro'";
	$resreg=mysql_query($sqlreg);
	$rowreg=mysql_num_rows($resreg);
	if($rowreg==0){
		die("<h1>Nenhum registo encontrado com a descrição $registro!");
	}else{
		if($rowreg>1){die("<h1>$rowreg registros encontrados para esta busca impossível gerar arquivo para mais de um registro com a mesma descrição!");}
	}
	$reg=mysql_result($resreg,0,"cod");
	$where="cp.cod_fechamento_reg=$reg and modelo.cod_fornecedor=$codFornecedor";
	$msg="com o regitro $registro";
}
// FIM CLAUSURA WHERE///


$sql="select * from fornecedor where cod=$codFornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$codTelecontrol=mysql_result($res,0,"cod_telecontrol");
$osauto=mysql_result($res,0,"os_auto");

if($codFornecedor==1){
$select="defeito.cod_britaniareclamado as reclama,defeito.cod_britaniaconstatado as constata,defeito.cod_britaniacausa as causa,solucao.cod_britania as soluciona";
}
if($codFornecedor==2){
$select="defeito.cod_aulik_reclamado as reclama,defeito.cod_aulik_constatado as constata,defeito.cod_aulik_causa as causa,solucao.cod_aulik as soluciona";
}

unlink($arquivo);
$sql="select cp.cod as cp,cp.os_fornecedor as os,cp.item_os_fornecedor as itmos,
DATE_FORMAT(cp.data_entra, '%Y-%m-%d') as dtentra,DATE_FORMAT(cp.data_sai, '%Y-%m-%d') as dtsai,
modelo.cod_produto_fornecedor as modelo,cp.serie as serie,cliente.cpf_cnpj as cnpj,
cliente.descricao as nome,cliente.telefone as telefone,cp.barcode as barcode,cliente.cod as codCliente,
$select
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join cliente on cliente.cod = cp.cod_cliente
inner join defeito on defeito.cod = cp.cod_defeito
inner join solucao on solucao.cod = cp.cod_solucao
where cod_extrato_mo is null and data_sai <> '00-00-0000' and ( $where )";

$sql="select cp.cod as cp,cp.os_fornecedor as os,cp.item_os_fornecedor as itmos,
DATE_FORMAT(cp.data_entra, '%Y-%m-%d') as dtentra,DATE_FORMAT(cp.data_sai, '%Y-%m-%d') as dtsai,
modelo.cod_produto_fornecedor as modelo,cp.serie as serie,cliente.cpf_cnpj as cnpj,
cliente.descricao as nome,cliente.telefone as telefone,cp.barcode as barcode,cliente.cod as codCliente,
$select
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join cliente on cliente.cod = cp.cod_cliente
inner join defeito on defeito.cod = cp.cod_defeito
inner join solucao on solucao.cod = cp.cod_solucao
where cod_extrato_mo is null and ( $where )";

//die($sql);

$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro ao gerar arquivo de UP-LOAD $sql <br><h1>".mysql_error());		
$rowsos=mysql_num_rows($res);
$ficheiro=fopen($arquivo,"w");
	$totrow=0;
	while ($linha = mysql_fetch_array($res)){
		if($finalizar==0){// se escolher a opção finalizar não é necessário pesquisar por peças. Somente setar o flag de contrução da seção de peças do arquivo $totrow=0; para zero.
			$sql2="select peca.cod_fabrica as peca,pedido.qt as qt,pedido.cod_peca_defeito as def,pedido.cod_peca_servico as ser
			from pedido
			inner join peca on peca.cod = pedido.cod_peca
			where pedido.cod_cp = $linha[cp]";
			$res2=mysql_db_query ("$bd",$sql2,$Link) or die ("Erro ao buscar item da tabela pedido $sql2 <br><h1>".mysql_error());		
			$totrow=mysql_num_rows($res2);
		}else{
			$totrow=0;
		}
		$count=0;
		$codCliente=$linha["codCliente"];
		// Se o cliente for o CBD então corta os dois primeiros digitos do Barcode que em junho de 2006 são sempre 0 para não 
		//erro ao fazer up-load no telecontrol que só aceita 10 digítos no campo Nota Fiscal
		if($codCliente==2){
			$barcode=$linha["barcode"];
			$nf=substr($barcode,2,11);
		}else{
			$nf=$linha["barcode"];
		}
		if($osauto==4){
			$os=$linha["barcode"];
			$itmOs="";
		}else{
			$os=$linha["os"];
			$itmOs=$linha["itmos"];
		}
		
		if ($totrow==0){//se houver não pedido de peças ou a variavel $totrow foi setada p/ zero pelo comando finalizar...
			$dtsai=$linha["dtsai"];
			$peca="";
			$qt="";
			$def="";
			$ser="";
			$str="$codTelecontrol\t52024932000178\t$os\t$itmOs\tR\t$linha[dtentra]\t$dtsai\t$linha[modelo]\t$linha[serie]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$nf\t$linha[dtentra]\t$linha[reclama]\t$linha[constata]\t$linha[causa]\t$peca\t$qt\t$def\t$ser\t\t\t\t\t\t\t\t$linha[soluciona]\r\n";
			fwrite($ficheiro, $str);
		}else{
			while ($linha2 = mysql_fetch_array($res2)){
				$count++;
				$peca=$linha2["peca"];
				$qt=$linha2["qt"];
				$def=$linha2["def"];
				$ser=$linha2["ser"];
				$dtsai="";//10/10/06
				//teste 10/10/06 após o looping para ver se finaliza os após este looping//if($count==$totrow){$dtsai=$linha["dtsai"];}else{$dtsai="";}//se for a ultima linha então finaliza da OS
				$str="$codTelecontrol\t52024932000178\t$os\t$itmOs\tR\t$linha[dtentra]\t$dtsai\t$linha[modelo]\t$linha[serie]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$nf\t$linha[dtentra]\t$linha[reclama]\t$linha[constata]\t$linha[causa]\t$peca\t$qt\t$def\t$ser\t\t\t\t\t\t\t\t$linha[soluciona]\r\n";
				fwrite($ficheiro, $str);
			}
			//Teste 10/10/06 para ver se ele fianliza a OS após rodar pedidos de peças...
			$dtsai=$linha["dtsai"];
			$peca="";
			$qt="";
			$def="";
			$ser="";
			$str="$codTelecontrol\t52024932000178\t$os\t$itmOs\tR\t$linha[dtentra]\t$dtsai\t$linha[modelo]\t$linha[serie]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$linha[cnpj]\t$linha[nome]\t$linha[telefone]\t$nf\t$linha[dtentra]\t$linha[reclama]\t$linha[constata]\t$linha[causa]\t$peca\t$qt\t$def\t$ser\t\t\t\t\t\t\t\t$linha[soluciona]\r\n";
			fwrite($ficheiro, $str);
			//fim teste 10/10/06
		}
	}
//acertar código do fornecedor p/ 11 qdo lenoxx e 3 qdo britania!!!!
fclose($ficheiro);

//////teste compactar
//
//if (!extension_loaded('zip')) {
//    echo( "Nao esta habilitado php_zip.dll, edite seu php.ini" );
//    //no php.ini descomente essa linha, se nao existir basta cria-la: extension=php_zip.dll
//    exit;    
//}
//
//$dir = dirname(bkp)."/";
//
//$zip = new Zip();
////$zip->open($dir . "ordens.zip", ZIP::CREATE);
//$zip->open($dir . "ordens.zip");
//$zip->addfile($dir . "ordens.txt", "nome_do_arquivo.extensao");
//$zip->addfile($dir . "ordens.txt");
//$zip->close();



///////teste fim
?>
<style type="text/css">
<!--
.style1 {font-size: 24px}
-->
</style>
<html> 

<head> 
<script language="JavaScript"> 
function MM_goToURL() { 
  for (var i=0; i< (MM_goToURL.arguments.length - 1); i+=100) 
    eval(MM_goToURL.arguments[i]+".location='"+MM_goToURL.arguments[i+1]+"'"); 
  document.MM_returnValue = false; 
} 
</script> 
</head> 
<body nLoad="MM_goToURL('parent','bkp/ordens.zip');return document.MM_returnValue"> 

<p align="center">Arquivo <? print ($msg.$msgf)?> Gerado com sucesso na pasta: <? print($arquivo);?></p>
<p align="center"><? print($rowsos); ?> Ordens de Servi&ccedil;os encontradas </p>
<p></p>
<p align="center">Clique com o bot&atilde;o direito do mouse sobre <span class="style1">&quot;<a href="<? print($arquivo);?>">BAIXAR</a>&quot;</span> e escolha a op&ccedil;&atilde;o &quot;salvar link como...&quot; ou &quot;salvar destino como...&quot; </p>
</body> 
</html> 