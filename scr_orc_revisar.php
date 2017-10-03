<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$item=$_GET["item"];
$valor1=$_GET["txtValor"];
$valor=sprintf(str_replace(',','.',$valor1));// SUBSTITUI  VIRGULA POR PONTO . ,
$barcode=$_GET["barcode"];
// se já possuir pré notas não permitir nenhuma alteração
	$res=mysql_query("select cod_orc_pre_nota 
	from orc 
	inner join peca on peca.cod = orc.cod_peca
	where orc.cod = $item");
	$pre=mysql_result($res,0,"cod_orc_pre_nota");
	if(!empty($pre)){
		die("<center><h1> Este barcode já possui pré-notas. IMPOSSÍVEL SALVAR! </h1>");
	}
// 
if ($valor=="t" || $valor=="T"){
	$sql=mysql_query("update orc inner join peca on peca.cod = orc.cod_peca set valor = peca.venda where orc.cod = $item") or die ("erro1".$sql.mysql_error());
}else{
	$res=mysql_query("select custo,ipi,simples,lucro,perda,cpmf,icms,dif_icms from peca inner join orc on orc.cod_peca = peca.cod where orc.cod = $item");
	$pp=mysql_result($res,0,"custo");
	$ipi=mysql_result($res,0,"ipi")/100;
	$simples=mysql_result($res,0,"simples")/100;
	$lucro=mysql_result($res,0,"lucro")/100;	
	$perda=mysql_result($res,0,"perda")/100;
	$cpmf=mysql_result($res,0,"cpmf")/100;
	$icms=mysql_result($res,0,"icms")/100;
	$difIcms=mysql_result($res,0,"dif_icms")/100;

	$custoTot=(($pp+($ipi*$pp))*(1+$difIcms))*(1+$cpmf);
	$pm=$custoTot/(1-($icms+$simples+$perda));
	$pv=$custoTot/(1-($lucro+$icms+$simples+$perda));
	//die("Custo total $custoTot<br>preço minimo $pm<br>Valor informado $valor <br>Custo $pp<br>Venda $pv<br>ipi $ipi<br>	simples $simples<br>
	//lucro $lucro<br>perda $perda<br>cpmf $cpmf<br>icms $icms<br>dificms $difIcms");
	$res=mysql_query("select orc_lucro_zero, linha.descricao as linha
	from linha 
	inner join modelo on modelo.linha = linha.cod
	inner join cp on cp.cod_modelo = modelo.cod
	inner join orc on orc.cod_cp = cp.cod
	where orc.cod=$item")or die(mysql_error());
	$olz=mysql_result($res,0,"orc_lucro_zero");
	$linha=mysql_result($res,0,"linha");
	if($olz==1){// se a linha do produto permitir venda a custo zero para o cliente então permitirque o preço seja o de custo
		if($valor<$pp){
			die(" Produto da linha : $linha - Permite a venda a preço de tabela da fábrica mesmo sem a cobrança de impostos.
			Impossível vender um item por preço inferior ao TABELA DA FABRICA<br>
			O preço pago por esta peça é $pp<br>
			<font color=red>O preço minimo de venda com os impostos é de $pm</font><br>
			O preço normal venda é $pv (já com impostos)");
		}else{
			$sql=mysql_query("update orc set valor = $valor where cod = $item") or die ("erro1".$sql.mysql_error());
		}

	}else{
		if($valor<$pm){
			die("Impossível vender um item por preço inferior ao CUSTO<br>
			O preço pago por esta peça é $pp<br>
			<font color=red>O preço minimo de venda com os impostos é de $pm</font><br>
			O preço normal de venda é $pv (já com impostos)");
		}else{
			$sql=mysql_query("update orc set valor = $valor where cod = $item") or die ("erro1".$sql.mysql_error());
		}
	}
}
	Header("Location:frm_orc_revisar.php?txtBarcode=$barcode");
?>
