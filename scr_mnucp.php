<?
require_once("sis_valida.php");
require_once("sis_conn.php");
	if ($_GET["txtBarras"]==""){
		die ("C�d Barras N�O PREENCHIDO<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
	$barcode=$_GET["txtBarras"];
	$sql=mysql_query("SELECT barcode,cod_tec,cp.cod as os,cod_modelo,cod_defeito, day(data_barcode)as dia,
	  month(data_barcode)as mes,date_format(data_barcode,'%y') as ano,
	  data_pronto,serie,cod_solucao,filial,certificado,obs , defeito_reclamado, cod_posicao
	  from cp where barcode='$barcode'")or die("Erro no Camando SQL p�g scr_mnucp.php".mysql_error());
	$row=mysql_num_rows($sql);
	if ($row>0){
		$tec=mysql_result($sql,$row-1,"cod_tec");
		$cp=mysql_result($sql,$row-1,"os");
		$modelo=mysql_result($sql,$row-1,"cod_modelo");
		$defeito=mysql_result($sql,$row-1,"cod_defeito");
		$defeitoR=mysql_result($sql,$row-1,"defeito_reclamado");
		$diab=mysql_result($sql,$row-1,"dia");
		$mesb=mysql_result($sql,$row-1,"mes");
		$anob=mysql_result($sql,$row-1,"ano");
		$serie=mysql_result($sql,$row-1,"serie");
		$solucao=mysql_result($sql,$row-1,"cod_solucao");
		$filial=mysql_result($sql,$row-1,"filial");
		$certificado=mysql_result($sql,$row-1,"certificado");
		$obs=mysql_result($sql,$row-1,"obs");
		$dtpronto=mysql_result($sql,$row-1,"data_pronto");
		$posicao=mysql_result($sql,$row-1,"cod_posicao");
//se ainda n�o houve analize por nenhum t�cnico, redireciona os campos da entrada sen�o,
// se o produto j� est� pronto entao ERRO ("Print produto n�o pode ser cadastrado ou atualizado pois j� est� pronto") sen�o
//se o produto � do mesmo t�cnico que analizou, leva todas as vari�veis, mais a variavel cmdEnvia que determina para o script apos o frm que isto � uma atualiza��o			
			// Se na� existe t�cnico preenchido, signifiga que � a primeira vez que este barcode � acessado por um t�cnico
			if (empty($tec) || $tec==""){
				Header("Location:frm_cp.php?defeitoR=$defeitoR&cmdEnvia=Salvar&codModelo=$modelo&serie=$serie&codDefeito=$defeito&codSolucao=$solucao&diaB=$diab&mesB=$mesb&anoB=$anob&barcode=$barcode&cp=$cp&filial=$filial&certificado=$certificado&obs=$obs&posicao=$posicao");
			}else{
				if (!$dtpronto=="" || !empty($dtpronto)){
					// Se j� estiver pronto ent�o DIE (Busca no Banco nome do tecnico que deixou o produto pronto)
						Header("Location:con_cp.php?cp=$cp&msg=Consulta de &nalter=Este produto j� est� pronto!");					
					//$sql=mysql_query("select nome from user where cod=$tec")or die("Erro no Camando de consulta a tabela User pg scr_menucp.php".mysql_error());
					//$nometec=mysql_result($sql,0,"nome");
					//die("<h2><center>Este C�digo de Barras j� est� pronto desde $dtpronto e cadastrado pelo t�cnico:</h2><h1><font color='red'> $nometec<br>");
				}else{
					// Se n�o estiver pronto verifica se o t�cnico � o mesmo que abriu esta os
					if ($tec==$id){
						Header("Location:con_cp.php?cp=$cp&msg=Altera��o de ");	
						//Header("Location:frm_cp.php?cmdEnvia=Alterar&codModelo=$modelo&serie=$serie&codDefeito=$defeito&codSolucao=$solucao&diaB=$diab&mesB=$mesb&anoB=$anob&barcode=$barcode&cp=$cp&filial=$filial&certificado=$certificado&obs=$obs");
					}else{
					// Se n�o for o mesmo Redireciona para a consulta (sem permitir nenhuma altera��o na OS)
						Header("Location:con_cp.php?cp=$cp&msg=Consulta de &nalter=Este produto n�o est� pronto, por�m j� est� em an�lise com outro t�cnico!");	
						//$sql=mysql_query("select nome from user where cod=$tec")or die("Erro no Camando de consulta a tabela User pg scr_menucp.php".mysql_error());
						//$nometec=mysql_result($sql,0,"nome");
						//die("<h2><center>Este produto n�o est� pronto, por�m j� foi analizado pelo t�cnico: </h2><h1><font color='red'>$nometec");
					}
				}
			}
	}else{
			if ($barcode=="Sem Entrada"){
?>			
				<p align="center">Escolha uma das entradas disponiveis Abaixo  para a abertura do Controle de Produ&ccedil;&atilde;o</p>
				<p>&nbsp;</p>
				<table width="800" border="1" align="center">
				  <tr>
				    <td>Modelo</td>
					<td>Cliente</td>
				  </tr>
<?
//PEGAR SOMENTE PRODUTOS QUE N�O ESTEJAM EM USO E SEM PREENCHIMENTO!!!
	$sql="SELECT cp.cod AS cp, modelo.descricao AS modelo, modelo.cod AS codmodelo, cliente.descricao AS cliente
	FROM cp
	INNER JOIN modelo ON cp.cod_modelo = modelo.cod
	INNER JOIN cliente ON cliente.cod = cp.cod_cliente
	WHERE (barcode IS NULL)
	ORDER BY cliente
	LIMIT 0 , 30";
				$tot=0;
				$res=mysql_db_query("$bd",$sql,$Link) or die ("Erro na busca por modelos disponiveis!");
					while  ($linha=mysql_fetch_array($res)){
						$mod=$linha["modelo"];
				//		$qt=$linha["qt"];
						$codmodelo=$linha["codmodelo"];
						$cliente=$linha["cliente"];
						$cp=$linha["cp"];
						$link="<a href=frm_cp.php?codModelo=$codmodelo&cp=$cp>";
?>						
						<tr>
						<td><? print($link.$mod);?></td>
						<td><? print($link.$cliente);?></td>
						</tr>
<?
						$tot++;
					}
				print ("</table><h3><center>Existem <font color='red'>$tot </font>produtos sem entrada por C�d. de Barras Aguardando An�lise");
			}else{
			print ("<h3>N�o h� entrada para este c�digo de barras!</h3>");
			}
	}
?>