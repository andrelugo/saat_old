if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatind.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatind_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatind_plain:link, a.saatind_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatind_plain:hover, a.saatind_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatind_b1",".gif",25,100,"javascript:;","","Indicadores Estatisticos",2,2,"saatind_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatind_b1","saatind_menu",100);
submenuItem("Desempenho",loc+"con_desenpenho.php","adm_mainFrame","saatind_plain");
submenuItem("Estoque",loc+"con_indestoque.php","adm_mainFrame","saatind_plain");
submenuItem("Defeito/Solução",loc+"con_inddefeitos.php","adm_mainFrame","saatind_plain");
submenuItem("Produção",loc+"con_indproducao.php","adm_mainFrame","saatind_plain");
submenuItem("Peças",loc+"con_indpecas.php","adm_mainFrame","saatind_plain");
submenuItem("Posição/Inventário",loc+"con_indposicao.php","adm_mainFrame","saatind_plain");
endSubmenu("img/botoes/saatind_b1");

loc="";
