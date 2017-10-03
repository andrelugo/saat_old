if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatsai.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatsai_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatsai_plain:link, a.saatsai_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatsai_plain:hover, a.saatsai_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatsai_b1",".gif",25,100,"javascript:;","","Gerenciamento das saídas de produtos",2,2,"saatsai_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatsai_b1","saatsai_menu",105);
submenuItem("Fechamento",loc+"frm_fechamento.php","adm_mainFrame","saatsai_plain");
submenuItem("Folhas Salvas",loc+"con_saidaok.php","adm_mainFrame","saatsai_plain");
submenuItem("Folhas em uso",loc+"con_saidasalvar.php","adm_mainFrame","saatsai_plain");
endSubmenu("img/botoes/saatsai_b1");

loc="";
