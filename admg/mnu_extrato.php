<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtAno"])){$ano=$_GET["txtAno"];}else{$ano=date("Y");}
if (isset($_GET["txtMes"])){$mes=$_GET["txtMes"];}else{$mes=date("m");}
$where="and (month(data_sai)=$mes and year(data_sai)=$ano)";
$nome = $_COOKIE["nome"];
?><html>
<head>
<title>SAAT</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(fundo.jpg);
}
.style1 {font-weight: bold}
-->
</style></head>
<body>
<p align="center" class="style1"><a href="frm_cad_extrato.php">CADASTRO DE NOVO EXTRATO</a></p>
<p align="center" class="style1"><a href="con_extrato.php">CONSULTAR EXTRATOS</a> </p>
<hr>
<div align="center">Ordens de Servi&ccedil;os sem extrato no sistema
</div>
  <form name="form1" method="get" action="mnu_extrato.php">
    <p align="center">
    <input name="cmdAcuMes" type="submit" id="cmdAcuMes" value="Visualizar">
    Movimento de entrada do exercicio:
    <select name="txtMes" id="txtMes">
          <option value="1" <? if ($mes==1){print ("selected");}?>>Janeiro</option>
          <option value="2"<? if ($mes==2){print ("selected");}?>>Fevereiro</option>
          <option value="3"<? if ($mes==3){print ("selected");}?>>Mar&ccedil;o</option>
          <option value="4"<? if ($mes==4){print ("selected");}?>>Abril</option>
          <option value="5"<? if ($mes==5){print ("selected");}?>>Maio</option>
          <option value="6"<? if ($mes==6){print ("selected");}?>>Junho</option>
          <option value="7"<? if ($mes==7){print ("selected");}?>>Julho</option>
          <option value="8"<? if ($mes==8){print ("selected");}?>>Agosto</option>
          <option value="9" <? if ($mes==9){print ("selected");}?>>Setembro</option>
          <option value="10"<? if ($mes==10){print ("selected");}?>>Outubro</option>
          <option value="11"<? if ($mes==11){print ("selected");}?>>Novembro</option>
          <option value="12"<? if ($mes==12){print ("selected");}?>>Dezembro</option>
      </select>
          de
          <input name="txtAno" type="text" id="txtAno" value="<? print($ano);?>" size="4" maxlength="4">
  </p>
</form>


<div align="center">
  <table width="822" border="1">
    <tr>
      <td width="215"><strong>Fornecedor</strong></td>
	  <td width="114"><strong>OS Entregues </strong></td>
	  <td width="112"><strong>M.O. Paga </strong></td>
	  <td width="115"><strong>Or&ccedil;amentos Aprovados</strong></td>
	  <td width="112"><strong>Or&ccedil;amentos Reprovados</strong></td>
      <td width="114"><strong> O.S. n&atilde;o pagas </strong></td>
    </tr>
<?
$sql="SELECT cod,descricao FROM fornecedor";
$res=mysql_query($sql) or die(mysql_error());
$rows = mysql_num_rows($res);
if ($rows==0){
	print("Nenhuma OS sem extrato no sistema neste momento");
}else{
	$tot=0;$tot2=0;$tot3=0;$tot4=0;$tot5=0;
	while($linha=mysql_fetch_array($res)){
		?><tr><td><?
		print($linha["descricao"]);
		?></td><td><?
			$res2=mysql_query("select count(cp.cod) as var from cp inner join modelo on modelo.cod = cp.cod_modelo where modelo.cod_fornecedor = $linha[cod] $where") or die (mysql_error());
			$var=mysql_result($res2,0,"var");
			$tot=$tot+$var;
			print($var);
		?></td><td><?
			$res2=mysql_query("select sum(cp.valor_gar) as var from cp inner join modelo on modelo.cod = cp.cod_modelo where modelo.cod_fornecedor = $linha[cod] $where") or die (mysql_error());
			$var=mysql_result($res2,0,"var");
			$tot2=$tot2+$var;
			print($var);		
		?></td><td><?
			$res2=mysql_query("select sum(orc.valor) as var 
			from cp 
			inner join modelo on modelo.cod = cp.cod_modelo 
			inner join orc on orc.cod_cp = cp.cod
			inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
			where modelo.cod_fornecedor = $linha[cod] and orc_decisao.aprova=1 $where") or die (mysql_error());
			$var=mysql_result($res2,0,"var");
			$tot3=$tot3+$var;
			print($var);				
		?></td><td><?
			$res2=mysql_query("select sum(orc.valor) as var 
			from cp 
			inner join modelo on modelo.cod = cp.cod_modelo 
			inner join orc on orc.cod_cp = cp.cod
			inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
			where modelo.cod_fornecedor = $linha[cod] and orc_decisao.aprova=0 $where") or die (mysql_error());
			$var=mysql_result($res2,0,"var");
			$tot4=$tot4+$var;
			print($var);				
		?></td><td><?
			$res2=mysql_query("select count(cp.cod) as var from cp inner join modelo on modelo.cod = cp.cod_modelo where modelo.cod_fornecedor = $linha[cod] and cp.cod_extrato_mo is null $where") or die (mysql_error());
			$var=mysql_result($res2,0,"var");
			$tot5=$tot5+$var;
			print($var);
		?></td></tr><?
	}
}
?>	
    <tr>
      <td><strong>Total</strong></td>
      <td><strong><? print($tot);?></strong></td>
	  <td><strong><? print($tot2);?></strong></td>
	  <td><strong><? print($tot3);?></strong></td>
	  <td><strong><? print($tot4);?></strong></td>
	  <td><strong><? print($tot5);?></strong></td>
    </tr>
  </table>
</div>
<hr>
<p align="center">Ordens de Serviços entregues a mais de 35 dias sem extrato</p>
<div align="center">
  <table width="822" border="1">
<tr>
	<td>Fornecedor</td>
	<td>Qtdade</td>
</tr>
<?
$sql=mysql_query("select fornecedor.descricao as fornecedor, count(cp.cod) as qt , fornecedor.cod as codfornecedor
from cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join fornecedor on fornecedor.cod = modelo.cod_fornecedor 
where datediff(now(),data_sai)>35 and cod_extrato_mo is null
group by fornecedor");
while($linha=mysql_fetch_array($sql)){
?>
<tr>
	<td><? print($linha["fornecedor"]);?></td>
	<td><a href="con_semextrato.php?fornecedor=<? print($linha["codfornecedor"]);?>"><font color="#FF0000"><strong><? print($linha["qt"]);?></strong></font></a></td>
</tr>
<?
}
?></table>
</div>
</body>
</html>