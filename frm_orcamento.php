<script>
function abrir(pesq,forn){
	var url = "";
	if (document.form1.txtDescricao.value != ""){
		pesq=document.form1.txtDescricao.value;
		url="pes_peca.php?desc=" + pesq + "&forn=" + forn + "&orcamento=1&cortesia=0&garantia=0&modelo=0";
		janela=window.open(url, "janela","toolbar=no,location=no,status=no,scrollbars=yes,directories=no,width=500,height=400,top=18,left=0");
		janela.focus();
	}else{
	alert ("Nenhuma descrição foi digitada!");
	}
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cp"])){
	$cp=$_GET["cp"];
	$forn=$_GET["forn"];
	if (isset($_GET["erro"])){
		$erro=$_GET["erro"];
		$codPeca=$_GET["codPeca"];
		$descricao=$_GET["descricao"];
		$codMotivo=$_GET["motivo"];
		$codDestino=$_GET["destino"];
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 24px}
.style4 {font-size: 12px}
.style5 {
	font-size: 72px;
	color: #CC0033;
}
body {
	background-image: url(img/fundo.gif);
}
.style6 {	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<div align="center" class="style1">
  <p><span class="style5">Or&ccedil;amento</span> C.P. <?print($cp);?><? if (isset($erro)){print("<h2><font color='red'>".$erro);}?>
</p>
  <form name="form1" method="post" action="scr_orcamento.php">
    <table width="803" border="0">
      <tr>
        <td width="411" class="style4">Descri&ccedil;&atilde;o</td>
        <td width="144" class="style4">Destino: </td>
        <td width="102" class="style4">Motivo</td>
        <td width="206" class="style4">Quantidade</td>
      </tr>
      <tr>
        <td><input name="txtDescricao" type="text" value="<?if (isset($descricao)){print($descricao);}?>" size="55" maxlength="50" alt="Digite o primeiro nome de uima peça e clique em pesquisar para efetuar uma pesquisa!">
        <span class="style4">          <img src="img/botoes/b_search.png" title="Preencha a caixa descri&ccedil;&atilde;o com uma palavra e clique aqui!" width="16" height="16"  onclick='javascript: abrir(document.form1.txtDescricao,<?print($forn);?>);'> </span></td>
        <td><select name="cmbDestino" class="style1" id="select6"  tabindex="5" >
          <option value="0"></option>
<?
//$con=mysql_query("select data_aprp_orc as dt, cod_destino as dest aprp_orc as aprp from cp where cod = $cp");
//$dest=mysql_result($con,0,"dest");
//$data=mysql_result($con,0,"dt");
//$aprp=mysql_result($con,0,"aprp");
//if (isset($data)){
//	$con2=mysql_query("select descricao from destino where cod=$dest");
//	$dest=mysql_result($con,0,"dest");
//	if($aprp=0){$ap="Reprovado";}else{$ap="Aprovado";}
//	print("<font align=center><h2>Orçamento $ap em $data para $dest</h2></font>");
//}else{
	$sql="select * from destino where cq=1";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Destino");
	while ($linha = mysql_fetch_array($res)){
		if (isset($codDestino)){
			if ($codDestino==$linha[cod]){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
			}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
			}
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}
//}
?>
        </select></td>
        <td><span class="style6">
          <select name="cmbMotivo" class="style1" id="select"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from orc_motivo where ativo=1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Destino");
while ($linha = mysql_fetch_array($res)){
	if (isset($codMotivo)){
		if ($codMotivo==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
          </select>
        </span></td>
        <td><span class="style6">          <input name="txtQt" type="text" value="1" size="1" maxlength="1">
</span></td>
      </tr>
    </table>
    <span class="style1">
    <input name="txtCod" type="text" class="style1" value="<?if (isset($codPeca)){print($codPeca);}?>" size="11" maxlength="11">
    </span>
    <input type="hidden" name="cp" value="<? print($cp);?>">
	<input type="hidden" name="forn" value="<? print($forn);?>">
<? if (empty($dest)){?>	
    <input name="Submit" type="submit" class="style1" value="Inserir">
<? }?>
</form>
  <hr>
  <?
  $sqlvlp=mysql_query("select custo_cliente,perc_aprova from modelo inner join cp on modelo.cod = cp.cod_modelo where cp.cod = $cp")or die(mysql_error());
  $vlProduto=mysql_result($sqlvlp,0,"custo_cliente");
  $percentual=mysql_result($sqlvlp,0,"perc_aprova");
	if($vlProduto==0 || $percentual==0){
		if($vlProduto==0){print("O valor deste produto não foi cadastrado no sistema");}
		if($percentual==0 && $vlProduto<>0){print("Erro: O valor deste produto foi cadastrado mas o percentual limite não. Favor avise a gerência!");}
	$maxAp=0;
	}else{
		$percentual=$percentual/100;
		$maxAp=$vlProduto*$percentual;
	  
		$sqltv=mysql_query("select sum(valor*qt) as tot FROM orc WHERE orc.cod_cp = $cp")or die(mysql_error());
		$vlv=mysql_result($sqltv,0,"tot");
	  
		if($vlv>$maxAp){
			//$msgvalida="<h2><font color='red'>Produto não será aprovado VALORDOPROD=$vlProduto PERCENTUAL=$percentual / MAX=$maxap / Vendido = $vlv </h2></font>";  
			$msgvalida="<h2><font color='red'>Orçamento será reprovado pois ultrapassou limite estabelecido pelo cliente</h2></font>";  
			print("$msgvalida");
		}
	}
  ?>
  <table width="910" border="1">
    <tr>
      <td width="60"><strong>C&oacute;digo</strong></td>
      <td width="312"><strong>Descri&ccedil;&atilde;o</strong></td>
      <td width="55"><strong>Motivo</strong></td>
      <td width="52"><strong>Destino</strong></td>
      <td width="52"><strong>Qtdade</strong></td>
      <td width="101"><strong>Data do Or&ccedil;amento </strong></td>
	  <td width="91"><strong>Data da Aprova&ccedil;&atilde;o </strong></td>
	  <td width="135"><strong>%Limite</strong></td>
    </tr> 
<?
//Caso um item já tenha sido defino então não permitir sua exclusão e mostrarsua definição
$sql="SELECT peca.cod_fabrica AS cod, peca.descricao AS descricao, orc.qt, DATE_FORMAT( data_cad, '%d-%m-%y' ) AS dtCad, 
DATE_FORMAT( data_decisao, '%d/%m/%Y as %k:%i%s' ) AS dtAp, orc.cod AS codorc, orc_motivo.descricao AS motivo, 
destino.descricao AS destino, orc_decisao.descricao AS decisao, orc.cod_decisao AS codDescisao,orc.valor*orc.qt as vlpeca
FROM orc
INNER JOIN peca ON peca.cod = orc.cod_peca
LEFT JOIN orc_motivo ON orc_motivo.cod = orc.cod_motivo
LEFT JOIN destino ON destino.cod = orc.cod_destino
LEFT JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE orc.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Sql peças em frm_orcamento.php".mysql_error());

		// Remendo provisório para permitir que o fábio corriga alguns orçamentos na Casas Bahia em 26/11/2007
		$resadm=mysql_query("select adm_geral from rh_user where cod = $id");
		$admGeral=mysql_result($resadm,0,"adm_geral");
$tpercAp=0;
while ($linha = mysql_fetch_array($res)){
	$codorc=$linha["codorc"];
	$codDecisao=$linha["codDescisao"];
	
	$vlPeca=$linha["vlpeca"];
	if($maxAp<>0){
		$percAp=($vlPeca/$maxAp)*100;
		$tpercAp=$tpercAp+$percAp;
		$percAp = number_format($percAp, 2, ',', '.') . "%";
	}else{
		$percAp = "Não Cadastrado";
	}
	
	
	if ($codDecisao==NULL || $codDecisao==0 || $codDecisao==1 || $admGeral==1){
		print ("<tr class='style11'><td>$linha[cod]</td> 
		<td>$linha[descricao]</td>
		<td>$linha[motivo]</td>
		<td>$linha[destino]</td>
		<td>$linha[qt]</td> 
		<td>$linha[dtCad]</td>
		<td>$linha[dtAp]</td>
		<td>$percAp</td>
		<td><a href='scr_excui.php?codped=$codorc&cp=$cp&forn=$forn&msg=&dest=frm_orcamento.php&tabela=orc&modelo=0'>
		<img src='img/botoes/b_drop.png' width='16' height='16' border='0'></a></td></tr>");
	}else{
		$decisao=$linha["decisao"];
		print ("<tr><td>$linha[cod]</td> 
		<td>$linha[descricao]</td> 
		<td>$linha[motivo]</td>
		<td>$linha[destino]</td>
		<td>$linha[qt]</td>
		<td>$linha[dtCad]</td>
		<td>$linha[dtAp]</td>
		<td>$percAp</td>
		<td>$linha[decisao]</td></tr>");
	}
}
?>
<tr>
  <td colspan="7"><div align="center"><strong>TOTAL EM OR&Ccedil;AMENTO (n&atilde;o deve ultrapassar o limite de 100%) </strong></div></td>
  <td><strong>
    <? 
$tpercAp = number_format($tpercAp, 2, ',', '.') . "%";
print($tpercAp);?>
  </strong></td>
</tr>
</table>
  <p><a href="con_cp.php?cp=<? print($cp);?>&msg=">Voltar</a> </p>
</body>
</html>