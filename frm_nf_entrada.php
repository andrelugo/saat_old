<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_POST["codNf"])){
	$codNf=$_POST["codNf"];
	$nota="";
}else{
	if (isset($_GET["nota"])){
		$nota=$_GET["nota"];
	}else{
		$sql="SELECT descricao FROM nf_entrada WHERE data_salva IS NULL ";
		$res=mysql_db_query ("$bd",$sql,$Link) or die ($sql);
		$row=mysql_num_rows ($res);
		if ($row>=1){
			$nota=mysql_result($res,0,"descricao");
		}else{
			$nota="";
		}
	}
	$codNf="";
}
?>
<html>
<head>
<title>Cadastro de Nota Fiscal de Entrada</title>
</head>
<body>
	<p align="center">Cadastro de Nota Fiscal de entrada Coletiva de Produtos </p>
<?
if ($nota==""){
	if ($codNf<>""){
		$sql="select cod_cliente,descricao,cnpj,
		day(data_emissao) as d1,month(data_emissao) as m1,year(data_emissao) as a1,
		day(data_recebe) as d2,month(data_recebe) as m2,year(data_recebe) as a2,
		transportador,vl_tot,obs from nf_entrada where cod = '$codNf'";
		$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());		
		
		$codCliente=mysql_result($res,0,"cod_cliente");
		
		$nota=mysql_result($res,0,"descricao");
		$cnpj=mysql_result($res,0,"cnpj");
		//adm = emissão
		$diaAdm=mysql_result($res,0,"d1");
		$mesAdm=mysql_result($res,0,"m1");
		$anoAdm=mysql_result($res,0,"a1");
		//adm = Recebe
		$diaDem=mysql_result($res,0,"d2");
		$mesDem=mysql_result($res,0,"m2");
		$anoDem=mysql_result($res,0,"a2");
		
		$responsavel=mysql_result($res,0,"transportador");
		$obs=mysql_result($res,0,"obs");
		$vlTot=mysql_result($res,0,"vl_tot");
		$btn="Alterar";
		
		$sql="select count(cod) as tot from cp where cod_nf_entrada = $codNf";
		$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());		
		$tot=mysql_result($res,0,"tot");
	}else{
		//$codCliente="";
		$nota="";
		$cnpj="";
		$responsavel="";
		$obs="";
		$vlTot="";
		$btn="Cadastrar";
		$tot=0;
	} ?>	
<form name="formCab" method="post" action="scr_nf_entrada.php">
  <table width="799" border="1" align="center">
        <tr>
          <td width="192">Cliente:</td>
          <td width="170">
		  <?
			if ($tot>0){
				$sql="select descricao from cliente where cod = $codCliente";
				$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());		
				$cliente=mysql_result($res,0,"descricao");
				print($cliente." <br>Impossivel alterar cliente pois já existem barcodes cadastrados para esta nota!
				<input type='hidden' name='cmbCliente' value='$codCliente'>");
			}else{

		  ?>
		  	<select name="cmbCliente" class="caixaPR1" id="cmbCliente">
	            <option value=""></option>
				<? $sql="select cod,descricao from cliente where revenda = 1";
				$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Cliente");
				while ($linha = mysql_fetch_array($res)){
					if (isset($codCliente)){
						if ($codCliente==$linha[cod]){
							print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
						}else{
							print ("<option value= $linha[cod] > $linha[descricao] </option>");
						}
					}else{
						print ("<option value= $linha[cod] > $linha[descricao] </option>");
					}
				}?>
            </select>
		  <?
		  }
		  ?>
		  </td>
		  
          <td width="181">N&uacute;mero da Nota:</td>
          <td width="228"><input name="txtNota" type="text" id="txtNota" size="25" maxlength="50" value="<? print($nota);?>"></td>
        </tr>
        <tr>
          <td>CNPJ do cliente: (na nota) : </td>
          <td><input name="txtCnpj" type="text" id="txtCnpj" size="25" maxlength="30" value="<? print($cnpj);?>"></td>
          <td>Data de Emiss&atilde;o:</td>
          <td><input name="txtDiaAdm" type="text" tabindex="22" id="txtDiaAdm"  value="<?if(isset($diaAdm) && $diaAdm <>'0'){print($diaAdm);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtDiaAdm.value.length==2){document.form1.txtMesAdm.focus();}">
/
  <input name="txtMesAdm" type="text" tabindex="23" id="txtMesAdm"  value="<?if(isset($mesAdm) && $mesAdm <>'0'){print($mesAdm);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesAdm.value.length==2){document.form1.txtAnoAdm.focus();}">
/
<input name="txtAnoAdm" type="text" tabindex="24" id="txtAnoAdm"  value="<?if(isset($anoAdm) && $anoAdm <>'0'){print($anoAdm);}?>" size="4" maxlength="4"></td>
        </tr>
        <tr>
          <td>Produtos com Barcode? </td>
          <td><input name="rd" type="radio" value="0">
            Sim 
            ----
              <input name="rd" type="radio" value="1">
            N&atilde;o</td>
          <td>Data de Recebimento: </td>
          <td><input name="txtDiaDem" type="text" tabindex="25" id="txtDiaDem2"  value="<?if(isset($diaDem) && $diaDem <>'0'){print($diaDem);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtDiaDem.value.length==2){document.form1.txtMesDem.focus();}">
/
  <input name="txtMesDem" type="text" tabindex="26" id="txtMesDem2"  value="<?if(isset($mesDem) && $mesDem <>'0'){print($mesDem);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesDem.value.length==2){document.form1.txtAnoDem.focus();}">
/
<input name="txtAnoDem" type="text" tabindex="27" id="txtAnoDem2"  value="<?if(isset($anoDem) && $anoDem <>'0'){print($anoDem);}?>" size="4" maxlength="4"></td>
        </tr>
        <tr>
          <td>Respons&aacute;vel pelo Transporte : </td>
          <td><input name="txtResponsavel" type="text" id="txtResponsavel" size="25" maxlength="30" value="<? print($responsavel);?>"></td>
          <td>Valor Total da Nota : </td>
          <td>R$:
            <input name="txtValor" type="text" id="txtValor" size="15" maxlength="12" value="<? print($vlTot);?>"></td>
        </tr>
		<tr>
		  <td colspan="4">Observa&ccedil;&otilde;es:
	      <textarea name="txtObs" cols="110" id="txtObs" value="<? print($obs);?>"></textarea></td>
	</tr>
  </table>
        <div align="center">
          <input name="Cadastrar" type="submit" id="Cadastrar" value="<? print($btn);?>">
<? if ($codNf<>""){
		print("<input type='hidden' value='$codNf' name='cod'>");
	}
?>
</div>
</form>
	<br><br><br><br>
	<br>
	<br><br><br><br>
<!--
<form action="frm_nf_entrada.php" method="get">
  <div align="center">Alterar nota fiscal n&ordm;
    <input name="nota" type="text" id="nota">
    <input type="submit" name="Submit3" value="Alterar Nota">
  </div>
</form>
-->
<?
}else{
if (isset($_GET["erro"])){print($_GET["erro"]);}
$sql="select nf_entrada.data_salva as data_salva, nf_entrada.cod as codnf,data_emissao,transportador,obs,cliente.descricao as cliente,cliente.cod as codCliente,endereco,cpf_cnpj,rg_ie,telefone
from nf_entrada inner join cliente on cliente.cod = nf_entrada.cod_cliente
where nf_entrada.descricao = '$nota'";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());		

	$row=mysql_num_rows($res);
	if ($row==0){
		$erro="Nenhum resultado encontrado para a nota fiscal nº $nota";			
	}else{
		$salva=mysql_result($res,0,"data_salva");	
		if ($salva<>NULL){
			$erro="<h1><font color = red>Erro: Esta nota fiscal $nota foi salva em $salva. Impossível alterar dados!!!	";
		}
	}
	if (isset($erro)){
		//die("Location:frm_nf_entrada.php?erro=$erro");
		die("$erro");
		exit;		
	}
$cliente=mysql_result($res,0,"cliente");
$codCliente=mysql_result($res,0,"codCliente");
$endereco=mysql_result($res,0,"endereco");
$telefone=mysql_result($res,0,"telefone");
$dtEm=mysql_result($res,0,"data_emissao");
$cnpj=mysql_result($res,0,"cpf_cnpj");
$ie=mysql_result($res,0,"rg_ie");
$responsavel=mysql_result($res,0,"transportador");
$obs=mysql_result($res,0,"obs");
$codNf=mysql_result($res,0,"codnf");
?>
	
	
	
	<table width="799" border="1" align="center">
		<tr>
			<td> Nota:</td>
			<td><? print($nota);?></td>
			<td>Data Emiss&atilde;o: </td>
			<td><? print($dtEm);?></td>
		</tr>
        <tr>
          <td width="192">Raz&atilde;o Social :</td>
          <td colspan="3"><? print($cliente);?>&nbsp;</td>
        </tr>
		<tr>
          <td width="192">Endere&ccedil;o:</td>
          <td colspan="3"><? print($endereco);?>&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td>CNPJ </td>
          <td width="170"><? print($cnpj);?></td>
          <td width="181">I.E.:</td>
          <td width="228"><? print($ie);?></td>
        </tr>
        <tr>
          <td>Telefone:</td>
          <td><? print($telefone);?></td>
          <td>Respons&aacute;vel transporte : </td>
          <td><? print($responsavel);?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><form name="form1" method="post" action="frm_nf_entrada.php">
            <div align="center">
              <input type="submit" name="Submit2" value="Alterar Cabe&ccedil;alho">
            </div>
			<input type="hidden" name="codNf" value="<? print($codNf);?>">
          </form></td>
          <td>&nbsp;</td>
        </tr>
		<tr>
		  <td colspan="4">Observa&ccedil;&otilde;es:	<? print($obs);?> </td>
	</tr>
</table>
	

	<div align="center">
	  <p>Itens:
      </p>
	</div>
	<form action="scr_nf_entrada_item.php" name="formItem" method="post">
	  <div align="center">Modelo
	    <select name="cmbModelo" class="caixaPR1" id="cmbModelo">
	              <option value=""></option>
	      <?	  
	$sql="select * from modelo";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Modelo");
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
	?>
	    </select>
	 - Qtantidade 
	 <input name="txtQt" type="text" size="4" maxlength="4"> 
	 - R$ Unit&aacute;tio 
	 <input name="txtValor" type="text" size="15" maxlength="12">
	 <input type="submit" name="Submit" value="Inserir">
	 <input type="hidden" name="codNf" value="<? print($codNf);?>">
	 <input type="hidden" name="nota" value="<? print($nota);?>">
	  </div>
</form>
<div align="center"></div>
    <table width="800" border="1" align="center">
	      <tr class="Cabe&ccedil;alho">
	        <td width="50">Item</td>
	        <td width="46">Qtdade</td>
	        <td width="471">Modelo</td>
	        <td width="90">R$ Unitário</td>
		    <td width="109">R$ Total</td>
	    </tr>
	    <?
	$count = 0;
	$qtTot=0;
	$rsTot=0;
	$rsTot=0;
	$sql="SELECT nf_entrada_itens.cod as codItem,nf_entrada_itens.qt AS qt, modelo.descricao AS modelo, modelo.marca AS marca, modelo.tipo AS descricao, nf_entrada_itens.vl_unit AS unit
FROM nf_entrada_itens
INNER JOIN modelo ON modelo.cod = nf_entrada_itens.cod_modelo
INNER JOIN nf_entrada ON nf_entrada.cod = nf_entrada_itens.cod_nf_entrada
WHERE nf_entrada.descricao ='$nota'";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela de notas fiscais de entrada".mysql_error());
	while ($linha = mysql_fetch_array($res)){
	$codItem=$linha["codItem"];
	$l1=$linha["qt"];
	$l2=$linha["marca"]." ".$linha["modelo"]." ".$linha["descricao"];
	$l3=$linha["unit"];
	$l4=$l3*$l1;
	?><tr>
	<td>&nbsp;<? print($count);?></td>
	<td>&nbsp;<? print($l1);?></td>
	<td>&nbsp;<? print($l2);?></td>
	<td>&nbsp;<? print($l3);?></td>
	<td>&nbsp;<? print($l4);?></td>
	<td><a href='<? print ("scr_exclui_nf_entrada_item.php?cod=$codItem&tabela=nf_entrada_itens&nota=$nota");?>'>
	<img src='img/botoes/b_drop.png' width='16' height='16' border='0' title='Pressione para excluir esta Linha!'></a></td>
	</tr>
	<?
	$count++;
	$qtTot=$qtTot+$l1;
	$rsTot=$rsTot+$l4;
	}
	?>
	    <tr class="style3"><td class="Cabe&ccedil;alho">TOTAL</td>
	    <td class="style3">
		<span class="style3"><? print("$qtTot");?></span></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><? print("$rsTot");?></td>
		</tr>
	</table>
    <form name="form2" method="get" action="scr_salva_nf_entrada.php">
      <div align="center">
<? 
if (isset($_GET["erroSalvar"])){
	$erro=$_GET["erroSalvar"];
	print($erro);
}
?>
        <input type="submit" name="Submit4" value="Salvar Nota">
        <input type="hidden" name="rsTot" value="<? print ("$rsTot");?>">
        <input type="hidden" name="nota" value="<? print ("$nota");?>">
        <input type="hidden" name="codCliente" value="<? print ("$codCliente");?>">
</div>
    </form>
<?
}
?>	
</body>
</html>
