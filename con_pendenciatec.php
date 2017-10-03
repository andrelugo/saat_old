<?
//
// Aperfeiçoar esta tela para que seja exibido no campo STATUS, o Status do recebimento da Peça
//	Mostrar tambem em VERMELHO	 SITUAÇÕES ONDE O PRODUTO FOI ANALIZADO, NÃO ESTÁ PRONTO E NÃO HÁ NEM PEDIDO DE PEÇAS E NEM ORÇAMENTO
//
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["destino"])){$codDestino=$_GET["destino"];}else{$codDestino=0;}
if (isset($_GET["erro"])){$erro=$_GET["erro"];}
if (isset($_GET["contOk"])){$contOk=$_GET["contOk"];}else{$contOk=0;}
if (isset($_GET["contErro"])){$contErro=$_GET["contErro"];}else{$contErro=0;}
if (isset($_GET["contTot"])){$contTot=$_GET["contTot"];}else{$contTot=0;}

$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$tot = mysql_num_rows ($sqlCliente);
if ($tot>0){
	$cliente=mysql_result($sqlCliente,0,"cod");
}
?>
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-size: 18px}
-->
</style>
<body onLoad="document.form1.txtBarcode.focus();" topmargin="0">
<form name="form1" method="post" action="scr_pendenciatec.php">
  <p align="center">
  <span class="style3">
  <? if(isset($erro)){print("<h1><font color='red'>".$erro."</h1></font><br>");}?>
  </span><span class="style2"><strong>Use esta caixa para retirar do sistema produtos devolvidos sem conserto (N&atilde;o Prontos) </strong></span>  
  <table width="756" border="1" align="center">
    <tr>
      <td width="278"><span class="style4">C&oacute;digo de Barras:</span></td>
      <td width="468">
        <input name="txtBarcode" type="text" class="style2" id="txtBarcode" maxlength="20">
        <span class="style3">
        <input name="cmdEnviar" type="submit" id="cmdEnviar" value="Marcar como n&atilde;o pronto!" >
      </span>      </td>
    </tr>
    <tr>
      <td class="style4">Destino:</td>
      <td class="style4"><select name="cmbDestino" class="style2" id="select6"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from destino where cq=2";
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
?>
          </select></td>
	</tr><tr>
	  <td colspan="2">Retirados: <?print ($contOk);?>	  <input type="hidden" name="contErro" value="<?print ($contErro);?>">	  
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Erros: <?print ("<font color='red'>".$contErro."</font>");?>
	<input type="hidden" name="contOk" value="<?print ($contOk);?>">		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  Total de cliques: <?print ($contTot);?>
		  <input type="hidden" name="contTot" value="<?print ($contTot);?>"></td></tr>
  </table>
</form>
<hr>
<p align="center"><span class="style1">Produtos Pendentes de Conserto </span></p>
<p align="center"><span class="style1">AGUARDANDO SUA LIBERA&Ccedil;&Atilde;O! <br>
</span><span class="style4">Ordenados pela data do c&oacute;digo de barras! </span></p>
<div align="center">
  <table width="926" border="1" align="center">
    <tr>
      <td width="61"><div align="center">Barcode</div></td>
      <td width="61"><div align="center">Modelo</div></td>
      <td width="39"><div align="center" class="style5">Dias parado </div></td>
      <td width="293"><div align="center">Pe&ccedil;as Garantia </div></td>
      <td width="505"><div align="center">Orçamento</div></td>
      <td width="30"><div align="center">Status</div></td>
    </tr>
<?
$count = 0;
$sql="SELECT DATEDIFF(now(),data_entra) AS dd,modelo.descricao as descricao,cp.cod,barcode,orc_cliente
FROM cp inner join modelo on modelo.cod = cp.cod_modelo
where data_pronto is null and cod_tec=$id
order by dd;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
		$dias=$linha["dd"];
		$numOrc=$linha["orc_cliente"];
		if($dias>19){$bg="bordercolorlight=#FF0000";$cor="red";}else{$bg="bordercolorlight=#000000";$cor="black";} 

		$jv="con_cp.php?cp=$linha[cod]&msg=Alteração de ";
		print ("<tr onMouseOver=$jvA onMouseOut=$jvB $bg><td><a href=$jv> $linha[barcode]</td><td>$linha[descricao]</td><td><font color = $cor>$linha[dd]</font></td>");
		$count++;
		
		$sql="select peca.descricao as pc from pedido inner join peca on peca.cod = pedido.cod_peca where pedido.cod_cp = $linha[cod]";
		$res2=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta às peças garantia".mysql_error());
		$pecag="";
		while ($linha2 = mysql_fetch_array($res2)){
			$pecag = "$pecag $linha2[pc]<br>";
		}
			print ("<td> <div align='left'>$pecag</div></td>");

		$sqlo="select peca.descricao as pc from orc inner join peca on peca.cod = orc.cod_peca where orc.cod_cp = $linha[cod]";
		$res3=mysql_db_query ("$bd",$sqlo,$Link) or die ("Erro na string SQL de consulta às peças orçamento".mysql_error());
		$pecao="";
		while ($linha3 = mysql_fetch_array($res3)){
			$pecao = "$pecao $linha3[pc]<br>";
		}
////<td>		
			print ("<td> <div align='left'>$pecao</td></div>");
////</td>			
$sta=mysql_db_query ("$bd","select cod_decisao from orc where orc.cod_cp = $linha[cod]",$Link) or die (mysql_error());
$rows=mysql_num_rows ($sta);
if ($rows==0){
	$status="Orç. Vazio!";
}else{
	$decisao=mysql_result($sta,0,"cod_decisao");
	// Se a coluna Numero do Orçamento no Cliente (orc_cliente)Orçamento for NULL e o cliente não foor Casa Bahia então 
	if ($numOrc==NULL && $cliente==1){
		$status = "Ag. Dig. Orc.!";
	}else{
		if($decisao<>0){
			$sqlDesc=mysql_db_query ("$bd","select descricao from orc_decisao where cod=$decisao",$Link) or die (mysql_error());
			$status=mysql_result($sqlDesc,0,"descricao");
		}else{
			$status="Aguardando posicionamento do Cliente!";
		}
	}
}
////<td>			
			print ("<td> <div align='left'>$status</td></div>");
////</td>
}
?>
    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$count");?></strong></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
</html>