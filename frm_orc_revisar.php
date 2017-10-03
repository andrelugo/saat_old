<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
.style2 {color: #660000}
.style4 {font-size: 18px}
.style5 {color: #FF0000}
-->
</style>
</head>
<body>
<div align="center" class="style1">
  <p class="style2">Revis&atilde;o de Or&ccedil;amentos </p>
  <form name="form2" method="get" action="frm_orc_revisar.php"  target="_self">
    <div align="left">Revisar Or&ccedil;amento Individual - Barcode n&ordm; 
<?
	if (isset($_GET["txtBarcode"])){
		$barcode=$_GET["txtBarcode"];
	}else{
		$barcode="";
	}
?>
      <input type="text" name="txtBarcode" value="<? print($barcode);?>">
      <input type="submit" name="Submit2" value="Revisar">
    </div>
  </form>
<hr><hr><hr>
<?
if ($barcode<>""){
	$res=mysql_query("select cp.cod as cod, modelo.cod_fornecedor as forn from cp inner join modelo on modelo.cod = cp.cod_modelo where barcode like'%$barcode'")or die(mysql_error());
	$row=mysql_num_rows($res);
	if ($row==0){
		die("Nenhum resultado encontrado para o barcode $barcode!");
	}
	$cp = mysql_result($res,$row-1,"cod");
	$forn = mysql_result($res,$row-1,"forn");
	$pre=mysql_query("select cod_decisao from orc where cod_cp = $cp AND cod_orc_pre_nota IS NULL") or die(mysql_error());
	$rowpre=mysql_num_rows($pre);
	//die($rowpre);
	if ($rowpre==0){
		die(" Não há orçamentos sem pré-notas para este barcode!");
	}
?> <h4>Caso queira o valor original da Tabela ent&atilde;o substitua o valor pela letra T</h4>
<p>ALTERE UMA LINHA POR VEZ E PRESSIONE ENTER</p>
<table width="831" border="1" align="center" class="style12">
	    <tr>
	      <td width="59" class="style4 style13"><span class="style18">C&oacute;digo</span></td>
	      <td width="223" class="style4 style13"><span class="style18">Descri&ccedil;&atilde;o</span></td>
	      <td width="18" class="style4 style13"><span class="style18">Qt</span></td>
	      <td width="107" class="style4 style13"><span class="style18">Valor</span></td>
	      <td width="49"><strong>Motivo</strong></td>
	      <td width="74"><strong>Destino</strong></td>
	      <td width="85" class="style4 style13"><span class="style18">Data do Or&ccedil;amento </span></td>
	      <td width="64" class="style4 style13"><span class="style18">Descisão</span></td>
		  <td width="94" class="style4 style13"><span class="style18">Colaborador Cadastra</span></td>
	    </tr> 
<?	  
	$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,orc.qt,DATE_FORMAT(data_cad, '%d-%m-%y') AS dtCad,orc.valor as valor,
	DATE_FORMAT(data_decisao, '%d/%m/%Y as %k:%i%s') AS dtAp, orc.cod as codorc, orc_motivo.descricao as motivo, destino.descricao as destino,
	orc_decisao.descricao as decisao, rh_user.nome as nome,orc.cod as codorc
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
	  <td>
	  <form name="form1" method="get" action="scr_orc_revisar.php">
	    <input name="txtValor" type="text" value="<? print ($linha["valor"]);?>" size="5" maxlength="10">
        <input type="submit" name="Submit" value="Alterar">	    
        <input type="hidden" name="item" value="<? print ($linha["codorc"]);?>">
	    <input type="hidden" name="barcode" value="<? print ($barcode);?>">
      </form></td>
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
<?	}?>
</table>
<?
		// Remendo provisório para permitir que o fábio corriga alguns orçamentos na Casas Bahia em 26/11/2007
		$resadm=mysql_query("select adm_geral from rh_user where cod = $id");
		$admGeral=mysql_result($resadm,0,"adm_geral");
		if($admGeral==1){
			print("<a href='frm_orcamento.php?cp=$cp&forn=$forn&msg=Alteração '>EDITAR ORÇAMENTO</a>");
		}

 }?>
</div>
</body>
</html>
