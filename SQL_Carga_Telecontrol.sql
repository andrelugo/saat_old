select 3,58024932000178,cp.os_fornecedor,cp.data_entra,cp.data_sai,modelo.cod_produto_fornecedor,
cp.serie,cliente.cpf_cnpj,cliente.descricao,cliente.telefone,cliente.cpf_cnpj,cliente.descricao,
rs.numero,cp.data_entra,defeito.cod_britaniareclamado,defeito.cod_britaniaconstatado,
defeito.cod_britaniacausa,peca.cod_fabrica,pedido.qt,pedido.cod_peca_defeito,pedido.cod_peca_servico
into outfile "E:/Compartilhada/teste.txt"
fields terminated by '	' 
enclosed by ''
lines terminated by ''
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join cliente on cliente.cod = 1
left join rs on cp.cod_rs = rs.cod
inner join defeito on defeito.cod = cp.cod_defeito
left join pedido on cp.cod = pedido.cod_cp
inner join peca on peca.cod = pedido.cod_peca
where cp.data_analize between '2005-01-01' and '2005-12-31'

/ nem sempre haverá pedido
// nem sempre tem registro de saida ex.: entrada

select 3,58024932000178,cp.os_fornecedor,cp.data_entra,cp.data_sai,modelo.cod_produto_fornecedor,
cp.serie,cliente.cpf_cnpj,cliente.descricao,cliente.telefone,cliente.cpf_cnpj,cliente.descricao,
rs.numero,cp.data_entra,defeito.cod_britaniareclamado,defeito.cod_britaniaconstatado,
defeito.cod_britaniacausa,peca.cod_fabrica,pedido.qt,pedido.cod_peca_defeito,pedido.cod_peca_servico
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join cliente on cliente.cod = 1
left join rs on cp.cod_rs = rs.cod
inner join defeito on defeito.cod = cp.cod_defeito
left join pedido on cp.cod = pedido.cod_cp
inner join peca on peca.cod = pedido.cod_peca
where cp.data_analize between '2005-01-01' and '2005-12-31'


em 04/09/2005

select 3,52024932000178,cp.os_fornecedor,cp.item_os_fornecedor,'R',
cp.data_entra,cp.data_sai,modelo.cod_produto_fornecedor,
cp.serie,cliente.cpf_cnpj,cliente.descricao,cliente.telefone,cliente.cpf_cnpj,cliente.descricao,
cliente.telefone,cp.barcode,cp.data_entra,defeito.cod_britaniareclamado,defeito.cod_britaniaconstatado,
defeito.cod_britaniacausa,peca.cod_fabrica,pedido.qt,pedido.cod_peca_defeito,pedido.cod_peca_servico
into outfile "E:/Compartilhada/teste.txt"
fields terminated by '	' 
enclosed by ''
lines terminated by ''
from cp
inner join modelo on modelo.cod = cp.cod_modelo
inner join cliente on cliente.cod = 1
inner join defeito on defeito.cod = cp.cod_defeito
left join pedido on cp.cod = pedido.cod_cp
left join peca on pedido.cod_peca = peca.cod
where cp.data_analize between DATE_SUB(NOW(),INTERVAL 10 DAY) and now()