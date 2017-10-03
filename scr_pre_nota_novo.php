<? // Analizar 22/09/07

// Script ativado pela Página frm_orc_definir....
// Este script é o responsável por atribuir a cada "max_item_nota" itens um numero de pre_nota que gera um relatório para o dpt. de contas
// enquanto não apontar-mos o numero de nota fiscal para o sistema ele nãO gera o rateio de lojas que será gerado para 
// cada nota entregue ao CBD
// Geraremos um campo especial para orc_individual onde geraremos o arquivo pdf e tambem as pre-notas após a aprovação do C.B.D.
// Ao gerar pré-notas para orc coletivos, o sistema entenderá que todos os orçamentos sem definição estão aprovados... 
// Porem orçamentos individuais precisarão ter todos os orçamentos definidos... caso contrário ERROOOO!!!!mostrar quais ainda não foram definidos
// Orçamentos reprovados devem ter o numero de prénota setado para 0 (zero)
require_once("sis_valida.php");
require_once("sis_conn.php");
$sqlF=mysql_query("select max(fechamento) as fechamento from orc");
$fechamento=mysql_result($sqlF,0,"fechamento"); 
if($fechamento==NULL){$fechamento=1;}else{$fechamento++;}
mysql_query("update orc set fechamento=$fechamento where cod_decisao>1 AND fechamento IS NULL");

///////// Setando para 0 (zero) o num da pré-nota para todos os orçamentos reprovados
$sql2="update orc inner join orc_decisao on orc_decisao.cod = orc.cod_decisao 
set cod_orc_pre_nota=0 
where orc.fechamento = $fechamento and orc_decisao.aprova=0";
mysql_query($sql2) or die ("$sql2<br>".mysql_error());
////////Fim setando para 0

//Iniciando a busca por todas peças no fechamento em questão para então gravar o nº de Pré-Nota
$sql="SELECT cod_peca,valor FROM orc WHERE fechamento=$fechamento and cod_orc_pre_nota is null GROUP BY cod_peca,valor order by cod_peca";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error()."<br>$sql");
$row=mysql_num_rows($res);
$itm=mysql_query("select max_item_nota from base");// Número máximo de linhas suportado pelo corpo da nota
$linhas=mysql_result($itm,0,"max_item_nota");
$maxLinhas=$linhas-1;
$i=0;

while($linha=mysql_fetch_array($res)){
	if($i==0){
		$sql="insert into orc_pre_nota (data_abre,cod_colab_abre) values(now(),$id)";// Cadastra uma nova pré-nota
		mysql_db_query ("$bd",$sql,$Link) or die ("$sql<br>".mysql_error());
		$sqlPre=mysql_query("select max(cod) as cod from orc_pre_nota");
		$pre=mysql_result($sqlPre,0,"cod");
	}
	//sempre que encontra um item que não aprova então fica um buraco na PRÉ-NOTA então usei a função mysql_affected_rows()
	$sql2="update orc inner join orc_decisao on orc_decisao.cod = orc.cod_decisao 
	set cod_orc_pre_nota = $pre 
	where orc.fechamento = $fechamento and cod_peca=$linha[cod_peca] and orc_decisao.aprova=1 and valor like $linha[valor]";
	
	mysql_query($sql2) or die ("$sql2<br>".mysql_error());
	$rowGravados=mysql_affected_rows();
	if ($rowGravados==0){
		die("Nenhum registro afetado <br>".$sql2);	
		//o comando abaixo limpa as colunas de orc para desfazer este evento
		"update orc set fechamento=null,cod_orc_pre_nota=null where fechamento=$fechamento";
		"delete from orc_pre_nota inner join orc on orc.cod_orc_pre_nota = orc_pre_nota.cod 
		where orc.cod is null";
	}
	if($i==$maxLinhas){
		$i=0;
	}else{
		if($rowGravados<>0){
			$i++;
		}
	}
}
Header("Location:con_pre_nota.php?fechamento=$fechamento");
?>