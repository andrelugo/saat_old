<? //esta p�gina deve pegar o �ltimo valor da coluna folha_cq na tabela cp para o colaborador logado e 
//incrementar um. Este valor incrementado deve ser atualizado na tabela para todos os produtos finalizados
//do colaborador logado que n�o tenham um numero de folha.
//PARA EVITAR QUE UMA OUTRA PESSOA FINALIZE A PLANILHA DO CQ � IMPORTANTE QUE OS CONTROLADORES, S� LOGUEM 
//NO SISTEMA NO MOMENTO DE FAZER AS ENTRADAS DE DADOS E EM SEGUIDA SAIAM DO SISTEMA
//PARA EVITAR QUE NA FALTA DO CONTROLER DE QUALIDADE NO MOMENTO DA SA�DA DE UM LOTE, � IMPORTANTE QUE ELE
//SEMPRE FINALIZE UMA PLANILHA ANTES DE SE AUSENTAR DO AMBIENTE OU QUE ELE
//SEMPRE EXECUTE UMA FINALIZA��O PELO MENOS UMA VEZ POR DIA!
require_once("sis_valida.php");
require_once("sis_conn.php");
//$sql=mysql_query("select max(folha_cq) as num from cp where cod_cq=$id")or die(mysql_error());
$sql1="select count(cod) as tot from cp where cod_cq=$id and folha_cq is null and data_pronto is not NULL";
$query=mysql_query($sql1);
$res1=mysql_result($query,0,"tot");

if($res1==0){
	die("<h1>N�o h� produtos para salvar!</h1>");
}else{
	$sql=mysql_query("select max(folha_cq) as num from cp")or die(mysql_error());
	$num=mysql_result($sql,0,"num");
	$num++;
	$sql=mysql_query("update cp set folha_cq=$num where cod_cq=$id and folha_cq is null and data_pronto is not NULL")or die(mysql_error());
	$sqlrow=mysql_query("select count(folha_cq) as linhas from cp where folha_cq=$num")or die(mysql_error());
	$row=mysql_result($sqlrow,0,"linhas");
	if ($row==0){$row="Nenhum ";}
	$msg="$row Registros salvos nesta folha de produ��o de controle de qualidade";
	Header("Location:pdf_folhacq.php?txtFolha=$num");
}
?>