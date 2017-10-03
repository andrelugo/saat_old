<script>
function pre(){
	if (document.form1.txtOrc.value=="S" || document.form1.txtOrc.value=="s"){
		document.form1.txtOrc.value="Sem Orçamento";
	}
}
function dig(){
		document.form1.txtOrc.value="";
}

</script>
<?
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
if (empty($_GET["onde"])){$onde="";}else{$onde="and cp.cod = ".$_GET["onde"];}
if (isset($_GET["erro"])){$erro="<br><h1><font color=red><center>".$_GET["erro"]."</font></h1></center>";}else{$erro="";}
if (empty($_GET["ordem"])){$ordem="cod";}else{$ordem=$_GET["ordem"];}
if (empty($_GET["limite"])){$limite="";$limiteform="";}else{$limite="limit 0 , ".$_GET["limite"];$limiteform=$_GET["limite"];}


if (empty($_GET["digitar"])){$digitar="";}else{$digitar=1;$ordem="dig_orc";}

if (empty($_GET["revisa"])){
	$revisa=0;
		$sql="SELECT DATEDIFF(now(),data_analize) as diasag,tx_mo, fornecedor.os_auto AS os_auto, cp.cod AS cod, barcode, serie, modelo.descricao AS modelo, rh_user.nome AS nome, cod_produto_cliente, 
		filial, data_barcode, defeito.descricao AS defeito, solucao.descricao AS solucao, dig_orc
		FROM cp
		INNER JOIN modelo ON modelo.cod = cp.cod_modelo
		INNER JOIN linha ON linha.cod = modelo.linha
		INNER JOIN rh_user ON rh_user.cod = cp.cod_tec
		INNER JOIN defeito ON defeito.cod = cp.cod_defeito
		INNER JOIN solucao ON solucao.cod = cp.cod_solucao
		INNER JOIN fornecedor ON fornecedor.cod = modelo.cod_fornecedor
		WHERE 
		linha.orc_coletivo =0 AND
		cp.orc_cliente IS NULL AND 
		DATEDIFF(now(),data_analize)<>0
		$onde
		GROUP BY cod, barcode, serie, modelo, nome, cod_produto_cliente
		order by $ordem
		$limite";
}else{
		$revisa=$_GET["revisa"];
		$sql="SELECT tx_mo,fornecedor.os_auto as os_auto,cp.orc_cliente, cp.cod AS cod, barcode, serie, modelo.descricao AS modelo, rh_user.nome AS nome, 
		cod_produto_cliente, filial, data_barcode, defeito.descricao AS defeito, solucao.descricao AS solucao
		FROM cp
		INNER JOIN modelo ON modelo.cod = cp.cod_modelo
		INNER JOIN linha ON linha.cod = modelo.linha
		INNER JOIN rh_user ON rh_user.cod = cp.cod_tec
		INNER JOIN defeito ON defeito.cod = cp.cod_defeito
		INNER JOIN 	solucao ON solucao.cod = cp.cod_solucao
		inner join fornecedor on fornecedor.cod = modelo.cod_fornecedor
		WHERE cp.cod=$revisa
		$onde
		GROUP BY cod, barcode, serie, modelo, nome, cod_produto_cliente,orc_cliente
		order by $ordem
		$limite";
		$vazio=NULL;
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundo.gif);
}
.style2 {font-size: 24px}
.style3 {
	color: #0000FF;
	font-weight: bold;
}
.style6 {font-size: 18}
.style7 {font-size: 14px}
.style9 {color: #0000FF; font-weight: bold; font-size: 14px; }
-->
</style></head>
<body onLoad="document.form1.txtOrc.focus();">
<p align="center">Or&ccedil;amentos  &agrave; comunicar ao Cliente <? print($erro); ?></p>
<form name="form1" method="post" action="scr_orcarnocliente.php">
  <table width="835" border="5" align="center" id="5">
    <?
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta de Orçamentos".mysql_error());
$rows=mysql_num_rows($res);
if ($rows<=0){print("<h1><font color=red>Nenhum produto aguardando realiazação de orçamento!</h1></font>");}
$count=0;
while ($linha = mysql_fetch_array($res)){
	$cp=$linha["cod"];
	$tecnico=$linha["nome"];
	$barcode=$linha["barcode"];
	$dtbarcode=$linha["data_barcode"];
	$filial=$linha["filial"];
	$modelo=$linha["modelo"];
	$serie=$linha["serie"];
	$defeito=$linha["defeito"];
	$solucao=$linha["solucao"];
	$cod_modelo=$linha["cod_produto_cliente"];
	$os_auto=$linha["os_auto"];
	$mo1=$linha["tx_mo"];
	$dig_orc=$linha["dig_orc"];
	$dias_ag=$linha["diasag"];
	$mo=number_format($mo1, 2, ',', '.');
	
	if ($count==0){
		$count++;
?>
    <tr class="style2"<? if($dig_orc<>0 && $digitar==1){print("bgcolor='#00CCCC'");} ?>>
      <td width="120"><strong>T&eacute;cnico:</strong></td>
      <td width="175"><strong><? print($tecnico);?></strong></td>
      <td width="145"><strong>Modelo:</strong></td>
      <td width="247"><strong><? print($modelo);?></strong></td>
    </tr>

    <tr class="style2"<? if($dig_orc<>0 && $digitar==1){print("bgcolor='#00CCCC'");} ?>>
      <td width="120"><strong>Filial:</strong></td>
      <td width="175"><strong>&nbsp;<span class="style3"><strong><? print($filial);?></strong></span></strong></td>
      <td width="145"><strong>Cod. Modelo :</strong></td>
      <td width="247"><strong><? print($cod_modelo);?></strong></td>
    </tr>
	
    <tr class="style2"<? if($dig_orc<>0 && $digitar==1){print("bgcolor='#00CCCC'");} ?>>
      <td><strong>S&eacute;rie:</strong></td>
      <td><span class="style3"><strong><?print($serie);?>
      </strong></span></td>
      <td>Defeito:</td>
      <td><span class="style3"><strong><? print($defeito);?></strong></span></td>
    </tr>
	<tr<? if($dig_orc<>0 && $digitar==1){print("bgcolor='#00CCCC'");} ?>>
	<?
	if($os_auto==5){
	?>
		<td bgcolor="#FFFF33" class="style2">Mão de Obra:</td>
		<td bgcolor="#FFFF33" class="style2">R$ <? print($mo);?></td>
	<?
	}else{
	?>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	<?
	}
	?>
		<td width="82" class="style2">Solu&ccedil;&atilde;o:</td>
		<td width="18"><span class="style3"><strong><? print($solucao);?></strong></span></td>
	</tr>
	<tr class="style2"<? if($dig_orc<>0 && $digitar==1){print("bgcolor='#00CCCC'");} ?>>
		<td>Barcode:</td>
		<td><span class="style3"><? print("<a href='con_cp.php?cp=$cp&msg=Consulta' alt='Consultar Controle de Produção!'>".$barcode)."</a>";?></span></td>
		<td>Data Barcode: </td>
		<td><span class="style3"><strong><? print($dtbarcode);?></strong></span></td>
	</tr>
  </table>
  <table width="886" border="5" align="center">
    <tr class="style2">
	  <td width="50"><span class="style6">Cód.</span></td>
      <td width="350"><span class="style6">Descri&ccedil;&atilde;o</span></td>
      <td width="84"><span class="style6">Situa&ccedil;&atilde;o</span></td>
      <td width="76"><span class="style6">Destino</span></td>
      <td width="88"><span class="style6">Qtde</span></td>
      <td width="118"><span class="style6">R$ Unit.</span></td>
      <td width="69"><span class="style7">R$ - Total </span></td>
    </tr>
    <?	
		$sql2="select orc.qt as qt,peca.cod as codP,peca.descricao as peca,cod_peca as codpeca, orc_motivo.descricao as situacao, orc.valor as vl, destino.descricao as destino
		from orc
		inner join peca on peca.cod = orc.cod_peca
		left join orc_motivo on orc.cod_motivo = orc_motivo.cod
		left join destino on orc.cod_destino = destino.cod
		where orc.cod_cp = $cp";
		$res2=mysql_db_query ("$bd",$sql2,$Link) or die ("Erro na SQL de consulta de Orçamentos".mysql_error());
		$tot=0;
		$totg=0;
		while ($linha2 = mysql_fetch_array($res2)){
			$qt=$linha2["qt"];
			$peca=$linha2["peca"];
			$situacao=$linha2["situacao"];
			$destino=$linha2["destino"];
			$vl=$linha2["vl"];
			$codpeca=$linha2["codpeca"];
			$codP=$linha2["codP"];
			$tot=$qt*$vl;
			$totg=$totg+$tot;
?>
    <tr class="style2">
      <td><span class="style3"><? print($codP);?></span></td>
      <td><span class="style3"><? print("<a href='frm_peca.php?cod=$codpeca'>".$peca."</a>");?><span></span></span></td>
      <td><span class="style3"><? print($situacao);?><span></span></span></td>
      <td><span class="style3"><? print($destino);?></span></td>
      <td><?print($qt);?></td>
      <td><span class="style3">	  
        <? //Header("Location:scr_atualiza_itemorc.php?peca=$codpeca");
	  $Rvalor = "<a href='scr_atualiza_itemorc.php?peca=$codpeca&onde=$cp' alt='Atualizar preço!'>R$ " . number_format($vl, 2, ',', '.')."</a>";
	  print($Rvalor);
	  ?>
      </span></td>
	  <td><span class="style9">
	    <?
	  $Rvalor = "R$ " . number_format($tot, 2, ',', '.');
	  print($Rvalor); 
	  ?>
	  </span></td>
    </tr>
    <?
		}
?>
    <tr class="style2">
      <td colspan="4">        <div align="center">N&ordm; do Orc. do cliente
          <input name="txtOrc" type="text" class="style2" id="txtOrc" <? if($digitar==1){print("onKeyUp=dig();");}else{print("onKeyUp=pre();");}?> value="<? if($revisa<>0){print($linha["orc_cliente"]);}?>" >
            <input name="Marcar" type="submit" id="Marcar" value="Marcar">
            <span class="style3"><strong>
            <input type="hidden" name="cp" value="<?print($cp);?>">
            <strong>            </strong> </strong></span> </div></td>
      <td colspan="2"><span class="style7">Total Geral</span>:
        <?
	  $Rvalor = "R$ " . number_format($totg, 2, ',', '.');
	  print($Rvalor);
	  ?>
        <span class="style3"><strong><strong>
        <input type="hidden" name="vtot" value="<?print($totg);?>">
        </strong></strong></span></td>
    </tr>
  </table>
<table border="1" align="center">
<tr>
	<td>    Limitar em
		<input name="limite" type="text" size="2" maxlength="2" value="<? print($limiteform);?>"> 
		or&ccedil;amentos exibidos.
	</td>
	<td>
		Apenas digitar sem registrar or&ccedil;amento
		  <input type="checkbox" name="chkDigitar" value="checkbox" id="chkDigitar" <? if($digitar==1){print("checked>");}?>>
	</td>
	<td>Barcode 
	  <input name="txtBarcode" type="text" id="txtBarcode"></td>
</tr>
</table>
</form>
<br>
<table width="945" border="1" align="center">
  <tr>
  <td width="23">N</td>
  <td width="56">Dias Ag. Digitar</td>
    <td width="177"><strong><a href="frm_orcarnocliente.php?ordem=modelo&limite=<? print($limiteform);?>">Modelo</a></strong></td>
    <td width="80"><strong>Barcode</strong></td>
    <td width="125"><strong>S&eacute;rie</strong></td>
    <td width="337"><strong>Pe&ccedil;as</strong></td>
  </tr>
<?
	}
	if ($count<>1){
?>
  <tr <? if($dig_orc<>0){print("bgcolor='#00CCCC'");}  // colorindo conforme o mouse se movimenta //print ("onMouseOver=$jvA onMouseOut=$jvB");?>>
    <td><? print($count);?></td>
	<td><? print($dias_ag);?></td>
	<td><? print($modelo);?></td>
    <td><a href="frm_orcarnocliente.php?onde=<? print ($cp);?>&vazio=<?print ($vazio);?>&limite=<? print($limiteform);?>"><?print($barcode);?></td>
    <td><?print($serie);?></td>
<?
		$sql3="select peca.descricao as peca,orc_motivo.descricao as situacao, orc.valor as vl
		from orc
		inner join peca on peca.cod = orc.cod_peca
		left join orc_motivo on orc.cod_motivo = orc_motivo.cod
		where orc.cod_cp = $cp";
		$res3=mysql_db_query ("$bd",$sql3,$Link) or die ("Erro na SQL de consulta de Orçamentos".mysql_error());
		$pecas="";
		while ($linha3 = mysql_fetch_array($res3)){
			$peca=$linha3["peca"];
			$situacao=$linha3["situacao"];
			$vl = "R$ " . number_format($linha3['vl'], 2, ',', '.');
			$pecas="$pecas $peca <font color=blue> $situacao</font> <font color=red>$vl</font><BR>";
		}
?>    
	<td><? print ($pecas); ?></td>
 <td width="101"></a> </tr>
<?
	}
	$count++;
}
?>
</table>
<p align="center"><strong> <? print($count-1);?> PRODUTOS AGUARDANDO DIGITA&Ccedil;&Atilde;O DE OR&Ccedil;AMENTO </strong></p>
</body>
</html>
