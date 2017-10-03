<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_POST["cp"])){
	$cp=$_POST["cp"];
	$forn=$_POST["forn"];
	$modelo=$_POST["modelo"];	
	$msg=$_POST["msg"];
	if (!$_POST["txtCod"]==0){$codPeca=$_POST["txtCod"];}else{$erro2="Código da Peça não preenchido!";$codPeca=0;}	
	if (!$_POST["cmbPecaDefeito"]==0){$defeito=$_POST["cmbPecaDefeito"];}else{$erro="Defeito não selecionado!";$defeito=0;}	
	if (!$_POST["cmbPecaSolucao"]==0){$servico=$_POST["cmbPecaSolucao"];}else{$erro="Serviço não preenchido!";$servico=0;}	
	if (!$_POST["txtQt"]==""){$qt=$_POST["txtQt"];}else{$erro="Quantidade não preenchida!";$qt="";}	
	if (isset($_POST["txtDescricao"])){$descricao=$_POST["txtDescricao"];}
}
if (isset($erro2)){
	$erro=$erro2;
}else{
	if ($forn==1){
		//Pesquisa na tabela peça com a tab. MUP o código (Chave primária) desejado
		// Se achar verifica é garantia
		// Se não achar :
		$sql=mysql_query("select cod, garantia, descricao from peca inner join mup on mup.cod_peca = peca.cod 
						  where cod_fabrica=$codPeca and  mup.cod_modelo=$modelo")or die("Erro no Camando SQL scr_peca.php".mysql_error());
		$row=mysql_num_rows($sql);
		if($row==0){
			// Se não achar na primeira consulta pesquisa se a peça existe
			// Se exite avisa ao ususário que não está cadastrada para o modelo no cadastro senão avisa que não existe.
			$sql2=mysql_query("select descricao from peca where cod_fabrica=$codPeca")or die("Erro no Camando SQL scr_peca.php".mysql_error());
			$row=mysql_num_rows($sql2);
			if($row==0){
				$erro="O código $codPeca não existe no Banco de Dados!";
			}else{
				$sql=mysql_query("select descricao from modelo where cod = $modelo");
				$descModelo=mysql_result($sql,0,"descricao");
				$descPeca=mysql_result($sql2,0,"descricao");
				$erro="A peça $descPeca não está cadastrada para o modelo $descModelo na tabela da Fábrica<br><h1>Caso este componente seja utilizado neste modelo, Favor informar ao seu gerente para que ele comunique a fábrica URGENTEMENTE!<br>Obrigado!";
			}	
		}else{
			$codP=mysql_result($sql,0,"cod");
			$gar=mysql_result($sql,0,"garantia");
	
			if ($gar==0){
				$descPeca=mysql_result($sql,0,"descricao");
				$erro="A peça $descPeca não está cadastrada como componente funcional portanto não pode ser solicitada em Garantia. Caso exista algum equivoco no cadastro ou alguma dúvida informe seu gerente!";
			}
		}
	}
	if ($forn<>1){
		//
		$sql=mysql_query("select cod, garantia, descricao from peca
						  where cod_fabrica=$codPeca and  cod_fornecedor=$forn")or die("Erro no Camando SQL scr_peca.php".mysql_error());
		$row=mysql_num_rows($sql);
		if($row==0){
			// Se não achar na primeira consulta pesquisa se a peça existe
			// Se exite avisa ao ususário que está cadastrada para o fabricante no cadastro senão avisa que não existe.
			$sql2=mysql_query("select descricao from peca where cod_fabrica=$codPeca")or die("Erro no Camando SQL scr_peca.php".mysql_error());
			$row=mysql_num_rows($sql2);
			if($row==0){
				$erro="O código $codPeca não existe no Banco de Dados!";
			}else{
				$sql=mysql_query("select descricao from fornecedor where cod = $forn");
				$descForn=mysql_result($sql,0,"descricao");
				$descPeca=mysql_result($sql2,0,"descricao");
				$erro="A peça $descPeca não está cadastrada para o fornecedor $descForn na tabela da Fábrica<br><h1>Caso este componente seja utilizado neste fabricante, Favor informar ao seu gerente URGENTEMENTE!<br>Obrigado!";
			}	
		}else{
			$codP=mysql_result($sql,0,"cod");
			$gar=mysql_result($sql,0,"garantia");
			if ($gar==0){
				$descPeca=mysql_result($sql,0,"descricao");
				$erro="A peça $descPeca não está cadastrada como componente funcional portanto não pode ser solicitada em Garantia. Caso exista algum equivoco no cadastro ou alguma dúvida informe seu gerente!";
			}
		}
		
	}
}
if (isset($erro)){
	Header("Location:frm_pedido.php?cp=$cp&erro=$erro&codPeca=$codPeca&defeito=$defeito&servico=$servico&descricao=$descricao&forn=$forn&msg=$msg&modelo=$modelo");
}else{
	$sql="insert into pedido (cod_peca,cod_cp,qt,cod_peca_defeito,cod_peca_servico,cod_colab,data_cad)
	values ('$codP','$cp','$qt','$defeito','$servico',$id,now())";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserção dos dados do Pedido de Peças $sql ".mysql_error());		
// Atualizar base de dados com uma coluna estoque penha e outra estoque garantia onde controlaremos sua movimentação com a linha debaixo
//	$sqlC=mysql_query("update peca set qt=qt-1 where cod = $codP");
	Header("Location:frm_pedido.php?cp=$cp&forn=$forn&msg=$msg&modelo=$modelo");
}
?>