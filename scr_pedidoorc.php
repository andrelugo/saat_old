<? // script paliativo para o controle dos pedidos de compra
require_once("sis_valida.php");
require_once("sis_conn.php");
$pedido=$_GET["txtPedido"];
$valor=$_GET["txtValor"];
$dtini=$_GET["dtIni"];
$dtfim=$_GET["dtFim"];

if ($pedido=="" || $valor==""){
	die("<h1>Campos obrigatórios não preenchidos!</h1>");
}
$sql="select cod,data_cad from orc_pedido where descricao = $pedido";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows>0){
	$datacad=mysql_result($res,0,"data_cad");
	die("<h1>Pedido $pedido já está foi cadastrado em $datacad!</h1>");
}

$sql="insert into orc_pedido (descricao,data_cad, cod_colab_cad, valor) values ($pedido,now(),$id,$valor)";
mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserção do registro de saída $sql ".mysql_error());		
$res=mysql_query("select max(cod) as cod from orc_pedido") or die(mysql_error());
$cod=mysql_result($res,0,"cod");
$sql2="update orc 
inner join orc_decisao on orc_decisao.cod = orc.cod_decisao
set cod_orc_pedido = $cod 
where data_decisao BETWEEN ('$dtini')AND('$dtfim')
and orc_decisao.aprova=1
and cod_orc_pedido is null";
mysql_query($sql2) or die(mysql_error());
print("<h1>Pedido $pedido cadastrado com sucesso com código $cod</h1>");

//Header("Location:frm_registra_saida2.php?folha=$folha");
?>