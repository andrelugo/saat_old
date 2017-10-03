<? // A partir do número do barcode ou do registro de saida, orc coletivo, orc individual ou num orc cliente
// incluir ou excluir de uma pré-notas todos os itens aprovados e ao mesmo tempo perguntar se uma nova pré-nota deve ser gerada em função do max_item_nota
// em uma outra tela, permitir o cadastro e alteração do numero da nota e sua respectiva impressão.
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Pré-Notas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center" class="style1">
  <p>Administra&ccedil;&atilde;o de Notas Fiscais<br>
    Aqui você pode montar uma nota de acordo com os or&ccedil;amento liberados</p>
  <p>&nbsp;</p>
  <p><a href="frm_pre_nota_individual.php">Montar uma pr&eacute;-nota individual</a><br>
  Montar  pr&eacute;-notas coletiva para registro de sa&iacute;da ou or&ccedil;amento coletivo<br>
  <br>
    Cadastrar/Alterar dados de uma pr&eacute;-notas (num. NF, OBS, trasportador, etc) <br>
  Imprimir uma nota fiscal</p>
  <p>&nbsp;</p>
  <p>&nbsp; </p>
</div>
</body>
</html>
