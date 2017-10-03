<?
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
$registro=$_POST["txtRegistro"];
$serie=$_POST["txtSerie"];
//$barcode=$_POST["txtBarcode"];
$barcode=trim($_POST["txtBarcode"]);
$orcamento=$_POST["txtOrcamento"];
$os=$_POST["txtOs"];
$ositem=$_POST["txtOsItem"];
$extrato=$_POST["cmbExtrato"];
if ($os=="" && $ositem=="" && $serie=="" && $registro=="" && $barcode=="" && $orcamento=="" && $extrato==""){
	die("<h1>Nenhum Campo selecionado para pesquisa!");
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

if ($os<>"" || $ositem<>""){
$where="cp.os_fornecedor like '%$os%' and cp.item_os_fornecedor like '%$ositem%'";
$order="order by cp.os_fornecedor";
$por=" Ordem de Serviço $os-$ositem";
}


if ($extrato<>""){
$where="cp.cod_extrato_mo = $extrato";
$order="order by barcode";
$por=" Extrato do Fornecedor cód.: $extrato";
}

$sql="SELECT modelo.descricao as descricao,serie,cp.cod_fechamento_reg as reg,cp.cod as cod,cp.barcode as barcode ,
orc_cliente as orc,cp.data_entra as entra,data_barcode,data_analize,data_sai,data_barcode,aprp_orc,folha_cq
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
where cp.cod_fornecedor=$id and $where
$order;";

$sql="SELECT modelo.descricao as descricao,serie,fechamento_reg.registro as reg,cp.cod as cod,cp.barcode as barcode ,
date_format(data_entra,'%d/%m/%y') as data_entra,orc_cliente as orc,date_format(data_barcode,'%d/%m/%y') as data_barcode,
date_format(data_analize,'%d/%m/%y') as data_analize,date_format(data_sai,'%d/%m/%y') as data_sai,folha_cq,os_fornecedor,
item_os_fornecedor,extrato_mo.descricao as extrato
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
left join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo
left join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
where $where
$order;";

//die($sql);

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
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<p align="center"><span class="style1">	Resultado da Consulta de Controle de Produ&ccedil;&atilde;o</span></p>
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
	  <td width="56"><div align="center">Extrato</div></td>
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
		<td><? print($linha["extrato"]);?></td>
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
