<!--
var mies_tab = new Array("Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień");
var tabelaHead="<div style=\"position: fixed; top:300px; left: 500px; z-index: 90;\" bgcolor='#fffff'><table border=1 cellspacing=0 cellpadding=0  width=176><tr><td class=lewa_tab_1_bg><table border=0 class=tbw id=bkg cellspacing=0 width=100% bgcolor='#fffff'>\n";
var ret=tabelaHead;
var wsk=false;
var wsk_a=null;
var formatDaty = "Y-m-d";
var dys_tab=new Array(31,29,31,30,31,30,31,31,30,31,30,31);
var teraz=new Date();
var approve=new Date();
var mies=teraz.getMonth();
var rok=teraz.getYear();
var counter=mies;

//if (navigator.appName.indexOf('Microsoft') != -1) 
start_calendar();

function rysuj(target)
{
        rok=teraz.getYear();
        wsk_a = target;
       
        set_cal(teraz.getYear(), teraz.getMonth());
        if ((wsk_a != null)&&(wsk_a))
        {
        var obj = document.getElementById('kalendarz');
                x = document.body.scrollLeft + 300 -10;//+event.clientX
                y = document.body.scrollTop+ 200;//event.clientY+
        obj.style.left = (x>0)?x:0;
           //obj.style.top  = (y>0)?y:0;
//        if(par)
//          obj.style.top  = y-150;
//        else
           obj.style.top  = y;

        obj.style.visibility = "visible";
  }
}


/*konfiguracja*/
function click(log)
{
        counter++;
        if(log == 1)
        {

                if ((mies < teraz.getMonth()) && (rok >= teraz.getYear()) || (rok<teraz.getYear()))
                {
                        //Mies++;

                }

                //if ((rok == teraz.getYear()) || (rok<teraz.getYear()))
                 mies++;

                if(mies == 12)
                {
                        mies=0;rok=rok+1;
                }


        }


   else
   {

 //       if( (mies>9)||(rok>1991) )
 //       {
                mies--;
 //       }

        if(mies == -1 ) {mies=11;rok=rok-1; if (rok<100) rok=rok+1900;}
        }

 set_cal(rok,mies);
}

function wstaw_kal(param)
{
        var arr   = param.split("|");
        var rok  = arr[0];
        var month = arr[1];
        var data  = arr[2];
        var ptr = parseInt(data);
        approve.setDate(ptr);
        if ((wsk_a != null)&&(wsk_a))
        {
                wsk_a.value = FormatData(rok,month,data);
//                wsk_b.value = month+1;//FormatData(rok,month,data);
//                wsk_c.value = data;//FormatData(rok,month,data);
                ukryj();
   }
}



function set_cal(rok,month)
{
        if (rok  == null)
        {
                rok = teraz.getYear();
        }
        if (month == null)
        {
                month = teraz.getMonth();
        }
        if (month == 1)
        {
                dys_tab[1]  = (przestepny(rok)) ? 29 : 28;
        }


        approve.setYear(rok);
        approve.setMonth(month);
        approve.setDate(1);
        przeladuj();
}

function przeladuj()
{
        przelicz();
        document.getElementById('dni').innerHTML = ret;
        ret = tabelaHead;
}


function przelicz()
{
        var rok  = _okrok(approve);
        if (rok<100) {
			rok = 3800+rok;
        }
        var month = approve.getMonth();
        var data  = 1;
        var dzisiaj = teraz.getDay();
        var day   = (approve.getDay()-1);
        var len   = dys_tab[month];
        var bgr,cnt,tmp = "";
        var j,i;
        
        ret += "<tr bgcolor='#ffffff'><td colspan=7><div align=center><a href='javascript:ukryj()' class=u>Zamknij</a>&nbsp;&nbsp;&nbsp;</div></td></tr>";
        ret += "<tr bgcolor='#fffff'><td colspan=1 bgcolor='#fffff'><div align=center>"+"<a href='javascript:click(0)'><img src='../javascript/kalendarz/ico_nav_prev.png' border=0></a></td>"+"<td colspan=5 align=center><span class=gl_tab_0_txt>"+rok+" "+mies_tab[mies]+"</span></td>"
               +"<td colspan=1><a href='javascript:click(1)'><img src='../javascript/kalendarz/ico_nav_next.png' border=0></a>"+"</div></td></tr>";
   ret  += "<tr bgcolor='#ffffff'><td colspan=7><tr align=center bgcolor='#dddddd'><td width='20' id=t1><span class=gl_tab_0_txt>Pon</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Wt</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Sr</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Czw</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Pt</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Sb</span></td><td width='20' id=t1><span class=gl_tab_0_txt>Nd</span></td></tr></td></tr>";
 for (j = 0; j < 7; j++)
 {
        if (data > len) {break;}
        for (i = 0; i < 7; i++)
        {
        if(day==-1) {day=6;}
        bgr = ((i == 6)) ? "#FFFFCC" : "#ffffff";//||(i==5)
        sobniedz = ((i == 6)) ? 1 : 0;  //dorzucone przez Jeda - niklikalne soboty i niedz//||(i==5)

        if ( ((j == 0) && (i < day)) ||(data > len) )
        {
                tmp  += generuj(bgr,rok,month,0,sobniedz);
        }
      else
      {
        tmp  += generuj(bgr,rok,month,data,sobniedz);data++;
      }
    }
   ret += "<tr align=\"center\">\n" + tmp + "</tr>\n";tmp = "";}
   //ret += "<tr><td colspan=7><div align=center><a class=gl_tab_0_link  href='javascript:ukryj()' class=u>Zamknij</a>&nbsp;&nbsp;&nbsp;</div></td></tr>";
   ret += "</table></td></tr></table></div>\n";}

function generuj(bgr,rok,month,sdate,sobniedz)
{
         var param = "\'"+rok+"|"+month+"|"+sdate+"\'";

         var td1 = "<td class=a1 width=\"20\" bgcolor=\""+bgr+"\" ";
         var td2 = "</span></td>\n";
         var evt = "onMouseOver=\"this.style.backgroundColor=\'#AAFFAA\'\" onMouseOut=\"this.style.backgroundColor=\'"+bgr+"\'\" onMouseUp=\"wstaw_kal("+param+")\" ";
         var ext = "<span Style=\"cursor: hand\">";
         var lck = "<span Style=\"cursor: default\">";
         var lnk = "<a href=\"javascript:wstaw_kal("+param+")\" onMouseOver=\"window.status=\' \';return true;\">";
         var cellValue = (sdate != 0) ? "<span class=gl_tab_0_txt>"+sdate+"" : "&nbsp;</span>";


         if ((teraz.getDate() == sdate )&&(teraz.getMonth() == month)&&(_okrok(teraz) == rok))
         {
                 cellValue = "<b><font color='#FF8818'>"+cellValue+"</font></b>";
         }

         if ((teraz.getDate()<sdate )&&(teraz.getMonth() == month)&&(_okrok(teraz) == rok))
         {
                 cellValue = "<font color='#CACACA'>"+cellValue+"</font>";
         }

         //|| (teraz.getDate()<sdate )
         var cellCode = "";
         if (sdate == 0 || sobniedz &&(teraz.getMonth() == month)&&(_okrok(teraz) == rok))
         {
                 cellCode = td1+"Style=\"cursor: default\">"+lck+cellValue+td2;
         }

         else
         {
                    cellCode = td1+evt+"Style=\"cursor: hand\">"+ext+cellValue+td2;
         }
         return cellCode;
 }


function ukryj() {
 document.getElementById('kalendarz').style.visibility = "hidden";
 mies = teraz.getMonth();
 wsk = false;
 wsk_a = null;

 }


function przestepny(rok)
{
 if ((rok%400==0)||((rok%4==0)&&(rok%100!=0)))
 {
        return true;
 }
 else
 {
        return false;
        }
}

function _okrok(obj)
 {
 return obj.getYear();
 }

function form_data(data) {
 var reply = true;

  var mode = arr[0];
  var arg  = arr[1];
  var key  = arr[2].charAt(0).toLowerCase();
  if (key != "d") {
   var day = approve.getDay();
   var orn = isEvenOrOdd(data);
   reply = (mode == "[^]") ? !((day == arg)&&((orn == key)||(key == "a"))) : ((day == arg)&&((orn == key)||(key == "a")));}
  else {reply = (mode == "[^]") ? (data != arg) : (data == arg);}
 return reply;}

function FormatData(rok,month,data)
{
        if (formatDaty == null) {formatDaty = "Y/m/d";}
        var day = approve.getDay();
        var crt = "";
        var str = "";
        var chars = formatDaty.length;
        if (rok.length<4) rok = rok;
        for (var i = 0; i < chars; i++)
         {
                 crt = formatDaty.charAt(i);
                switch (crt)
                {
                case "M": str += mies_tab[month]; break;
                case "m": str += (month<9) ? ("0"+(++month)) : ++month; break;
                case "Y": str += rok; break;
                case "y": str += rok.substring(2); break;
                   case "d": str += ((formatDaty.indexOf("m")!=-1)&&(data<10)) ? ("0"+data) : data; break;
         default: str += crt;
       }
    }
 return str;
}

function start_calendar()
{
        obr1 = new Image; //obr1.src = '../../images/right.gif';
        obr2 = new Image; //obr2.src = '../../images/left.gif';
        document.writeln("<div id=\"kalendarz\" style=\"position:absolute; left:0px; top:0px; z-index:7; width:1px; height:77px; visibility: hidden; background-color: #FF0000; #FF0000\">");
        document.writeln("<div id=\"miesiace\" style=\"position:absolute; left:0px; top:0px; z-index:9; width:181px; height:27px;\">");
        document.writeln("<div id=\"dni\" style=\"position:absolute; left:0px; top:0px; z-index:8; width:176px; height:17px; background-color: #FFFFFF; border: 1px none #000000\">&nbsp;</div></div>");
        document.writeln("</div>");
        set_cal(teraz.getYear(), teraz.getMonth());

}

function sprawdz_daty(){
        var popr=0, data_od,  mies;
    data_od = new Date(document.wysz.data.value.substr(2,2),document.wysz.data.value.substr(5,2)-1,document.wysz.data.value.substr(8,2));
    mies= new Array(31,28,31,30,31,30,31,31,30,31,30,31);
    if ((document.wysz.data.value.length!=10)) popr=1;
        else if ((document.wysz.data.value.substr(4,1)!='/')||(document.wysz.data.value.substr(7,1)!='/')) {popr=1; }
    else if ((isNaN(document.wysz.data.value.substr(0,4)))||(isNaN(document.wysz.data.value.substr(5,2))||(isNaN(document.wysz.data.value.substr(8,2))))) {popr=2;}
    else{    if (data_od.getYear()<100){
                         if (data_od.getYear()!= Number(document.wysz.data.value.substr(2,2))) {popr=2;}
                }   else {                if (data_od.getYear()!= Number(document.wysz.data.value.substr(0,4))) {popr=2;}
                                                else{                        if (data_do.getYear()<100){
                                                                        if (data_do.getYear()!= Number(document.wysz.data.value.substr(2,2))) popr=2;                        }
                                                    else {                                if (data_do.getYear()!= Number(document.wysz.data.value.substr(0,4))) popr=2;
                                                         }
                                                }
                                }
                         }
                         if (Number(document.wysz.data.value.substr(8,2))>mies[Number(document.wysz.data.value.substr(5,2))-1]){ popr=2;}
                         if (popr!=0){                if (popr==1){
                                                                 alert('Poprawny format daty to yyyy/mm/dd!');                }
                                            else{                        if (popr==2) {alert('Niepoprawne daty!');}
                                                                            else{alert('Bledne daty!')}
                                            }
                                     }        else {document.wysz.submit();
        }}

-->
