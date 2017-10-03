<script>
function abrir(pesq,forn,modelo){
	var url = "";
	if (document.form1.txtDescricao.value != ""){
		pesq=document.form1.txtDescricao.value;
		url="pes_peca.php?desc=" + pesq + "&forn=" + forn + "&orcamento=0&garantia=1&cortesia=0&modelo=" + modelo;
		janela=window.open(url, "janela","toolbar=no,location=no,status=no,scrollbars=yes,directories=no,width=500,height=400,top=18,left=0");
		janela.focus();
	}else{
	//alert ("Nenhuma descrição foi digitada!");
	}
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cp"])){
	$cp=$_GET["cp"];
	$msg=$_GET["msg"];
	$forn=$_GET["forn"];
	$modelo=$_GET["modelo"];
	if (isset($_GET["erro"])){
		$erro=$_GET["erro"];
		$codPeca=$_GET["codPeca"];
		$codDefeito=$_GET["defeito"];
		$codServico=$_GET["servico"];
		$descricao=$_GET["descricao"];
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
.style5 {font-size: 14px}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>
<body onLoad="document.form1.txtDescricao.focus();">
<div align="center" class="style1">
  <p>Pedido de Pe&ccedil;as em Garantia <br>
    para o Controle de Produção n. <?print($cp);?></p>
  <p><? if (isset($erro)){print("<h2><font color='red'>".$erro);}?>&nbsp;</p>
  <form name="form1" method="post" action="scr_pedido.php">
    <table width="805" border="0">
      <tr>
        <td width="443" class="style4">Descri&ccedil;&atilde;o</td>
        <td width="35" class="style4">Qtdade</td>
        <td width="127" class="style4">Defeito</td>
        <td width="93" class="style4">Servi&ccedil;o</td>
      </tr>
      <tr>
        <td><input name="txtDescricao" type="text" value="<?if (isset($descricao)){print($descricao);}?>" size="50" maxlength="50" alt="Digite o primeiro nome de uma peça e clique em pesquisar para efetuar uma pesquisa!" onBlur="javascript: abrir(document.form1.txtDescricao,<?print($forn);?>,<?print($modelo);?>);">
        <span class="style4"><img src="img/botoes/b_search.png" title="Preencha a caixa descrição com uma palavra e clique aqui!" width="16" height="16"  onclick='javascript: abrir(document.form1.txtDescricao,<?print($forn);?>,<?print($modelo);?>);'> </span></td>
        <td><input name="txtQt" type="text" id="txtQt" value="1" size="1" maxlength="1"></td>
        <td><select name="cmbPecaDefeito" class="style5" id="cmbPecaDefeito"  tabindex="5" >
            <option value="0"></option>
<?
$sql=mysql_query("select cod_telecontrol from fornecedor where cod=$forn and cod_telecontrol is not null");
$rowsT=mysql_num_rows($sql);

if($rowsT==0){
	$sql="select * from peca_defeito where ativo = 1";
}else{
	$sql="select * from peca_defeito where cod_fornecedor=$forn and ativo = 1";
}
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Peca Defeito");
while ($linha = mysql_fetch_array($res)){
		if ($linha["seleciona"]==1){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
}
?>
        </select></td>
        <td><select name="cmbPecaSolucao" class="style5" id="cmbPecaSolucao"  tabindex="5" >
            <option value="0"></option>
<?
if($rowsT==0){
	$sql="select * from peca_servico where ativo = 1";
}else{
	$sql="select * from peca_servico where cod_fornecedor=$forn and ativo = 1";
}
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Peca Defeito");
while ($linha = mysql_fetch_array($res)){
		if ($linha["seleciona"]==1){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
}
?>
        </select></td>
      </tr>
    </table>
    <span class="style1">C&oacute;digo
    <input name="txtCod" type="text" class="style1" value="<?if (isset($codPeca)){print($codPeca);}?>" size="6" maxlength="6">
    </span>
    <input type="hidden" name="cp" value="<?print($cp);?>">
	<input type="hidden" name="msg" value="<?print($msg);?>">
	<input type="hidden" name="forn" value="<?print($forn);?>">
	<input type="hidden" name="modelo" value="<?print($modelo);?>">
    <input name="Submit" type="submit" class="style1" value="Inserir" title="Apó">
  </form>
  <hr>
  <hr>
  <table width="806" border="1">
    <tr>
      <td width="81" ><strong>C&oacute;digo</td>
      <td width="306"><strong>Descri&ccedil;&atilde;o</td>
      <td width="139"><strong>Defeito</td>
      <td width="252" ><strong>Serviço</td>
    </tr> 
<?	  
$hoje=(date("d-m-y"));
$sql="select peca.cod_fabrica as cod, peca.descricao as descricao,peca_defeito.descricao as defeito,
peca_servico.descricao as servico, date_format(data_cad,'%d-%m-%y') as cad,pedido.cod as codped
from pedido inner join
peca_defeito on peca_defeito.cod = pedido.cod_peca_defeito inner join
peca_servico on peca_servico.cod = pedido.cod_peca_servico inner join
peca on peca.cod = pedido.cod_peca
where pedido.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Pedidos".mysql_error());
while ($linha = mysql_fetch_array($res)){
	$cad=$linha["cad"];
	$codped=$linha["codped"];
	if ($cad==$hoje){
		print ("<tr class='style11'>
		<td>$linha[cod]</td> 
		<td>$linha[descricao]</td>
		<td>$linha[defeito]</td> 
		<td>$linha[servico]</td>
		<td><a href='scr_excui.php?codped=$codped&cp=$cp&forn=$forn&msg=$msg&dest=frm_pedido.php&tabela=pedido&modelo=$modelo'>
		<img src='img/botoes/b_drop.png' width='16' height='16' border='0' title='Pressione para excluir esta Linha!'></a>
		</td></tr>");
	}else{	
		print ("<tr class='style11'><td>$linha[cod]</td> <td>$linha[descricao]</td> <td>$linha[defeito]</td> <td>$linha[servico]</td></tr>");
	}	
}
?>
</table>
  <p><a href="con_cp.php?cp=<?print($cp."&msg=".$msg);?>">Voltar</a> </p>
</body>
</html>
