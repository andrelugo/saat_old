<script>
function pre(){
	if (document.form1.txtBarras.value=="S" || document.form1.txtBarras.value=="s"){
		document.form1.txtBarras.value="Sem Entrada";
	}
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("select bgcolor from rh_user where cod=$id");
$bgcolor=mysql_result($res,0,"bgcolor");
?>
<html>
<head>
<title>Menu Controle de Produção - Ordem de Serviços</title>
<style type="text/css">
<!--
.style2 {font-size: 24px}
.style3 {color: #FF0000}
.style4 {
	font-size: 24px;
	font-weight: bold;
}
.style5 {
	font-size: 12px;
	font-style: italic;
}
body {
	background-color: <? print($bgcolor);?>;
}
.style9 {color: #FF0000; font-style: italic; }
.style10 {color: #000000}
.style12 {color: #FF0000; font-style: italic; font-size: 18px; }
.style18 {color: #0000FF}
.style19 {
	color: #FF0000;
	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>
<body onLoad="document.form1.txtBarras.focus();">
<?
$sqlmes=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id")or die(mysql_error());
$mes=mysql_result($sqlmes,0,"qt");
$sqlaglib=mysql_query("SELECT count(cod) as qt from cp where data_pronto is not null and data_sai is null and cod_tec=$id")or die(mysql_error());
$aglib=mysql_result($sqlaglib,0,"qt");
$sqlsaimesCarencia=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_sai) = MONTH(NOW()) and YEAR(data_sai) = YEAR(NOW()) and cod_tec=$id and carencia=1")or die(mysql_error());
$saimesCarencia=mysql_result($sqlsaimesCarencia,0,"qt");
$sqlsaimes=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_sai) = MONTH(NOW()) and YEAR(data_sai) = YEAR(NOW()) and cod_tec=$id")or die(mysql_error());
$saimes=mysql_result($sqlsaimes,0,"qt");
$sqlhoje=mysql_query("SELECT count(cod) as qt from cp where DAY(data_pronto) = DAY(NOW()) and MONTH(data_pronto) = MONTH(NOW()) and YEAR(data_pronto) = YEAR(NOW()) and cod_tec=$id")or die(mysql_error());
$hoje=mysql_result($sqlhoje,0,"qt");
$sqlpend=mysql_query("SELECT count(cod) as qt from cp where data_pronto is null and data_analize is not null and cod_tec=$id")or die(mysql_error());
$pend=mysql_result($sqlpend,0,"qt");
$sqlpend20=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_entra) >19) and cod_tec=$id")or die(mysql_error());
$pend20=mysql_result($sqlpend20,0,"qt");

$sqlpend14=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_entra) >13) and cod_tec=$id")or die(mysql_error());
$pend14=mysql_result($sqlpend14,0,"qt");

$sqlpendorc=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and cod_tec=$id and cod_decisao=0 GROUP BY cp.cod")or die(mysql_error());
$pendorc=mysql_num_rows($sqlpendorc);

$sqlap=mysql_query("SELECT cp.cod AS cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND cod_tec =$id AND aprova =1 GROUP BY cod")or die(mysql_error());
$ap_orc=mysql_num_rows($sqlap);

$sqlrp=mysql_query("SELECT cp.cod AS cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND cod_tec =$id AND reprova =1 GROUP BY cod")or die(mysql_error());
$rp_orc=mysql_num_rows($sqlrp);

?>
<table width="512" border="1" align="center">
  <tr class="style19">
    <td colspan="2"><div align="center" class="style10"><a href="con_pendenciatec.php" title="Se desejar ver os produtos NÃO PRONTOS Clique AQUI!">Pend&ecirc;ncias de <?
//$nome = $_COOKIE["nome"];
$res=mysql_query("select nome from rh_user where cod = $id");
$nome=mysql_result($res,0,"nome");
echo " $nome";
?></a></div></td>
  </tr>
  <tr>
    <td width="430">&quot;Or&ccedil;amentos Aguardando aprova&ccedil;&atilde;o &quot;</td>
    <td width="66"><?print ($pendorc)?></td>
  </tr>
  <tr>
    <td>Or&ccedil;amentos APROVADOS</td>
    <td><?print ($ap_orc)?></td>
  </tr>
   <tr>
    <td>Or&ccedil;amentos REPROVADOS </td>
    <td><?print ($rp_orc)?></td>
  </tr>
  
  <tr>
    <td>Aguardando  pe&ccedil;as </td>
    <td>
<?
$sqlpeca=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND cod_tec =$id GROUP BY cp.cod")or die(mysql_error());
$peca=mysql_num_rows($sqlpeca);
//$peca = $pend - $pendorc - $aprp_orc;
print ($peca)
?></td>
  </tr>
  <tr>
  <td><span class="style3">Produtos Pendentes sem Orçamento e sem Pedido</span></td>
  <td><span class="style3">
    <?
$sqlSem=mysql_query("SELECT count(cp.cod) as tot
FROM cp LEFT
JOIN orc ON orc.cod_cp = cp.cod LEFT
JOIN pedido ON pedido.cod_cp = cp.cod
WHERE orc.cod_cp IS NULL
AND pedido.cod_cp IS NULL
AND cp.cod_tec = $id
AND cp.data_pronto IS NULL ")or die(mysql_error());
$rows=mysql_num_rows($sqlSem);
if ($rows>0){
	$sem=mysql_result($sqlSem,0,"tot");
	print ($sem);
}else{
	print ("0");
}
?>
  </span></td>
  </tr>
  
  <tr>
    <td>Total de pend&ecirc;ncias em minha planilha </td>
    <td><center class="style4">
        <font size="+1"><strong><?print ($pend)?></strong></font>
    </center></td>
  </tr>
  <div align="center"><span class="style9"> </span>
      <tr>
        <td> 
            <div align="center" class="style12"> Ordens de Servi&ccedil;os abertas a mais de 20 dias sob minha respons&aacute;bilidade </div>        </td>
        <td><div align="center" class="style19"><?print ($pend20)?></div></td>
      </tr>
      <span class="style9"></span><span class="style9"> </span></div>
</table>
<br> 
<span class="style3">
<?
print($nome." ");
if($pend20>0){
	?>
	Infelismente voc&ecirc; n&atilde;o ser&aacute; premiado neste m&ecirc;s mesmo que atinja suas metas individuais, porqu&ecirc; existem produtos muito atrazados com voc&ecirc;. Portanto &eacute; imprescindivel que se tomem medidas cabiveis junto ao seu Gerente para a devida retirada do sistema DO CLIENTE dos produtos acima de vinte dias, antes da virada do m&ecirc;s para que assim voc&ecirc; seja premiado. Ajude sua equipe a se tornar uma equipe vencedora. <?
}else{
	if($pend14>0){
		?>
		<span class="style18">Atenção!!! Exitem <? print($pend14);?> produtos acima de 14 dias em sua planilha é recomendavel verificar junto a sua gerencia providências antes que seja tarde demais!
		</span>
		<?
	}else{
		?>
		<span class="style18">Parab&eacute;ns! Por colaborar com o sucesso de nossa equipe. Continue assim!
		</span>
		<?
	}
}
?>
</span>
<hr>
<p align="center" class="style4"><a href="con_aganalise.php">
<?
$sqlLtec=mysql_query("select linhatec from rh_user where cod=$id");
$Ltec=mysql_result($sqlLtec,0,"linhatec");
if ($Ltec==0){
	$where="";
}else{
	$where="and modelo.linha=$Ltec";
}
$sqlanalise=mysql_query("SELECT count(cp.cod) as qt from cp inner 
join modelo on modelo.cod = cp.cod_modelo 
where data_analize is null $where")or die(mysql_error());
$analise=mysql_result($sqlanalise,0,"qt");


$sqlanalise3=mysql_query("SELECT count(cp.cod) AS qt FROM cp inner 
join modelo on modelo.cod = cp.cod_modelo 
WHERE data_analize IS NULL AND (DATEDIFF(now( ) , data_entra) >=4) $where")or die(mysql_error());
$analise3=mysql_result($sqlanalise3,0,"qt");

if ($analise3>0){
?>
Atenção: Senhores técnicos <span class="style19">existem <? print($analise3);?> produtos com mais de 4 dias</span> aguardando análise. 
<span class="style18">PRIORIZEM</span> o atendimento destes, pois caso necessitem de pe&ccedil;as em garantia haver&aacute; tempo para seu recebimento antes do prazo de 20 dias! Colaborem para o nosso sucesso!
<?
}else{
	if ($analise>0){
		?>
<h3 align="center" class="style18">
		Existem <? print($analise);?> produtos com entrada aguardando análise técnica
		<?
	}else{
		?>
		Neste momento não há entrada de produtos no sistema!
</h3>
		<?
	}
}
?>
</a>
</p>
<p align="center" class="style4">Acesso ao  Controle de Produ&ccedil;&atilde;o </p>
<form name="form1" method="get" action="scr_mnucp.php">
  <p align="center">
  Digite o n&uacute;mero do C&oacute;digo de Barras ou PRESSIONE O GATILHO DO LEITOR </p>
  <p align="center"><span class="style2">C&oacute;digo de Barras</span>     
    <input name="txtBarras" type="text" class="style2" id="txtBarras" maxlength="20"  onKeyUp=pre();>
    <input name="cmdEnviar" type="hidden" class="style2" id="cmdEnviar" value="Entrar Barras"> 
	<input name="cmdEnviar" type="submit" class="style2" id="cmdEnviar" value="Acessar O.S." >
</p>
  <p align="center" class="style5">Obs: Se o c&oacute;digo de barras n&atilde;o foi cadastrado na entrada digite: &quot;Sem Entrada&quot; e pressione ENTER para escolher o modelo </p>
</form>
<hr>
  <table width="389" border="1" align="center">
    <tr bgcolor="#FFFFFF">
      <td colspan="2"><div align="center">PRODUTIVIDADE</div></td>
    </tr>
	<tr>
      <td width="294" class="style10"><a href="con_producaotec.php?criterio=HOJE" title="Clique para ver os produtos PRONTOS!">Sua produ&ccedil;&atilde;o hoje </a></td>
      <td width="79"><?print ($hoje)?></td>
    </tr>
    <tr>
      <td class="style10"><a href="con_producaotec.php?criterio=NO+MES" title="Clique para ver os produtos PRONTOS!">Sua prudu&ccedil;&atilde;o este m&ecirc;s </a></td>
      <td><?print ($mes)?></td>
    </tr>
	 <tr>
	 <td>Produtos Liberados este mês</td>
	 <td><center><font size="+1" color="#006600"><strong><?print ($saimes)?></strong></font></center></td>
	 </tr>
	 <tr>
	 <td>Produtos Liberados este mês na Carência</td>
	 <td><center class="style3">
	   <font size="+1"><strong><?print ($saimesCarencia)?></strong></font>
	 </center></td>
	 </tr>
	
	 <tr>
	   <td>Produtos aguardando libera&ccedil;&atilde;o (CQ) </td>
	   <td><?print ($aglib)?></td>
	 </tr>
</table>	
</body>
</html>
