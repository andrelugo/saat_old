<?
require_once("sis_valida.php");
require_once("sis_conn.php");
//require_once("includes/code128.php");
//$cod128 = new code128();

if (isset($_GET["cp"])){
	$cp=$_GET["cp"];$cond="where cp.cod=$cp";
}


$sql=mysql_query("select cp.cod as cp, modelo.descricao as modelo,modelo.marca as marca,cod_fechamento_reg,
fornecedor.cod as forn,barcode,date_format(data_barcode,'%d/%m/%y') as data_barcode,filial,
date_format(data_analize,'%d/%m/%y %H:%i') as data_analize,rh_user.nome as tecnico, cp.cod_tec as codtec,
defeito.descricao as defeito, solucao.descricao as solucao, serie, certificado, obs,data_pronto,cp.defeito_reclamado as defeitoR,
carencia, cod_colab_entra,date_format(data_entra,'%d/%m/%y %H:%i') as data_entra,orc_cliente,
date_format(data_pronto,'%d/%m/%y %H:%i') as data_pronto2,
date_format(data_sai,'%d/%m/%y %H:%i') as data_sai,cod_cq, cod_modelo,cod_defeito,cod_solucao,
day(data_barcode)as dia,month(data_barcode)as mes,date_format(data_barcode,'%y') as ano,os_fornecedor,
reprova_cq,item_os_fornecedor,cod_posicao,cod_destino,folha_cq
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
$defeitoR = mysql_result($sql,0,"defeitoR");
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
$dtPronto2 = mysql_result($sql,0,"data_pronto2");
$diab=mysql_result($sql,0,"dia");
$mesb=mysql_result($sql,0,"mes");
$anob=mysql_result($sql,0,"ano");
$osFornec=mysql_result($sql,0,"os_fornecedor");
$carencia=mysql_result($sql,0,"carencia");
$reprova=mysql_result($sql,0,"reprova_cq");
$codtec=mysql_result($sql,0,"codtec");
$orcCliente=mysql_result($sql,0,"orc_cliente");
$codRs=mysql_result($sql,0,"cod_fechamento_reg");
$fCq=mysql_result($sql,0,"folha_cq");
$itemOs=mysql_result($sql,0,"item_os_fornecedor");
$posicao=mysql_result($sql,0,"cod_posicao");
$destino=mysql_result($sql,0,"cod_destino");

?>

<html>
<head>
<charset="ISO-8859-1">

<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" >
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
</head>
<body>
<p align="center" class="style1">  Controle de Produção </a>
<table width="788" border="1" align="center">
    <tr bgcolor="#FDFCC8">
      <td width="150">Modelo:</td>
      <td width="230"><strong>
        <?if(isset($modelo)){print($modelo);}?>
      </strong></td>
      <td width="150">Marca:</td>
      <td width="230"><strong>
        <?if(isset($marca)){print($marca);}?>
      </strong></td>
    </tr>
    <tr bgcolor="#FDFCC8">
      <td>Defeito Reclamado </td>
      <td><strong>
        <? if(isset($defeitoR)){print($defeitoR);}?>
      </strong></td>
      <td>O.S. Fabrica : </td>
      <td><strong>&nbsp; 
        <?
if(isset($osFornec)){
	if ($osFornec<>"0"){
		print($osFornec);
		if(isset($itemOs)){
			if ($itemOs<>"0"){
				print("-".$itemOs);
			}
		}	
	}else{
		print("Não definida");
	}
}	
?>
</strong></td>
    </tr>
    <tr bgcolor="#FDFCC8">
      <td>Defeito Constatado:</td>
      <td><strong>
        <?if(isset($defeito)){print($defeito);}?>
      </strong></td>
      <td>S&eacute;rie:</td>
      <td><strong>
        <?if(isset($serie)){print($serie);}?>
      &nbsp;
      </strong></td>
    </tr>
    <tr bgcolor="#FDFCC8">
      <td>Solu&ccedil;&atilde;o:</td>
      <td><strong>
        <?if(isset($solucao)){print($solucao);}?>
      </strong></td>
      <td>Certificado:</td>
      <td><strong>
        <?if(isset($certificado)){print($certificado);}?>
      </strong></td>
    </tr>
    <tr bgcolor="#EFFEC0">
      <td>C&oacute;digo de Barras : </td>
      <td class="style1"><div align="center"><strong>
          <? if(isset($barcode)){
				print($barcode);//print $cod128->produceHTML("$barcode",0,10);
		   }?>
      </strong></div></td>
      <td>Filial:</td>
      <td><strong>
      <?if(isset($filial)){print($filial);}?>
      </strong></td>
  </tr>
    <tr bgcolor="#EFFEC0">
      <td>Data C&oacute;d. Barras : </td>
      <td><strong>
        <?if(isset($dtBarcode)){print($dtBarcode);}?>
      </strong></td>
      <td>Posi&ccedil;&atilde;o Atual:</td>
      <td><strong>
        <?
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
?>
      </strong></td>
  </tr>
<tr bgcolor="#EFFEC0">
<td>N&ordm; Or&ccedil;amento Cliente:</td>
<td><strong>
  <? if(isset($orcCliente)){print($orcCliente);}?>
</strong></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>  
    <tr>
      <td colspan="4"><div align="center">Controle de Produ&ccedil;&atilde;o :<strong>
         <? if(isset($cp)){print($cp);}
?>
      </strong><strong>
      </strong></div></td>
    </tr>
    <tr>
      <td>Entrada / Triagem:</td>
      <td>
        <strong>
        <?
if(isset($colabTria)){
	$sql=mysql_query("select nome from rh_user where cod = $colabTria")
	or die("Erro no Camando de pesquisa do nome do Triador".mysql_error());
	$colabTria=mysql_result($sql,0,"nome");
	print($colabTria);
}
?>
        </strong></td>
      <td>1 - Data da Entrada : </td>
      <td><strong>
      <?if(isset($dtTria)){print($dtTria);}?>
 &nbsp;     </strong></td>
    </tr>
    <tr>
      <td>T&eacute;cnico:</td>
      <td><strong>
      <?if(isset($tecnico)){print($tecnico);}?>
      </strong></td>
      <td>2 - Data da An&aacute;lise : </td>
      <td><strong>
      <?if(isset($dtAnalise)){print($dtAnalise);}?>&nbsp;
      </strong></td>
    </tr>
    <tr>
      <td>Car&ecirc;ncia:</td>
      <td><strong>
        <?
if(isset($carencia)){
	if($carencia==1){
		print("<h3><font color='#FF0000'>SIM</h3></font>");
	}else{
		print("NÂO");
	}
}
?>
      </strong></td>
      <td>3 - Data Pronto : </td>
      <td><strong>
        <? if(isset($dtPronto2)){print($dtPronto2);}?>
 &nbsp;     </strong></td>
    </tr>
    <tr>
      <td>Controle de Qualidade</td>
      <td><strong>
        <?
if(isset($colabCq)){
	$sql=mysql_query("select nome from rh_user where cod = $colabCq")
	or die("Erro no Camando de pesquisa do nome do Controler de Qualidade".mysql_error());
	$colabCq=mysql_result($sql,0,"nome");
	print($colabCq);
}

?>
 &nbsp;     </strong></td>
      <td>4 - Data CQ : </td>
      <td><strong>
        <?if(isset($dtSai)){print($dtSai);}?>
      </strong></td>
    </tr>
    <tr>
      <td>Folha CQ:</td>
      <td>
        <strong>
        <?if(isset($fCq)){print($fCq);}?>&nbsp;
</strong></td>
      <td>5 - Data da Sa&iacute;da : </td>
      <td><strong>
        <?if(isset($dtSai)){print($dtSai);}?>
</strong></td>
    </tr>
    <tr>
      <td>Registro de Sa&iacute;da : </td>
      <td><strong>
      <?
if(isset($codRs)){
	if($codRs==0){
		print("Indefinido (cod_registro=$codRs)");
	}else{
		$sql=mysql_query("select registro from fechamento_reg where cod = $codRs")
		or die("Erro no Camando de pesquisa do nome do registro de saída<br>".mysql_error());
		$registro=mysql_result($sql,0,"registro");
		print($registro);
	}
}

?>
 &nbsp;     </strong></td>
      <td>Destino:</td>
      <td><strong>
      <? if (isset($resDestino)){print($resDestino);}?>
 &nbsp;     </strong></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
	    Observa&ccedil;&otilde;es:
	  	<textarea name="txtObs" cols="90" rows="4" id="txtObs" readonly style="background-image:url(img/FUNDO.GIF)">
<? if(isset($obs)){print($obs);}?>
		</textarea>
      </td>
    </tr>
</table>
<hr>
<div align="center">
  <span class="style6">PEDIDOS EM GARANTIA </span></div>
<hr>
<table width="741" border="1" align="center" class="style1">
    <tr class="style3">
      <td width="104" class="style6"><span class="style11">C&oacute;digo</span></td>
      <td width="247" class="style6"><span class="style11">Descri&ccedil;&atilde;o</span></td>
      <td width="161" class="style6"><span class="style11">Defeito</span></td>
      <td width="201" class="style6"><span class="style11">Serviço</span></td>
      <td width="201" class="style6"><span class="style11">Data Pedido</span></td>
      <td width="201" class="style6"><span class="style11">Data Rec. Pedido</span></td>	  	  
    </tr> 
<?
$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,peca_defeito.descricao as defeito,
peca_servico.descricao as servico,pedido.data_cad as dtPed
from pedido inner join
peca_defeito on peca_defeito.cod = pedido.cod_peca_defeito inner join
peca_servico on peca_servico.cod = pedido.cod_peca_servico inner join
peca on peca.cod = pedido.cod_peca
where pedido.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Pedidos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr class='style11'><td>$linha[cod]</td> <td>$linha[descricao]</td> <td>$linha[defeito]</td> <td>$linha[servico]</td><td>$linha[dtPed]</td><td></td></tr>");
}
?>
</table>
<hr>
   <div align="center"><span class="style12">Orçamento </span>
       </div>
  
 </div>
   <table width="748" border="1" align="center" class="style12">
    <tr>
      <td width="79" class="style4 style13"><span class="style18">C&oacute;digo</span></td>
      <td width="368" class="style4 style13"><span class="style18">Descri&ccedil;&atilde;o</span></td>
      <td width="33" class="style4 style13"><span class="style18">Qt</span></td>
      <td width="79"><strong>Motivo</strong></td>
      <td width="74"><strong>Destino</strong></td>
      <td width="75" class="style4 style13"><span class="style18">Data do Or&ccedil;amento </span></td>
      <td width="75" class="style4 style13"><span class="style18">Descisão</span></td>
	  <td width="75" class="style4 style13"><span class="style18">Colaborador Cadastra</span></td>
    </tr> 
<?	  
$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,orc.qt,DATE_FORMAT(data_cad, '%d-%m-%y') AS dtCad,
DATE_FORMAT(data_decisao, '%d/%m/%Y as %k:%i%s') AS dtAp, orc.cod as codorc, orc_motivo.descricao as motivo, destino.descricao as destino,
orc_decisao.descricao as decisao, rh_user.nome as nome
from orc 
inner join peca on peca.cod = orc.cod_peca
left join orc_motivo on orc_motivo.cod = orc.cod_motivo
left join destino on destino.cod = orc.cod_destino
left join orc_decisao on orc_decisao.cod = orc.cod_decisao
left join rh_user on rh_user.cod = orc.cod_colab_cad
where orc.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Pedidos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
<td><? print ($linha["cod"]);?></td>
<td><? print ($linha["descricao"]);?></td>
<td><? print ($linha["qt"]);?></td>
<td><? print ($linha["motivo"]);?></td>
<td><? print ($linha["destino"]);?></td>
<td><? print ($linha["dtCad"]);?></td>
<td><? $des=$linha["decisao"] ;
if ($des=="" || empty($des) || $des==NULL){
	print("Aguar. Posição");
}else{
	print ($des);
}?></td>
<td><? print ($linha["nome"]);?></td>
</tr>
<?}?>
</table>

</body>
</html>
