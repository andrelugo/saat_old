<?
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=trim($_POST["txtCp"]);
$registro=trim($_POST["txtRegistro"]);
$serie=trim($_POST["txtSerie"]);
$barcode=trim($_POST["txtBarcode"]);
$orcamento=trim($_POST["txtOrcamento"]);
$folha=trim($_POST["txtFolha"]);
$os=trim($_POST["txtOs"]);
$ositem=trim($_POST["txtOsItem"]);
$entrada=$_POST["txtaEntrada"]."-".$_POST["txtmEntrada"]."-".$_POST["txtdEntrada"];
$analize=$_POST["txtaAnalize"]."-".$_POST["txtmAnalize"]."-".$_POST["txtdAnalize"];
$saida=$_POST["txtaSaida"]."-".$_POST["txtmSaida"]."-".$_POST["txtdSaida"];
if ($cp=="" && $os=="" && $ositem=="" && $folha=="" && $serie=="" && $registro=="" && $barcode=="" && $orcamento=="" && $entrada=="--" && $analize=="--" && $saida=="--"){
	die("<h1>Nenhum Campo selecionado para pesquisa!");
}
if ($cp<>""){
$where="cp.cod like '%$cp%'";
$order=" order by cp.cod";
$por=" Controle de Produção $cp";
}
if ($registro<>""){
$where="fechamento_reg.registro like '%$registro%'";
$order=" order by fechamento_reg.registro";
$por=" Registro de Saída $registro";
}
if ($serie<>""){
$where="cp.serie like '%$serie%'";
$order="order by cp.serie";
$por=" Numero de Série $serie";
}
if ($orcamento<>""){
$where="cp.orc_cliente like '%$orcamento%'";
$order="order by cp.orc_cliente";
$por=" Numero de Orçamento $orcamento";
}
if ($barcode<>""){
$where="cp.barcode like '%$barcode%'";
$order="order by cp.barcode";
$por=" Código de Barras $barcode";
}
if ($entrada<>"--"){
$where="cp.data_entra like '%$entrada%'";
$order="order by cp.data_entra";
$por=" Código de Data de Entrada $entrada";
}
if ($analize<>"--"){
$where="cp.data_analize like '%$analize%'";
$order="order by cp.data_analize";
$por=" Código de Data de Analise $analize";
}
if ($saida<>"--"){
$where="cp.data_sai like '%$saida%'";
$order="order by cp.data_sai";
$por=" Código de Data de Saída $saida";
}
if ($folha<>""){
$where="cp.folha_cq like '%$folha%'";
$order="order by cp.folha_cq";
$por=" Folha de Controle de Qualidade $folha";
}
//Consultas por OS
if ($os<>"" && $ositem<>""){
$where="cp.os_fornecedor like '%$os%' and cp.item_os_fornecedor = $ositem";
$order="order by cp.os_fornecedor";
$por=" Ordem de Serviço $os-$ositem";
}

if ($os<>"" && $ositem==""){
$where="cp.os_fornecedor like '%$os%'";
$order="order by cp.os_fornecedor";
$por=" Ordem de Serviço $os-$ositem";
}

if ($os=="" && $ositem<>""){
$where="cp.item_os_fornecedor = $ositem";
$order="order by cp.os_fornecedor";
$por=" Ordem de Serviço $os-$ositem";
}
//FIM Consultas por OS
$sql="SELECT modelo.descricao as descricao,serie,fechamento_reg.registro as reg,cp.cod as cod,cp.barcode as barcode ,
orc_cliente as orc,date_format(data_barcode,'%d/%m/%y') as data_barcode,data_analize,date_format(data_sai,'%d/%m/%y') as data_sai,folha_cq,os_fornecedor,item_os_fornecedor
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
left join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
where $where
$order;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta".mysql_error()."<br>".$sql);
$row=mysql_num_rows($res);
if ($row==1){
	$cod=mysql_result($res,0,"cod");
	Header("Location:con_cp.php?cp=$cod&msg=Consulta de ");
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style4 {
	font-size: 14px;
	font-style: italic;
}
.style5 {font-size: 9px}
body {
	background-color: <?print($bgcolor);?>;
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>
<body>
<p align="center"><span class="style1">Resultado da Consulta de Controle de Produ&ccedil;&atilde;o</span></p>
<p align="center">por:<?print($por);?><span class="style4"></span></p>
<div align="center">
  <table width="808" border="1">
    <tr>
      <td width="135"><div align="center">C.P.</div></td>
      <td width="135"><div align="center">Modelo</div></td>
      <td width="80"><div align="center">O. S. </div></td>
      <td width="98"><div align="center">S&eacute;rie</div></td>
      <td width="83"><div align="center">Barcode</div></td>
      <td width="106"><div align="center">Data Barcode </div></td>	  
	  <td width="86"><div align="center">Finaliza&ccedil;&atilde;o</div></td>
	  <td width="68"><div align="center">Orcamento</div></td>
	  <td width="38"><div align="center">Folha Cq</div></td>
	  <td width="56"><div align="center">Registro Saida</div></td>
    </tr>
<?
if ($row<>0){
$count = 0;
	while ($linha = mysql_fetch_array($res)){
		$jv="con_cp.php?cp=$linha[cod]&msg=Consulta de ";
?>	
		<tr onMouseOver=<? print ($jvA);?>onMouseOut=<? print ($jvB);?>>
		<td><a href=<? print($jv);?>><? print($linha["cod"]);?> </a></td>
		<td><a href=<? print($jv);?>><? print($linha["descricao"]);?> </a></td>
		<td><a href=<? print($jv);?>><? print($linha["os_fornecedor"]."-".$linha["item_os_fornecedor"]);?></a></td>
		<td><a href=<? print($jv);?>> <? print($linha["serie"]);?></a></td>
		<td><a href=<? print($jv);?>> <? print($linha["barcode"]);?></a></td>
		<td><? print($linha["data_barcode"]);?></td>
		<td><? print($linha["data_sai"]);?></td>
		<td><? print($linha["orc"]);?></td>
		<td><? print($linha["folha_cq"]);?></td>
		<td><? print($linha["reg"]);?></td>
<?	
		$count++;
	}
?>
	    <tr>
	      <td><strong>TOTAL</strong></td>
	      <td colspan="2"><strong><?print("$count");?></strong></td>
	    </tr>
	  </table>
<?
}else{
print ("<h1>Nenhum resultado encontrado!</h1>");
}
?>
</div>
<p align="center">&nbsp;</p>
</body>
</html>
