<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$barcode=$_GET["txtBarcode"];
require_once("includes/code128.php");
$cod128 = new code128();
$sql="select orc_coletivo,cp.cod as cp
from linha inner 
join modelo on modelo.linha = linha.cod inner 
join cp on cp.cod_modelo = modelo.cod
where cp.barcode = '$barcode'";
$resL=mysql_query($sql);
$coletivo=mysql_result($resL,0,"orc_coletivo");
if($coletivo==1){
	$cp=mysql_result($resL,0,"cp");
	$sqlnOrc=mysql_query("select cod_orc_coletivo from orc where cod_cp = $cp");
	$nOrc=mysql_result($sqlnOrc,0,"cod_orc_coletivo");
	if($nOrc==NULL){
		$msg="<font color=red>Atenção este orçamento é do tipo coletivo!!! e como ainda não foi gerado, será reimpresso. CUIDADO!!!</font>";
	}else{
		$msg="<font color=red>2ª Via do Orçamento Coletivo Nº $nOrc</font>";
	}
}else{
	$res=mysql_query("select data_imprime_individual,day(data_imprime_individual) as dia,month(data_imprime_individual) as mes,year(data_imprime_individual) as ano, cod_orc_individual from orc inner join cp on cp.cod = orc.cod_cp where cp.barcode = '$barcode'")or die(mysql_error());
	$rows=mysql_num_rows($res);
	if ($rows==0){
		die("<h1>Não há itens orçados para o barcode $barcode");
	}else{
		$dataImp=mysql_result($res,0,"data_imprime_individual");
		$orc=mysql_result($res,0,"cod_orc_individual");
		$dia=mysql_result($res,0,"dia");
		$mes=mysql_result($res,0,"mes");
		$ano=mysql_result($res,0,"ano");
		$diah=date("d");
		$mesh=date("m");
		$anoh=date("Y");
		if ($orc==NULL){
			$msg="Orçamento Individual nº $orc<br><h1><font color=red>Protocolo de controle de impressão não gerado!</font></h1>";
		}else{
			if($dia<>$diah || $mes<>$mesh || $ano<>$anoh){
				$msg="Orçamento Individual nº $orc<br><h1><font color=red>Impresso em $dia/$mes/$ano</font></h1>";
			}else{
				$msg="Orçamento Individual nº $orc";
			}
		}
		
	}
}
?>
<html>
<head>
<title></title>
<link href="" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>
<body>
<div align="center">
  <p class="style1"><img src="img/timbre1.bmp"><br>
  <? print ($msg);?></p>
  <hr size="5" color="#000000" width="800">
    <span class="style1">PRODUTO</span>
  <hr size="5" color="#000000" width="800">
<?
$sql="select cp.cod as cp,barcode,date_format(data_barcode,'%d/%m/%y') as data_barcode,
filial,modelo.marca as marca,modelo.descricao as modelo,serie,cod_produto_cliente,
defeito.descricao as defeito, solucao.descricao as solucao, rh_user.nome as tecnico
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner
join orc on orc.cod_cp = cp.cod inner
join defeito on defeito.cod = cp.cod_defeito inner
join solucao on solucao.cod = cp.cod_solucao inner
join rh_user on rh_user.cod = cp.cod_tec
where cp.barcode='$barcode'
group by barcode,filial,marca,modelo,serie,cod_produto_cliente,cp
order by cod_produto_cliente";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$marca=mysql_result($res,0,"marca");
$filial=mysql_result($res,0,"filial");
$fornecedor=mysql_result($res,0,"cod_produto_cliente");
$tecnico=mysql_result($res,0,"tecnico");
$defeito=mysql_result($res,0,"defeito");
$modelo=mysql_result($res,0,"modelo");
$dataRg=mysql_result($res,0,"data_barcode");
$serie=mysql_result($res,0,"serie");
$solucao=mysql_result($res,0,"solucao");
?> 
  <table width="601" border="0">
    <tr>
      <td width="110" height="43" class="style1">Barcode</td>
      <td width="201"><? print($barcode);?></td>
      <td colspan="2"><?  if(isset($barcode)){print $cod128->produceHTML("$barcode",0,30);}?></td>
    </tr>
    <tr>
      <td class="style1">Marca</td>
      <td><? print($marca);?></td>
      <td width="68" class="style1">Modelo</td>
      <td width="194"><? print($modelo);?></td>
    </tr>
    <tr>
      <td class="style1">Filial</td>
      <td><? print($filial);?></td>
      <td class="style1">Data RG </td>
      <td><? print($dataRg);?></td>
    </tr>
    <tr>
      <td class="style1">Fornec.-Produto</td>
      <td><? print($fornecedor);?></td>
      <td class="style1">S&eacute;rie</td>
      <td><? print($serie);?></td>
    </tr>
	    <tr>
      <td class="style1">Defeito Const. </td>
      <td><? print($defeito);?></td>
      <td class="style1">Solu&ccedil;&atilde;o</td>
      <td><? print($solucao);?></td>	  
    </tr>

  </table>
  <hr size="5" color="#000000" width="800">
  <span class="style1">ITENS</span>  
  <hr size="5" color="#000000" width="800">
  <table width="613" border="1">
    <tr class="style1">
      <td width="46">Cód</td>
      <td width="46">Qtdade</td>
      <td width="376"><div align="center">Descri&ccedil;&atilde;o</div></td>
      <td width="78"><div align="center">Destino</div></td>
      <td width="78">R$ Unit&aacute;rio</td>
      <td width="85">R$ Total </td>
    </tr>
<?
$cp=mysql_result($res,0,"cp");
$peca="";
$vlT=0;
$sql2="select peca.descricao as peca , orc.valor as valor, orc.qt as qt,orc_decisao.aprova as aprova, orc_decisao.descricao as decisao, destino.descricao as destino, peca.cod as codp
from peca inner
join orc on orc.cod_peca = peca.cod left
join orc_decisao on orc_decisao.cod = orc.cod_decisao inner
join destino on destino.cod = orc.cod_destino
where orc.cod_cp = $cp"; 
$res2=mysql_db_query ("$bd",$sql2,$Link) or die (mysql_error());
while ($linha2 = mysql_fetch_array($res2)){
	$msg="";
	$aprova=$linha2["aprova"];
	if($aprova==0 && $aprova<>NULL){
		$msg="<font color=red> $linha2[decisao]</font>";
	}


	$codpeca=$linha2["codp"];
	$qt=$linha2["qt"];
	$peca=$linha2["peca"];
	$destino=$linha2["destino"];
	$valor=$linha2["valor"];
	$tot=$qt*$valor;
	$vlT=$vlT+$tot
?>
    <tr>
      <td><? print($codpeca);?></td>
      <td><? print($qt);?></td>
      <td><? print($peca.$msg);?></td>
	  <td><? print($destino);?></td>
      <td><? print("R$ ".number_format($valor, 2, ',', '.')); ?></td>
      <td><? print("R$ ".number_format($tot, 2, ',', '.')); ?></td>
    </tr>
<?
}
?>
    <tr>
      <td colspan="3" class="style1"><div align="right">TOTAL GERAL </div></td>
      <td><?
	 		 $vlT=number_format($vlT, 2, ',', '.');
			 print("R$ ".$vlT);
			 ?></td>
    </tr>
  </table>
  <br>
  <hr size="10" color="#000000" width="800">
  <table width="595" border="0">
  <tr>
      <td width="62" height="21">&nbsp;</td>
      <td width="529" colspan="3"><div align="center"></div></td>
    <tr>
	    <td height="21">T&eacute;cnico:</td>
	  <td><div align="center"></div>		  
	      <div align="center">_______________________________________<br>	    
          <? print($tecnico);?></div></td>
    </tr>
  <td height="41"></tr>
    <tr>
      <td width="161" height="56">Aprovado ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </td>
      <td width="424">Reprovado ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </td>
    </tr>
	<tr><td height="57">Rever ( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </td>
	<td><div align="center">_________________________________</div></td></tr>
    <tr>
      <td height="70">Data______/______/______</td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C.B.D.:________________________________</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
</body>
</html>
