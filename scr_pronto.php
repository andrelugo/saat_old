<?
// Esta p�gina deve permitir que um equipamento seja marcado como pronto somente se :
// OS OR�AMENTOS DO CONTROLE DE PRODUT��O POSSUIREM UMA DEFINI��O
//		SE ESTA DEFINI��O FOR A APROVA��O ENT�O ELE DEVE ATUALIZAR A TABELA ESTOQUE A PARTIR DOS DADOS DA TABELA OR�AMENTO
//		SE O OR�AMENTO N�O FOI APROVADO ENT�O ELE SIMPLESMENTE GERA UM ALERTA PARA QUE O T�CNICO RETIRE TODOS OS ACESS�RIOS 
//            OR�ADOS POIS O CLIENTE N�O APROVOU O SERVI�O!!!!
//				COLOCAR ESTA MENSAGEM NA TELA DO CONTROLER DE QUALIDADE TAMBEM!!!
//		Colocar esta mensagem tambem na tela de sa�da de produtos pela tela de pend�ncias 
// em 30 de maio de 2006
//
// Ao realizar a baixa no estoque o usuario deve ser direcionado para uma p�gina de resposta que o informe caso o estoque
// do item baixado esja abaixo no estoque minimo.
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=$_POST["cp"];

$fin=mysql_query("select data_pronto from cp where cod=$cp");
$sai=mysql_result($fin,0,"data_pronto");
if (!$sai==NULL || !$sai==0){
	die("<h1> ERRO: CONTROLE DE PRODU��O J� MARCADO COMO PRONTO EM $sai");
}
$res=mysql_db_query ("$bd","SELECT cod_decisao,cod_peca,qt from orc WHERE cod_cp=$cp order by cod_decisao",$Link) or die ($sql);
$totOrc=mysql_num_rows($res);
if ($totOrc==0){
	$pronto=1;
	$msg="<H1><font color='blue'>Nenhum item foi or�ado para este produto. <BR>
	Produto marcado como PRONTO!</font></H1>";
}else{
	$codDecisao=mysql_result($res,0,"cod_decisao");
	if ($codDecisao==0){
		$msg="<H1><font color='RED'>ERRO: O OR�AMENTO PARA ESTE PRODUTO AINDA N�O FOI DEFINIDO PELO CLIENTE!<BR>
		PRODUTO N�O MARCADO COMO PRONTO!</font></H1>";
		$pronto=0;
	}else{
		$flag=1;
		$msg="Atualizando tabela ESTOQUE para os itens aprovados!";
		$pronto=1;
	}
}
?><html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>
<body>
<?
print($msg);
if (isset($flag)){?>
  <table width="800" border="1" align="center">
    <tr>
      <td width="227"><div align="center">Pe�a</div></td>
      <td width="70"><div align="center">Quantidade</div></td>
      <td width="69"><div align="center">Or�amento</div></td>
      <td width="239"><div align="center">Obs.</div></td>
    </tr>
	<? // $res � definida na query no topo deste script
	$count = 0;
	$res=mysql_db_query ("$bd","SELECT cod_decisao,cod_peca,qt from orc WHERE cod_cp=$cp order by cod_decisao",$Link) or die ("SQL 1".$sql);
	while ($linha = mysql_fetch_array($res)){
		$codDecisao=$linha["cod_decisao"];
		$qtV=$linha["qt"];
		$codPeca=$linha["cod_peca"];
		
		$sqlDecisao=mysql_query("SELECT descricao,aprova,reprova from orc_decisao WHERE cod=$codDecisao")or die("SQL 2".mysql_error());
		$desDescricao=mysql_result($sqlDecisao,0,"descricao");
		$ap=mysql_result($sqlDecisao,0,"aprova");
		$rp=mysql_result($sqlDecisao,0,"reprova");
		if($ap==1 && $rp==1){die("<h1>ERRO: ATEN��O EXISTE UMA INCONSIST�NCIA NO SISTEMA <BR>NA TABELA ORC_DECISAO, A DECISAO $descricao APROVA E REPROVA UM OR�AMENTO AO MESMO TEMPO<BR> AVISE A GER�NCIA!</h1>");exit;}
		if($ap==1){
			$sqlSaldo=mysql_query("select qt,qtmin,descricao from peca where cod=$codPeca")or die("SQL 3".mysql_error());
			$qt=mysql_result($sqlSaldo,0,"qt");
			$qtmin=mysql_result($sqlSaldo,0,"qtmin");
			$descPeca=mysql_result($sqlSaldo,0,"descricao");
// nesta linha posso travar a saida de um produto caso haja insufici�ncia no estoque para o item aprovado!
			if ($qt==NULL || $qt<=0){$msg2="<h3>Existe um erro nas informa��es para este item no sistema a quantidade de pe�as atual � $qt";}
			if ($qt<=$qtmin){$msg2="<font color='red'>Estoque abaixo do m�nimo a quantidade de pe�as atual � $qt</font>";}
			$sql=mysql_query("update peca set qt=qt-$qtV where cod=$codPeca")or die("SQL 4".mysql_error());
			$msg3="TABELA ESTOQUE ATUALIZADA!!!";
		}
		if($rp==1){
			$msg2="<h1>Item REPROVADO cuidado para n�o liber�-lo!";
			$msg3="<H1>ATEN��O H� ITENS REPROVADOS NO OR�AMENTO DESTE PRODUTO! <BR>
			CUIDADO PARA N�O LIBERAR ESTES ITENS COM ESTE PRODUTO!</H1>";
		}
		?>
		<tr>
		<td><? print($descPeca);?></td>
		<td><? print($linha["qt"]);?></td>
		<td><? print($desDescricao);?></td>
		<td><? print($msg2);?></td>
		</tr>
		<?		$count = $count+$qt;
	}
	?>
	  <tr><td class="style3"><strong>TOTAL</strong></td>
	  <td class="style3"><strong><? print("$count");?></strong></td></tr>
</table>
	<?
}

if ($pronto==1){
	$Sqlto=mysql_query("SELECT sum( orc.qt * orc.valor ) AS tot FROM orc INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao WHERE aprova =1 AND orc.cod_cp =$cp");
	$totOrc=mysql_result($Sqlto,0,"tot");
// corrigir total orc	
	$sql="update cp set data_pronto=now(), total_orc=$totOrc where cod=$cp";
	$sql="update cp set data_pronto=now() where cod=$cp";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inser��o da data Pronto! $sql ".mysql_error());
}
if (isset($msg3)){
	print($msg3);
}
?>
<div align="center" class="style1"><A href="mnu_cp.php">VOLTAR PARA PRODU��O</A>
</div>
</body>
</html>