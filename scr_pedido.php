<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_POST["cp"])){
	$cp=$_POST["cp"];
	$forn=$_POST["forn"];
	$modelo=$_POST["modelo"];	
	$msg=$_POST["msg"];
	if (!$_POST["txtCod"]==0){$codPeca=$_POST["txtCod"];}else{$erro2="C�digo da Pe�a n�o preenchido!";$codPeca=0;}	
	if (!$_POST["cmbPecaDefeito"]==0){$defeito=$_POST["cmbPecaDefeito"];}else{$erro="Defeito n�o selecionado!";$defeito=0;}	
	if (!$_POST["cmbPecaSolucao"]==0){$servico=$_POST["cmbPecaSolucao"];}else{$erro="Servi�o n�o preenchido!";$servico=0;}	
	if (!$_POST["txtQt"]==""){$qt=$_POST["txtQt"];}else{$erro="Quantidade n�o preenchida!";$qt="";}	
	if (isset($_POST["txtDescricao"])){$descricao=$_POST["txtDescricao"];}
}
if (isset($erro2)){
	$erro=$erro2;
}else{
	if ($forn==1){
		//Pesquisa na tabela pe�a com a tab. MUP o c�digo (Chave prim�ria) desejado
		// Se achar verifica � garantia
		// Se n�o achar :
		$sql=mysql_query("select cod, garantia, descricao from peca inner join mup on mup.cod_peca = peca.cod 
						  where cod_fabrica=$codPeca and  mup.cod_modelo=$modelo")or die("Erro no Camando SQL scr_peca.php".mysql_error());
		$row=mysql_num_rows($sql);
		if($row==0){
			// Se n�o achar na primeira consulta pesquisa se a pe�a existe
			// Se exite avisa ao usus�rio que n�o est� cadastrada para o modelo no cadastro sen�o avisa que n�o existe.
			$sql2=mysql_query("select descricao from peca where cod_fabrica=$codPeca")or die("Erro no Camando SQL scr_peca.php".mysql_error());
			$row=mysql_num_rows($sql2);
			if($row==0){
				$erro="O c�digo $codPeca n�o existe no Banco de Dados!";
			}else{
				$sql=mysql_query("select descricao from modelo where cod = $modelo");
				$descModelo=mysql_result($sql,0,"descricao");
				$descPeca=mysql_result($sql2,0,"descricao");
				$erro="A pe�a $descPeca n�o est� cadastrada para o modelo $descModelo na tabela da F�brica<br><h1>Caso este componente seja utilizado neste modelo, Favor informar ao seu gerente para que ele comunique a f�brica URGENTEMENTE!<br>Obrigado!";
			}	
		}else{
			$codP=mysql_result($sql,0,"cod");
			$gar=mysql_result($sql,0,"garantia");
	
			if ($gar==0){
				$descPeca=mysql_result($sql,0,"descricao");
				$erro="A pe�a $descPeca n�o est� cadastrada como componente funcional portanto n�o pode ser solicitada em Garantia. Caso exista algum equivoco no cadastro ou alguma d�vida informe seu gerente!";
			}
		}
	}
	if ($forn<>1){
		//
		$sql=mysql_query("select cod, garantia, descricao from peca
						  where cod_fabrica=$codPeca and  cod_fornecedor=$forn")or die("Erro no Camando SQL scr_peca.php".mysql_error());
		$row=mysql_num_rows($sql);
		if($row==0){
			// Se n�o achar na primeira consulta pesquisa se a pe�a existe
			// Se exite avisa ao usus�rio que est� cadastrada para o fabricante no cadastro sen�o avisa que n�o existe.
			$sql2=mysql_query("select descricao from peca where cod_fabrica=$codPeca")or die("Erro no Camando SQL scr_peca.php".mysql_error());
			$row=mysql_num_rows($sql2);
			if($row==0){
				$erro="O c�digo $codPeca n�o existe no Banco de Dados!";
			}else{
				$sql=mysql_query("select descricao from fornecedor where cod = $forn");
				$descForn=mysql_result($sql,0,"descricao");
				$descPeca=mysql_result($sql2,0,"descricao");
				$erro="A pe�a $descPeca n�o est� cadastrada para o fornecedor $descForn na tabela da F�brica<br><h1>Caso este componente seja utilizado neste fabricante, Favor informar ao seu gerente URGENTEMENTE!<br>Obrigado!";
			}	
		}else{
			$codP=mysql_result($sql,0,"cod");
			$gar=mysql_result($sql,0,"garantia");
			if ($gar==0){
				$descPeca=mysql_result($sql,0,"descricao");
				$erro="A pe�a $descPeca n�o est� cadastrada como componente funcional portanto n�o pode ser solicitada em Garantia. Caso exista algum equivoco no cadastro ou alguma d�vida informe seu gerente!";
			}
		}
		
	}
}
if (isset($erro)){
	Header("Location:frm_pedido.php?cp=$cp&erro=$erro&codPeca=$codPeca&defeito=$defeito&servico=$servico&descricao=$descricao&forn=$forn&msg=$msg&modelo=$modelo");
}else{
	$sql="insert into pedido (cod_peca,cod_cp,qt,cod_peca_defeito,cod_peca_servico,cod_colab,data_cad)
	values ('$codP','$cp','$qt','$defeito','$servico',$id,now())";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inser��o dos dados do Pedido de Pe�as $sql ".mysql_error());		
// Atualizar base de dados com uma coluna estoque penha e outra estoque garantia onde controlaremos sua movimenta��o com a linha debaixo
//	$sqlC=mysql_query("update peca set qt=qt-1 where cod = $codP");
	Header("Location:frm_pedido.php?cp=$cp&forn=$forn&msg=$msg&modelo=$modelo");
}
?>