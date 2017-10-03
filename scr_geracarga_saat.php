<? require_once("sis_valida.php");
require_once("sis_conn.php");
$arquivo='bkp/carga_saat.txt';
unlink($arquivo);
$ficheiro=fopen($arquivo,"w");
//----------------cabeçalho----------------------
$sqlbase="select cod_unidade_filial, descricao_filial from base";
$resbase=mysql_query($sqlbase);
$cod_filial = mysql_result($resbase,0,"cod_unidade_filial");
$desc_filial = mysql_result($resbase,0,"descricao_filial");
fwrite($ficheiro, "base\t$cod_filial\t$desc_filial\r\n");
//--------------fim cabeçalho---------------------
//Setando o flag de confirmação de carregamento na base saat p/ 0 onde houveram alterações desde o ultimo Up-load
//Todos registros que não receberam o feedback do servidor permanecem com este flag em zero forçando nova carga
$res=mysql_query("select ultima_carga_saat from base");
$ultimaCarga=mysql_result($res,0,"ultima_carga_saat");
mysql_query("update fechamento_reg set carregado=0 where ult_atual > base.ult_carga");
mysql_query("update cp set carregado=0 where ult_atual > base.ult_carga");
mysql_query("update pedido set carregado=0 where ult_atual > base.ult_carga");
mysql_query("update orc set carregado=0 where ult_atual > base.ult_carga");
//_________________________________________________________________________________________________________________

	// ***************************************LAY OUT DO ARQUIVO DE CARGA FECHAMENTO_REG**********
	$sqlf="select cod,descricao,registro,data_registro,tipo,qt_os,obs,data_abre,data_fecha,
	(select cpf from rh_user where rh_user.cod=cod_colab_abre) as cpf_colab_abre,
	(select cpf from rh_user where rh_user.cod=cod_colab_fecha) as cpf_colab_fecha,
	valor,cod_extrato_mo_envio
	from fechamento_reg
	where carregado=0";
	
	$resf=mysql_query($sqlf) or die ("Erro na consulta a tabela fechamento_reg ".mysql_error()."<br> $sqlf");
	while ($linhaf = mysql_fetch_array($resf)){
		$f0=$linhaf["cod"];
		$f1=$linhaf["registro"];
		$f2=$linhaf["descricao"];
		$f3=$linhaf["data_registro"];
		$f4=$linhaf["tipo"];
		$f5=$linhaf["qt_os"];
		$f6=$linhaf["obs"]; $f6=str_replace("	"," ",$f6);$f6=str_replace("\r\n"," ",$f6);
		$f7=$linhaf["data_abre"];
		$f8=$linhaf["data_fecha"];
		$f9=$linhaf["cpf_colab_abre"];
		$f10=$linhaf["cpf_colab_fecha"];
		$f11=$linhaf["valor"];

		$str2="f\t$f0\t$f1\t$f2\t$f3\t$f4\t$f5\t$f6\t$f7\t$f8\t$f9\t$f10\t$f11\r\n";
		fwrite($ficheiro, $str2);
	}
	// ************************************* FIM LAY OUT DO ARQUIVO DE CARGA FECHAMENTO_REG************************




// ***************************************LAY OUT DO ARQUIVO DE CARGA TABELA CP*************************************
$sql="SELECT cp.cod as cod_cp,
cod_posicao,cod_extrato_mo,valor_gar,
data_entra,cod_nf_entrada,barcode,data_barcode,filial,data_analize,serie,certificado,obs,data_pronto,data_sai,
defeito_reclamado,folha_cq,os_fornecedor,item_os_fornecedor,carencia,reprova_cq,orc_cliente,data_orc,total_orc,
aprp_orc,data_aprp_orc,cod_colab_aprp_orc,dig_orc,
(select cpf from rh_user where rh_user.cod=cp.cod_colab_entra) as cpf_colab_entra,
(select cpf from rh_user where rh_user.cod=cp.cod_tec) as cpf_tec,
(select cpf from rh_user where rh_user.cod=cp.cod_cq) as cpf_cq,
(select cpf from rh_user where rh_user.cod=cp.cod_colab_reg_sai) as cpf_colab_reg_sai,
(select cpf_cnpj from cliente where cliente.cod=cp.cod_cliente) as cnpj_cpf_cliente,
(select descricao from modelo where modelo.cod=cp.cod_modelo) as desc_modelo,
(select registro from fechamento_reg where fechamento_reg.cod=cp.cod_fechamento_reg) as desc_fechamento_reg,
itm_fechamento_reg,
cod_defeito,
cod_solucao,
cod_destino
FROM `cp`
where carregado=0";

$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro ao gerar arquivo de UP-LOAD $sql <br><h1>".mysql_error());		
$rowsos=mysql_num_rows($res);
$totrow=0;
while ($linha = mysql_fetch_array($res)){
	$cp=$linha["cod_cp"];
	$c1=$linha["barcode"];
	$c2=$linha["cod_extrato_mo"];// estes dados serão definidos pelo site adm2
	$c3=$linha["valor_gar"];// estes dados serão definidos pelo site adm2
	$c4=$linha["cod_posicao"];// estes dados serão definidos pelo site adm2 CARREGAR APENAS A PRIMEIRA VEZ
	$c5=$linha["cod_nf_entrada"];
	$c6=$linha["data_entra"];
	$c7=$linha["data_barcode"];
	$c8=$linha["filial"];
	$c9=$linha["data_analize"];
	$c10=$linha["serie"];
	$c11=$linha["certificado"];
	$c12=$linha["obs"]; $c12=str_replace("	"," ",$c12);$c12=str_replace("\r\n"," ",$c12);
	$c13=$linha["data_pronto"];
	$c14=$linha["data_sai"];
	$c15=$linha["defeito_reclamado"];
	$c16=$linha["folha_cq"];
	$c17=$linha["os_fornecedor"];
	$c18=$linha["item_os_fornecedor"];
	$c19=$linha["carencia"];
	$c20=$linha["reprova_cq"];
	$c21=$linha["orc_cliente"];
	$c22=$linha["data_orc"];
	$c23=$linha["total_orc"];
	$c24=$linha["cpf_colab_entra"];
	$c25=$linha["desc_modelo"];
	$c26=$linha["cpf_tec"];
	$c27=$linha["cpf_cq"];
	$c28=$linha["cpf_colab_reg_sai"];
	$c29=$linha["cnpj_cpf_cliente"];
	$c30=$linha["desc_fechamento_reg"];
	$c31=$linha["itm_fechamento_reg"];
	$c32=$linha["cod_defeito"];
	$c33=$linha["cod_solucao"];
	$c34=$linha["cod_destino"];
	$str="c\t$cp\t$c1\t$c2\t$c3\t$c4\t$c5\t$c6\t$c7\t$c8\t$c9\t$c10\t$c11\t$c12\t$c13\t$c14\t$c15\t$c16\t$c17\t$c18\t$c19\t$c20\t$c21\t$c22\t$c23\t$c24\t$c25\t$c26\t$c27\t$c28\t$c29\t$c30\t$c31\t$c32\t$c33\t$c34\r\n";
	fwrite($ficheiro, $str);
// ************************************FIM LAY OUT DO ARQUIVO DE CARGA TABELA CP*************************************
	
	// ***************************************LAY OUT DO ARQUIVO DE CARGA LINHA 2 PEDIDO DE PEÇAS EM GARANTIA**********
	$sql2="select peca.cod_fabrica as cod_peca,
	pedido.qt as qt_peca,
	pedido.data_cad as data_cad_pedido,
	pedido.cod_peca_defeito as cod_peca_defeito,
	pedido.cod_peca_servico as cod_peca_servico,
	(select cpf from rh_user where rh_user.cod=pedido.cod_colab) as cpf_tec_pedido
	from pedido 
	inner join peca on peca.cod = pedido.cod_peca
	where pedido.cod_cp = $cp";
	$res2=mysql_query($sql2) or die ("Erro na consulta a tabela de pedido de peças");
	while ($linha2 = mysql_fetch_array($res2)){
		$p1=$linha2["cod_peca"];
		$p2=$linha2["qt_peca"];
		$p3=$linha2["data_cad_pedido"];
		$p4=$linha2["cod_peca_defeito"];
		$p5=$linha2["cod_peca_servico"];
		$p6=$linha2["cpf_tec_pedido"];
		$str2="p\t$p1\t$p2\t$p3\t$p4\t$p5\t$p6\r\n";
		fwrite($ficheiro, $str2);
	}
	// ************************************* FIM LAY OUT DO ARQUIVO DE CARGA LINHA 2 PEDIDO DE PEÇAS EM GARANTIA************************
	/////linha 3 ORÇAMENTO//////
	$sql3="select peca.cod_fabrica as cod_peca,
	orc.qt as qt_peca,
	(select cpf from rh_user where rh_user.cod=orc.cod_colab_cad) as cpf_cad_ped,
	orc.data_cad as data_cad,
	cod_destino,cod_motivo,
	orc.valor as valor_orc,
	cod_decisao,data_decisao,
	(select cpf from rh_user where rh_user.cod=orc.cod_colab_decide) as cpf_decide,
	cod_orc_coletivo,cod_orc_individual,cod_orc_pre_nota,fechamento
	from orc
	inner join peca on peca.cod = orc.cod_peca
	where orc.cod_cp = $cp";
	$res3=mysql_query($sql3) or die ("Erro na consulta a tabela de ORÇAMENTOS");
	while ($linha3 = mysql_fetch_array($res3)){
		$o1=$linha3["cod_peca"];
		$o2=$linha3["qt_peca"];
		$o3=$linha3["cpf_cad_ped"];
		$o4=$linha3["data_cad"];
		$o5=$linha3["cod_destino"];
		$o6=$linha3["cod_motivo"];
		$o7=$linha3["valor_orc"];
		$o8=$linha3["cod_decisao"];
		$o9=$linha3["data_decisao"];
		$o10=$linha3["cpf_decide"];
		$o11=$linha3["cod_orc_coletivo"];
		$o12=$linha3["cod_orc_individual"];
		$o13=$linha3["cod_orc_pre_nota"];
		$o14=$linha3["fechamento"];
		$str3="o\t$o1\t$o2\t$o3\t$o4\t$o5\t$o6\t$o7\t$o8\t$o9\t$o10\t$o11\t$o12\t$o13\t$o14\r\n";
		fwrite($ficheiro, $str3);
	}
	/////FIM linha 3 ORÇAMENTO//////	
}
fclose($ficheiro);
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
<p align="center">Arquivo <? // print ($msg.$msgf)?> Gerado com sucesso na pasta: <? print($arquivo);?></p>
<p align="center"><? print($rowsos); ?> Ordens de Servi&ccedil;os encontradas </p>
<p></p>
<p align="center">Clique com o bot&atilde;o direito do mouse sobre <span class="style1">&quot;<a href="<? print($arquivo);?>">BAIXAR</a>&quot;</span> e escolha a op&ccedil;&atilde;o &quot;salvar link como...&quot; ou &quot;salvar destino como...&quot; </p>
</body> 
</html> 
<?
mysql_query("update base set ultima_carga_saat=now()");
?>