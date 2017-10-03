if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatcad.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatcad_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatcad_plain:link, a.saatcad_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:11pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatcad_plain:hover, a.saatcad_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:11pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatcad_b1",".gif",24,100,"javascript:;","","Cadastrar dados nas tabelas do Banco de Dados!",2,2,"saatcad_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatcad_b1","saatcad_menu",100);
submenuItem("Colaborador",loc+"frm_colab.php","adm_mainFrame","saatcad_plain");
submenuItem("Modelo",loc+"frm_modelo.php","adm_mainFrame","saatcad_plain");
submenuItem("Peça",loc+"frm_mnupeca.php","adm_mainFrame","saatcad_plain");
submenuItem("Defeito",loc+"frm_defeito.php","adm_mainFrame","saatcad_plain");
submenuItem("Solução",loc+"frm_solucao.php","adm_mainFrame","saatcad_plain");
submenuItem("Destino",loc+"frm_destino.php","adm_mainFrame","saatcad_plain");
submenuItem("Posição",loc+"frm_posicao.php","adm_mainFrame","saatcad_plain");
submenuItem("Fornecedor",loc+"frm_fornecedor.php","adm_mainFrame","saatcad_plain");
submenuItem("Outros",loc+"frm_outros.php","adm_mainFrame","saatcad_plain");
endSubmenu("img/botoes/saatcad_b1");

loc="";
