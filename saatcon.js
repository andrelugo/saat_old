if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatcon.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatcon_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatcon_plain:link, a.saatcon_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:11pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatcon_plain:hover, a.saatcon_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:11pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatcon_b1",".gif",25,100,"javascript:;","","Consultar e Alterar as tabelas do Banco de Dados!",2,2,"saatcon_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatcon_b1","saatcon_menu",100);
submenuItem("Colaborador",loc+"con_colab.php","adm_mainFrame","saatcon_plain");
submenuItem("Modelo",loc+"con_modelo.php","adm_mainFrame","saatcon_plain");
submenuItem("Peça",loc+"con_peca.php","adm_mainFrame","saatcon_plain");
submenuItem("Defeito",loc+"con_defeito.php","adm_mainFrame","saatcon_plain");
submenuItem("Solução",loc+"con_solucao.php","adm_mainFrame","saatcon_plain");
submenuItem("Destino",loc+"con_destino.php","adm_mainFrame","saatcon_plain");
submenuItem("Posição",loc+"con_posicao.php","adm_mainFrame","saatcon_plain");
submenuItem("Fornecedor",loc+"con_fornecedor.php","adm_mainFrame","saatcon_plain");
submenuItem("Outros",loc+"con_outros.php","adm_mainFrame","saatcon_plain");
endSubmenu("img/botoes/saatcon_b1");

loc="";
