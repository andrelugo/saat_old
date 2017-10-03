<script>
function envia(){ 
	document.form1.action='scr_pronto.php'; 
	if (confirm("Tem certeza que deseja liberar este produto para controle de qualidade?")){
		document.form1.submit(); 
	}
} 
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require_once("../includes/code128.php");
$cod128 = new code128();
if (isset($_GET["msg"])){
	$msg=$_GET["msg"];
}
if (isset($_GET["cp"])){
	$cp=$_GET["cp"];$cond="where cp.cod=$cp";
}
$sql=mysql_query("select cp.cod as cp, modelo.descricao as modelo,modelo.marca as marca,cod_fechamento_reg,
fornecedor.cod as forn,barcode,date_format(data_barcode,'%d/%m/%y') as data_barcode,filial,
date_format(data_analize,'%d/%m/%y %H:%i') as data_analize,rh_user.nome as tecnico, cp.cod_tec as codtec,
defeito.descricao as defeito, solucao.descricao as solucao, serie, certificado, obs,data_pronto,
carencia, cod_colab_entra,date_format(data_entra,'%d/%m/%y %H:%i') as data_entra,
date_format(data_sai,'%d/%m/%y %H:%i') as data_sai,cod_cq, cod_modelo,cod_defeito,cod_solucao,
day(data_barcode)as dia,month(data_barcode)as mes,date_format(data_barcode,'%y') as ano,os_fornecedor,
reprova_cq,item_os_fornecedor,cod_posicao,cod_destino,aprp_orc,date_format(data_aprp_orc,'%d/%m/%y %H:%i') as data_aprp_orc, folha_cq
from cp 
inner join modelo on modelo.cod = cp.cod_modelo
left join rh_user on cp.cod_tec = rh_user.cod
left join defeito on defeito.cod = cp.cod_defeito
left join solucao on solucao.cod = cp.cod_solucao
inner join fornecedor on fornecedor.cod = modelo.cod_fornecedor $cond") 
or die ("Erro na query de resultado de consulta do Controle de Produção Erro = ".mysql_error());
if (mysql_num_rows($sql)==0){
	die ("<h2>Registro não encontrado");	
}
$marca = mysql_result($sql,0,"marca");
$forn = mysql_result($sql,0,"forn");
$modelo = mysql_result($sql,0,"modelo");
$serie = mysql_result($sql,0,"serie");
$defeito = mysql_result($sql,0,"defeito");
$solucao = mysql_result($sql,0,"solucao");
$codModelo = mysql_result($sql,0,"cod_modelo");
$codDefeito = mysql_result($sql,0,"cod_defeito");
$codSolucao = mysql_result($sql,0,"cod_solucao");
$cp = mysql_result($sql,0,"cp");
$barcode = mysql_result($sql,0,"barcode");
$dtBarcode = mysql_result($sql,0,"data_barcode");
$filial = mysql_result($sql,0,"filial");
$certificado = mysql_result($sql,0,"certificado");
$obs = mysql_result($sql,0,"obs");
$tecnico = mysql_result($sql,0,"tecnico");
$dtAnalise = mysql_result($sql,0,"data_analize");
$dtSai = mysql_result($sql,0,"data_sai");
$dtTria = mysql_result($sql,0,"data_entra");
$colabTria = mysql_result($sql,0,"cod_colab_entra");
$colabCq = mysql_result($sql,0,"cod_cq");
$dtPronto = mysql_result($sql,0,"data_pronto");
$diab=mysql_result($sql,0,"dia");
$mesb=mysql_result($sql,0,"mes");
$anob=mysql_result($sql,0,"ano");
$osFornec=mysql_result($sql,0,"os_fornecedor");
$carencia=mysql_result($sql,0,"carencia");
$reprova=mysql_result($sql,0,"reprova_cq");
$codtec=mysql_result($sql,0,"codtec");
$codRs=mysql_result($sql,0,"cod_fechamento_reg");
$fCq=mysql_result($sql,0,"folha_cq");
$itemOs=mysql_result($sql,0,"item_os_fornecedor");
$posicao=mysql_result($sql,0,"cod_posicao");
$destino=mysql_result($sql,0,"cod_destino");
$aprp_orc=mysql_result($sql,0,"aprp_orc");
$data_aprp_orc=mysql_result($sql,0,"data_aprp_orc");
$adm=$_COOKIE["adm"];
$linkCp="";
if ($codtec==$id || $adm==1){
	if (empty($dtPronto)){
		$linkCp="<a href='frm_cp.php?cmdEnvia=Alterar&codModelo=$codModelo&serie=$serie&codDefeito=$codDefeito&codSolucao=$codSolucao&diaB=$diab&mesB=$mesb&anoB=$anob&barcode=$barcode&cp=$cp&filial=$filial&certificado=$certificado&obs=$obs'>";
		if ($tecnico<>""){
			$linkorc="<a href='frm_orcamento.php?cp=$cp&forn=$forn&msg=$msg'>";
			$linkped="<a href='frm_pedido.php?cp=$cp&forn=$forn&msg=$msg&modelo=$codModelo'>";
		}
	}
}
?>
<html>
<head>
<title>Resultado de inclusão e Consulta de Controle de Produção</title>
<style type="text/css">
<!--
.style1 {font-size: 24px}
.style3 {font-size: 18}
.style6 {
	color: #FF6666;
	font-weight: bold;
}
.style11 {color: #FF6666;font-size: 14px; font-weight: bold; }
.style12 {
	color: #FF9900;
	font-weight: bold;
}
.style13 {color: #FF9933}
.style16 {font-size: 14; font-weight: bold; }
.style18 {font-size: 14px; font-weight: bold; }
body {
	background-image: url(FUNDO.JPG);
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<p align="center" class="style1"> <?print($linkCp);print($msg);?> Controle de Produção </a>
<? 
if (isset($reprova)){
	if (empty($dtPronto)){
		$sql=mysql_query("select descricao from reprova where cod=$reprova")or die("Erro no Camando de consulta a tabela REPROVA scr_menucp.php".mysql_error());
		$motivo=mysql_result($sql,0,"descricao");
		print ("<h1><font color = 'red'> Produto reprovado pelo CONTROLE DE QUALIDADE. MOTIVO:".$motivo."</h1></font>");
	}
}
?>
<table width="701" border="1" align="center">
    <tr>
      <td width="140">Marca</td>
      <td width="163"><?if(isset($marca)){print($marca);}?></td>
      <td width="158">Controle de Produ&ccedil;&atilde;o</td>
      <td width="212"><? if(isset($cp)){print($cp);}
?></td>
    </tr>
    <tr>
      <td>Modelo</td>
      <td><?if(isset($modelo)){print($modelo);}?></td>
      <td>C&oacute;digo de Barras </td>
      <td><? if(isset($barcode)){print($barcode);print $cod128->produceHTML("$barcode",0,10);}?></td>
    </tr>
    <tr>
      <td>S&eacute;rie</td>
      <td><?if(isset($serie)){print($serie);}?></td>
      <td>Data C&oacute;d. Barras </td>
      <td><?if(isset($dtBarcode)){print($dtBarcode);}?></td>
    </tr>
    <tr>
      <td>Defeito</td>
      <td><?if(isset($defeito)){print($defeito);}?></td>
      <td>Filial</td>
      <td><?if(isset($filial)){print($filial);}?></td>
    </tr>
    <tr>
      <td>Solu&ccedil;&atilde;o</td>
      <td><?if(isset($solucao)){print($solucao);}?></td>
      <td>Certificado</td>
      <td><?if(isset($certificado)){print($certificado);}?></td>
    </tr>
    <tr>
      <td>Posi&ccedil;&atilde;o Atual</td>
      <td><?
if(isset($destino)){
	$sql=mysql_query("select descricao from destino where cod = $destino")
	or die("Erro no Camando de pesquisa do nome destino".mysql_error());
	$resDestino=mysql_result($sql,0,"descricao");
	print($resDestino);
}else{
	if(isset($posicao)){
		$sql=mysql_query("select descricao from posicao where cod = $posicao")
		or die("Erro no Camando de pesquisa da posicao".mysql_error());
		$res=mysql_result($sql,0,"descricao");
		print($res);
	}
}
?></td>
      <td>O.S. Fabrica </td>
      <td><?if(isset($osFornec)){print($osFornec);}?>
        -
      <?if(isset($itemOs)){print($itemOs);}?></td>
    </tr>
    <tr>
      <td>Triagem</td>
      <td>
<?
if(isset($colabTria)){
	$sql=mysql_query("select nome from rh_user where cod = $colabTria")
	or die("Erro no Camando de pesquisa do nome do Triador".mysql_error());
	$colabTria=mysql_result($sql,0,"nome");
	print($colabTria);
}
?></td>
      <td>Data da Triagem </td>
      <td><?if(isset($dtTria)){print($dtTria);}?></td>
    </tr>
    <tr>
      <td>T&eacute;cnico</td>
      <td><?if(isset($tecnico)){print($tecnico);}?></td>
      <td>Data da An&aacute;lise </td>
      <td><?if(isset($dtAnalise)){print($dtAnalise);}?></td>
    </tr>
    <tr>
      <td>Controle de Qualidade</td>
      <td>
<?
if(isset($colabCq)){
	$sql=mysql_query("select nome from rh_user where cod = $colabCq")
	or die("Erro no Camando de pesquisa do nome do Controler de Qualidade".mysql_error());
	$colabCq=mysql_result($sql,0,"nome");
	print($colabCq);
}

?></td>
      <td>Data da Sa&iacute;da </td>
      <td><?if(isset($dtSai)){print($dtSai);}?></td>
    </tr>
    <tr>
      <td>Registro de Sa&iacute;da </td>
      <td><?
if(isset($codRs)){
	$sql=mysql_query("select registro from fechamento_reg where cod = $codRs")
	or die("Erro no Camando de pesquisa do nome do registro de saída<br>".mysql_error());
	$registro=mysql_result($sql,0,"registro");
	print($registro);
}

?></td>
      <td>Destino</td>
      <td><? if (isset($resDestino)){print($resDestino);}?></td>
    </tr>
	<tr>
      <td>Car&ecirc;ncia</td>
      <td><?
if(isset($carencia)){
	if($carencia==1){
		print("<h3><font color='#FF0000'>SIM</h3></font>");
	}else{
		print("NÂO");
	}
}
?></td>
      <td>Folha CQ</td>
      <td><?if(isset($fCq)){print($fCq);}?></td>
    </tr>
    <tr>
      <td colspan="4"><p>Observa&ccedil;&otilde;es:<?if(isset($obs)){print($obs);}?></p>
      <p>&nbsp;</p></td>
    </tr>
</table>
<?
//Variavel nalter (Não ALTERar) vem do formulário mnu_cp.php e tem por objetivo mostrar se um produto 
//está pronto
//é de outro técnico
//ou se foi prevovado pelo CQ!
//e liberar este produto para alteração somente se for o mesmo técnico quem estiver acessando!
if (isset($_GET["nalter"])){
	$nalter=$_GET["nalter"];
	print ("<h1><font color='red'>".$nalter."</h1></font>");
}
// Esta parte do código vai habilitar as alterações no controle de produção somente se for o mesmo técnico ou um administrador
//e o produto ainda não tiver sido marcado como pronto pelo técnico
if ($tecnico<>""){ 
	if ($linkCp<>""){?>
		<form name="form1" method="post" action="scr_pronto.php" onsubmit="javascript:return false;">
			<input name="Submit" type="submit" value="PRODUTO LIBERADO PARA CONTROLE DE QUALIDADE" onclick="envia()">
			<input type="hidden" name="cp" value="<?print($cp);?>">
		</form>
<?
	}
}?>
<hr>
<div align="center">
  <span class="style6"><? if(isset($linkped)){print($linkped);?>PEDIDOS EM GARANTIA </span></div><? print("</A>");}?>
<hr>
<table width="741" border="1" align="center" class="style1">
    <tr class="style3">
      <td width="104" class="style6"><span class="style11">C&oacute;digo</span></td>
      <td width="247" class="style6"><span class="style11">Descri&ccedil;&atilde;o</span></td>
      <td width="161" class="style6"><span class="style11">Defeito</span></td>
      <td width="201" class="style6"><span class="style11">Serviço</span></td>
    </tr> 
<?
$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,peca_defeito.descricao as defeito,
peca_servico.descricao as servico
from pedido inner join
peca_defeito on peca_defeito.cod = pedido.cod_peca_defeito inner join
peca_servico on peca_servico.cod = pedido.cod_peca_servico inner join
peca on peca.cod = pedido.cod_peca
where pedido.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela APedidos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr class='style11'><td>$linha[cod]</td> <td>$linha[descricao]</td> <td>$linha[defeito]</td> <td>$linha[servico]</td></tr>");
}
?>
</table>
<hr>
<? 
if(isset($aprp_orc)){
		if ($aprp_orc==1){
			$msgaprp="Orçamento APROVADO em $data_aprp_orc!";
		}
		if ($aprp_orc==0){
			$msgaprp="Orçamento REPROVADO em $data_aprp_orc!";
		}
		print("<h1><center>".$msgaprp."</h1></center>");
}else{
?>   
		<div align="center" class="style12 style13"><span class="style6">
		<? if(isset($linkorc)){print($linkorc);?> </span>OR&Ccedil;AMENTO</div> <? print("</a>");}
}
?>
<hr>
<table width="748" border="1" align="center" class="style12">
    <tr>
      <td width="79" class="style4 style13"><span class="style18">C&oacute;digo</span></td>
      <td width="368" class="style4 style13"><span class="style18">Descri&ccedil;&atilde;o</span></td>
      <td width="79"><strong>Motivo</strong></td>
      <td width="74"><strong>Destino</strong></td>
      <td width="33" class="style4 style13"><span class="style18">Qt</span></td>
      <td width="75" class="style4 style13"><span class="style18">Data do Or&ccedil;amento </span></td>
    </tr> 
<?	  
$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,orc.qt,DATE_FORMAT(data_cad, '%d-%m-%y') AS dtCad,
DATE_FORMAT(orc.data_decisao, '%d/%m/%Y as %k:%i%s') AS dtAp, orc.cod as codorc, orc_motivo.descricao as motivo, destino.descricao as destino
from orc 
inner join peca on peca.cod = orc.cod_peca
left join orc_motivo on orc_motivo.cod = orc.cod_motivo
left join destino on destino.cod = orc.cod_destino
where orc.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Pedidos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
<td><?print ($linha["cod"]);?></td>
<td><?print ($linha["descricao"]);?></td>
<td><?print ($linha["motivo"]);?></td>
<td><?print ($linha["destino"]);?></td>
<td><?print ($linha["qt"]);?></td>
<td><?print ($linha["dtCad"]);?></td>
</tr>
<?}?>
</table>

</body>
</html>
