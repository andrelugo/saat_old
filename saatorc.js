if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatorc.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatorc_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatorc_plain:link, a.saatorc_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatorc_plain:hover, a.saatorc_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatorc_b1",".gif",25,100,"javascript:;","","Gerenciamento de orçamentos no SAAT!",2,2,"saatorc_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatorc_b1","saatorc_menu",108);
submenuItem("Emissão",loc+"mnu_orc.php","adm_mainFrame","saatorc_plain");
submenuItem("Revisão",loc+"frm_orc_revisar.php","adm_mainFrame","saatorc_plain");
submenuItem("Pendentes",loc+"con_orcpendentes.php","adm_mainFrame","saatorc_plain");
submenuItem("Definir Orc",loc+"frm_orc_definir.php","adm_mainFrame","saatorc_plain");
submenuItem("Pré-Nota",loc+"mnu_pre_nota.php","adm_mainFrame","saatorc_plain");
submenuItem("Pedido Orç",loc+"con_pedidoorc.php","adm_mainFrame","saatorc_plain");
endSubmenu("img/botoes/saatorc_b1");

loc="";
