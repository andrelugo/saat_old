<script>
function envia(){ 
document.form1.action='scr_cp.php'; 
document.form1.submit(); 
} 
function focodia(){
	if(document.form1.txtDiaBarcode.value.length==2){
		document.form1.txtMesBarcode.focus();
	}		
	if(document.form1.txtDiaBarcode.value>31 || document.form1.txtDiaBarcode.value<1){
		if(document.form1.txtDiaBarcode.length>=1){
			alert("Valor do Dia Barcode inválido");
			document.form1.txtDiaBarcode.value="";
			document.form1.txtDiaBarcode.focus();
		}
	}
}
function pre(e){
	if (document.form1.txtSerie.value=="Se" || document.form1.txtSerie.value=="se" || document.form1.txtSerie.value=="SE" || document.form1.txtSerie.value=="sE"){
		document.form1.cmbDefeito.focus();
		document.form1.txtSerie.value="Sem Série";
	}
	if (event.keyCode == 13){
		if (document.form1.txtSerie.value==""){
			document.form1.txtSerie.value="Sem Série";
		}
	document.form1.cmbDefeito.focus();
	}
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("select bgcolor from rh_user where cod=$id");
$bgcolor=mysql_result($res,0,"bgcolor");
if (isset($_GET["cp"])){
	$cp=$_GET["cp"];
	$codModelo=$_GET["codModelo"];//modelo e cp sempre estarão setadas
	if (isset($_GET["barcode"])){$barcode=$_GET["barcode"];}
	if (isset($_GET["codDefeito"])){$codDefeito=$_GET["codDefeito"];}
	if (isset($_GET["defeitoR"])){$defeitoR=$_GET["defeitoR"];}
	if (isset($_GET["mesB"])){$mesBarcode=$_GET["mesB"];}
	if (isset($_GET["diaB"])){$diaBarcode=$_GET["diaB"];}
	if (isset($_GET["anoB"])){$anoBarcode=$_GET["anoB"];}
	if (isset($_GET["serie"])){$serie=$_GET["serie"];}
//	if (isset($_GET["obs"])){$obs=$_GET["obs"];}
	$sqlObs=mysql_query("select obs from cp where cod = $cp");
	$obs=mysql_result($sqlObs,0,"obs");
	if (isset($_GET["posicao"])){$codPosicao=$_GET["posicao"];}
// Ao receber a variavel obs pelo metodo get as linhas não eram impressas corretamente então precisei fazer esta alteração 
// para corrigir a formatação de linhas. 
// O correto é que todas as variaveis fossem setadas assim como está obs agora, porem não farei isto hoje 21/04/06 pois estou
// priorizando outras customizações importantes.
	$sqlCli=mysql_query("select cliente.descricao as cli from cliente inner join cp on cp.cod_cliente=cliente.cod where cp.cod=$cp");
	$descCliente=mysql_result($sqlCli,0,"cli");


	if (isset($_GET["filial"])){$filial=$_GET["filial"];}
	if (isset($_GET["certificado"])){$certificado=$_GET["certificado"];}
	if (isset($_GET["codSolucao"])){$codSolucao=$_GET["codSolucao"];}
	if (isset($_GET["erro"])){$erro=$_GET["erro"];}
	//cmdEnvia é uma flag para o proximo script tomar decisão de atualizar ou inserir e deve vir das consultas de pendencia
	//ou quando uma analize já tiver sido feita, porem o produto ainda não estiver pronto e quem estiver acessando for o próprio técnico
//	die ($_GET["cmdEnvia"]);
	if (isset($_GET["cmdEnvia"])){
		$botao=$_GET["cmdEnvia"];
	}else{
		$botao="Salvar";
	}
}else{
	die ("Variavel CP não setada em frm_cp.php");
}
?>
<html>
<head>
<title>Formulário de preenchimento de Ordem de Serviço pelo dept. técnico</title>
<style type="text/css">
<!--
body {
	background-color: <?print($bgcolor);?>;
}
.style3 {font-size: 12px}
.style4 {font-size: 24px}
.style5 {font-size: 18px}
.style10 {color: #000000}
.style9 {color: #FF0000; font-style: italic; }
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body onLoad="document.form1.txtSerie.focus()">
<?
if(isset($erro)){print("<h1><font color='red'>".$erro);}
?>
<form name="form1" method="post" action="scr_cp.php" onsubmit="javascript:return false;">

<div align="center" class="style4">Controle de Produ&ccedil;&atilde;o <input name="txtcp" type="hidden" value="<? print($cp);?>"><? print($cp);?> </div>
<div align="center">
    <table width="799" border="1">
	<tr>
	  <td>Cliente</td>
	  <td colspan="3"><? print($descCliente);?></td></tr>
      <tr>
        <td width="131">*Modelo</td>
        <td width="242"><div align="left">
<?
	// Se já existir algum pedido para este produto ou o dia ser diferente do da da analise de um CP então não permitir a alteração
	// do modelo pois os dados já foram enviados para o sistema do fornecedor	
	$sqlGar=mysql_query("select count(cod) as qt from pedido where cod_cp=$cp");
	$Gar=mysql_result($sqlGar,0,"qt");

	$sqlDtAn=mysql_query("select data_analize, day(data_analize) as dia,month(data_analize) as mes,year(data_analize) as ano
	,os_fornecedor ,modelo.cod_fornecedor from cp inner join modelo on modelo.cod = cp.cod_modelo where cp.cod=$cp")or die(mysql_error());
	$dataAn=mysql_result($sqlDtAn,0,"data_analize");
	$osFor=mysql_result($sqlDtAn,0,"os_fornecedor");
	$codFor=mysql_result($sqlDtAn,0,"cod_fornecedor");
	$D=mysql_result($sqlDtAn,0,"dia");
	$M=mysql_result($sqlDtAn,0,"mes");
	$A=mysql_result($sqlDtAn,0,"ano");
	$DH=date("d");
	$MH=date("m");
	$AH=date("Y");
	if ($dataAn==NULL){// resultado da pesquias da data de analise deste CP
		$DE=0;
	}else{
		if ($D<>$DH || $M<>$MH || $A<>$AH){
			$DE=1;
		}else{
			$DE=0;
		}
	}
	if ($osFor==NULL){
		$sqlM="SELECT cod,descricao FROM modelo";
	}else{
		$sqlM="SELECT cod,descricao FROM modelo WHERE cod_fornecedor=$codFor";
	}
if ($Gar>=1 || $DE==1){// se existir algum pedido na tabela pedidos ou foi analizado a mais de 1 dia não deixa alterar modelo...
	$sqlMod=mysql_query("SELECT cod,descricao AS modelo FROM `modelo` WHERE `cod` = $codModelo") or die(mysql_error()) ;
	$Mod=mysql_result($sqlMod,0,"modelo");
	print("<select name='cmbModelo' class='style5' id='select8' title='Modelo do Produto' tabindex='7'>
    <option value='$codModelo'>$Mod Análise $dataAn</option>");
}else{
	?>		
    <select name="cmbModelo" class="style5" id="select8" title="Modelo do Produto" tabindex="11">
    <option value="0"></option>
	<?	  
	$res=mysql_db_query ("$bd",$sqlM,$Link) or die ("Erro na string SQL de consulta à tabela Modelo");
	while ($linha = mysql_fetch_array($res)){
		if (isset($codModelo)){
			if ($codModelo==$linha[cod]){
				print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
			}else{
				print ("<option value= $linha[cod] > $linha[descricao] </option>");
			}
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}
}
?>
          </select>
        </div></td>
        <td width="150">*C&oacute;digo de Barras</td>
        <td width="248">
<input name="txtBarcode" type="text" tabindex="8" class="style5" id="txtBarcode" maxlength="20"
<?
$sqlAlt=mysql_query("select altera_cp as alt from rh_cargo inner join rh_user on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id");
$Alt=mysql_result($sqlAlt,0,"alt");
// Se o barcode estiver setado e o ID não possuir permissão de alterar em readonly=true
if (isset($barcode) and $Alt==0){
	print("readonly='true'");
}
if (isset($barcode)){
	print("value='$barcode'");
}
?>
>
</td>
      </tr>
      <tr>
        <td>*S&eacute;rie</td>
        <td><div align="left">
          <input name="txtSerie" type="text" class="style5" id="txtSerie4" tabindex="1" value="<? if(isset($serie)){print($serie);}?>" size="30" maxlength="40" onKeyUp=pre(event);>
        </div></td>
        <td>*Data C&oacute;d. Barras </td>
        <td><input name="txtDiaBarcode" type="text" tabindex="2" id="txtDiaBarcode"  value="<?if(isset($diaBarcode) && $diaBarcode <>'0'){print($diaBarcode);}?>" size="1" maxlength="2" onKeyUp=focodia()>
          /
            <input name="txtMesBarcode" type="text" tabindex="3" id="txtMesBarcode"  value="<?if(isset($mesBarcode) && $mesBarcode <>'0'){print($mesBarcode);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesBarcode.value.length==2){document.form1.txtAnoBarcode.focus();}">
            /
        <input name="txtAnoBarcode" type="text" tabindex="4" id="txtAnoBarcode"  value="<?if(isset($anoBarcode) && $anoBarcode <>'0'){print($anoBarcode);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtAnoBarcode.value.length==2){document.form1.txtSerie.focus();}"></td>
      </tr>
      <tr>
        <td>Defeito Reclamado </td>
        <td><div align="left">
<input name="txtDefeito" type="text" tabindex="6" class="style5" id="txtDefeito" maxlength="50" value="<? if(isset($defeitoR)){print($defeitoR);}?>" >
         </div></td>
        <td>Filial:</td>
        <td><input name="txtFilial" type="text" class="style5" tabindex="5" id="txtFilial2" maxlength="5" value="<?if(isset($filial)){print($filial);}?>"></td>
      </tr>
      <tr>
        <td>*Defeito Constatado </td>
        <td><div align="left">
          <select name="cmbDefeito" class="style5" id="select2"  tabindex="7" onChange="document.form1.cmbSolucao.focus();">
            <option value="0"></option>
            <?
if ($linhatec==0){
	$sql="select * from defeito where ativo=1";
}else{
	$sql="select * from defeito where linha=$linhatec and ativo=1";
}
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela defeito");
while ($linha = mysql_fetch_array($res)){
	if (isset($codDefeito)){
		if ($codDefeito==$linha[cod]){
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
        </div></td>
        <td>Certificado:</td>
        <td><input name="txtCertificado" type="text" tabindex="8" class="style5" id="txtCertificado" size="30" maxlength="40" value="<?if(isset($certificado)){print($certificado);}?>"></td>
      </tr>
	<tr>
	<td>*Solu&ccedil;&atilde;o</td>
	<td><select name="cmbSolucao" class="style5" id="select"  tabindex="8" onChange="envia()">
      <option value="0"></option>
      <?
if ($linhatec==0){
	$sql="select * from solucao where ativo=1";
}else{
	$sql="select * from solucao where linha=$linhatec and ativo=1";
}
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Solucão");
while ($linha = mysql_fetch_array($res)){
	if (isset($codSolucao)){
		if ($codSolucao==$linha[cod]){
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
	</td>
<?
if ($botao<>"Salvar" && $Alt==1){
// Este trecho do código vai permitir que um gerente geral altere o Técnico responsável por um produto, contudo, por questões de integridade
// nos dados, o gerente nunca poderá receber um produto de outro técnico e nem alterar um produto para que saia de sua responsábilidade
// para outro técnico portanto nesta página só serão exibidos os não gerentes e no script da pág scr_cp.php a alteração de téc só será per
// permitida caso um produto que esteja sendo alterado pelo gerente não seja dele próprio vide (if ($id<>$pesqTec){)
?>
	<td>Técnico:</td>
	<td><select name="cmbTec" class="style5" id="cmbTec" tabindex="9">
      <?
		$sqlTec=mysql_query("select cod_tec from cp where cod=$cp");
		$codTec=mysql_result($sqlTec,0,"cod_tec");
		$sql="select nome as descricao,rh_user.cod from rh_user inner join rh_cargo on rh_cargo.cod = rh_user.cargo where data_demissao = '0000-00-00'";
		$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela rh_user");
		while ($linha = mysql_fetch_array($res)){
			if (isset($codTec)){
				if ($codTec==$linha[cod]){
					print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
				}else{
					print ("<option value= $linha[cod] > $linha[descricao] </option>");
				}
			}else{
				print ("<option value= $linha[cod] > $linha[descricao] </option>");
			}
		}
	?>
    </select></td>
	</tr>
<?
}?>	 
<tr>
	<td>Posi&ccedil;&atilde;o</td>
	<td><select name="cmbPosicao" class="style5" id="select"  tabindex="8">
      <option value="0"></option>
      <?
$sql="select * from posicao where ativo=1 and inventario=0";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Solucão");
while ($linha = mysql_fetch_array($res)){
	if (isset($codPosicao)){
		if ($codPosicao==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
    </select></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr> 
<tr>
	<td colspan="4">
		Observa&ccedil;&otilde;es:
		<textarea name="txtObs" tabindex="10" cols="90" rows="4" id="txtObs"><? if(isset($obs)){print($obs);}?>
		</textarea>
	</td>
</tr>
</table>
<input name="cmdSalvar" type="hidden" id="cmdSalvar" value="<?print("$botao")?>"> 
</div>
</form>

  <p align="center">
    <input name="cmdSalvar" type="submit" id="cmdSalvar" value="<?print("$botao")?>" tabindex="10" onClick="envia()" >
<?
$sqlmes=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id")or die(mysql_error());
$mes=mysql_result($sqlmes,0,"qt");
$sqlhoje=mysql_query("SELECT count(cod) as qt from cp where DAY(data_pronto) = DAY(NOW()) and MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id")or die(mysql_error());
$hoje=mysql_result($sqlhoje,0,"qt");
$sqlpend=mysql_query("SELECT count(cod) as qt from cp where data_pronto is null and data_analize is not null and cod_tec=$id")or die(mysql_error());
$pend=mysql_result($sqlpend,0,"qt");
$sqlpend20=mysql_query("SELECT count( cod ) AS qt FROM cp WHERE data_sai IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_barcode) >19) ")or die(mysql_error());
$pend20=mysql_result($sqlpend20,0,"qt");
?>
</p>
  <table width="389" border="1" align="center">
    <tr>
      <td width="294"><span class="style10">Sua produ&ccedil;&atilde;o hoje </span></td>
      <td width="79"><?print ($hoje)?></td>
    </tr>
    <tr>
      <td><span class="style10">Sua prudu&ccedil;&atilde;o este m&ecirc;s </span></td>
      <td><?print ($mes)?></td>
    </tr>
    <tr>
      <td><span class="style10">Minhas pendencias</span></td>
      <td><?print ($pend)?></td>
    </tr>
    <div align="center"><span class="style9"> </span>
        <tr>
          <td><span class="style9"> </span>
              <div align="center" class="style9">Total de produtos acima de 20 dias </div>
              <span class="style9"></span></td>
          <td><span class="style9"><? print ($pend20)?></span></td>
        </tr>
        <span class="style9"></span><span class="style9"> </span></div>
  </table>
  <p align="center">&nbsp;  </p>
</body>
</html>
