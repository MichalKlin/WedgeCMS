<!--
/*
//	działa pod: 
//  IE >4, Netscape >4, Opera, nie udało się na razie odpalić pod Netscape4.x
//
//	opis:
//	Po kliknięciu na pole tekstowe pojawia się kalendarz pod 
//  kursorem myszy. Po wybraniu roku, miesiąca i kliknięciu na 
//  numerze dnia wybrana data jest wstawiana do pola
//
//	historia:
//	potrzebowałem wstawić datę do formularza w określonym formacie, 
//	widziałem skrypt na necie który pozwalał wybrać datę z kalendarza, 
//  ale był duży i skomplikowany, więc napisałem swój
//
//	wywołanie:
//	umieścić w zanczniku body: onLoad="document.onmousemove = mysz;" 
//  lub w treści strony w znacznikach javascriptu: document.onmousemove = mysz;
//	zdarzenie pola txt, do którego chcemy wstawić datę: onclick="showKal(this)"
*/

var ie4, ns4, ns6;
ie = document.all;
ns4 = document.layers;
ns6 = document.getElementById && !document.all;

var data = new Date();
var amies = data.getMonth();
var arok = data.getFullYear();
var adzien = data.getDate();
var adzientyg = data.getDay();
var frmpole;

// ilość dni w roku
var dni = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
// nazwy miesięcy
var miesiac = new Array('Styczeń','Luty','Marzec','Kwiecień', 'Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień');

// dane kolorów
var kol = new Array(5)
kol[0] = '#FFFFFF'; // kolor tła kalendarza, kolor tekstu wybranego dnia, nazw dni tyg...
//kol[1] = '#E1EDFF'; // kolor pól kalendarza - dni zwykłe
kol[1] = '#AFCFFA';
kol[2] = '#FFDBDB'; // kolor pól kalendarza - niedziele
//kol[3] = '#0A74E0'; // kolor pola oznaczającego aktualny dzień, kolor ramki, przycisku zamykajacego, tekstu
kol[3] = '#163381';
kol[4] = '#696969'; // kolor pól okreslajacych dni tygodnia (pn,wt...)
kol[5] = '#AAAAAA'; // kolor czcionki w dniu nieaktywnym (data wsteczna)

// ile lat pokazywane w kalendarzu od aktualnej daty
var wstecz = 50; 
var wprzod = 10;

// ilość dni w Lutym - przeliczane po zmianie miesiąca lub roku
function dniMies()
{
	dni[1] = (rok % 4 == 0) ? 29 : 28;
}

// pobieranie pozycji myszy
function mysz(e)
{
	if(ns4 || ns6)
	{
		x = e.pageX;
		y = e.pageY;
	}
	if(ie)
	{
		x = document.body.scrollLeft+event.clientX;
		y = document.body.scrollTop+event.clientY;
	}
}

// funkcja pokazujaca kalendarz pod kursorem myszy
function showKal(fp)
{
	data = new Date(arok, amies, 1);
	mies = data.getMonth();
	rok = data.getFullYear();
	dzien = data.getDate();
	dzientyg = data.getDay();
	
	dniMies();

	frmpole = fp;
	pozx = x;
	pozy = y;

	rysujKal();		
	
	if(ns6 || ie)
	{
		document.getElementById('kalendarz').style.left = pozx+'px';
		document.getElementById('kalendarz').style.top = (pozy+10)+'px';
		document.getElementById('kalendarz').style.visibility = 'visible';
	}
}

// funkcja ukrywajaca kalendarz i wstawiajaca wybraną datę do pola formularza
function hideKal()
{
	if(ns6 || ie)
		document.getElementById('kalendarz').style.visibility = 'hidden';

	// tutaj ustawia się format daty 		 
	// np:
  	//	format = selectday + ' ' + miesiac[mies] + ' ' + rok;
	
	// inny format daty - z zerami poprzedzającymi
	mies++;
	if(mies < 10)
		mies = '0' + mies;
	if(selectday < 10)
		selectday = '0' + selectday;

	format = rok+'-'+mies+'-'+selectday;	
			
	frmpole.value = format;
}

// ukrywanie kalendarza bez wstawiania daty
function exitKal()
{
	if(ns6 || ie)
		document.getElementById('kalendarz').style.visibility = 'hidden';
}

// ustawianie nowej daty po zmianie miesiaca lub roku
function setData()
{
	mies = document.forms['sdata'].elements['month'].value;
	rok = document.forms['sdata'].elements['year'].value;
	
	data = new Date(rok, mies, 1);
	mies = data.getMonth();
	rok = data.getFullYear();
	dzien = data.getDate();
	dzientyg = data.getDay();
	dniMies();
	rysujKal();
}

// rysowanie kalendarza
function rysujKal()
{
	kaltxt = '<form name="sdata" onSubmit="return false;">';
	kaltxt += '<table border=0 cellpadding=0 cellspacing=2 style="border:'+kol[3]+' 2px solid;background-color:'+kol[0]+';">';
	kaltxt += '<tr class=dzien><td colspan=6 height=25><select name="month" class="lista" onChange="setData()">';		
	for(i=0;i<12;i++)
	{
		if(i==mies)
			kaltxt += '<option value="'+i+'" selected>'+miesiac[i]+'</option>';
		else
			kaltxt += '<option value="'+i+'">'+miesiac[i]+'</option>';
	}
	kaltxt += '</select>&nbsp;<select name="year" class="lista" onChange="setData()">';
	for(i=(rok-wstecz);i<=(rok+wprzod);i++)
	{
		if(i==rok)
			kaltxt += '<option value="'+i+'" selected>'+i+'</option>';
		else
			kaltxt += '<option value="'+i+'">'+i+'</option>';	
	}
	kaltxt += '</select>';
	kaltxt += '</td><td><a href="javascript:exitKal()"><span class="aktday">&nbsp;X&nbsp;</span></a></td></tr>';
	kaltxt += '<tr class=dnityg><td width=30>Nd</td><td width=30>Pn</td><td width=30>Wt</td><td width=30>Śr</td>';
	kaltxt += '<td width=30>Czw</td><td width=30>Pt</td><td width=30>So</td></tr><tr class=dzien>';

	j = 1;

	for(i=0;i<dzientyg+dni[mies];i++)
	{
		if(i>=dzientyg)
		{
			if(j==adzien && rok==arok && mies==amies)
				kaltxt += '<td class=aktday><a class=aktday href="javascript:selectday='+j+';hideKal();" >'+j+'</a></td>';
			/*else if(rok<arok || (rok==arok && mies<amies) || (rok==arok && mies==amies && j<adzien)){
				if(i%7==0) kaltxt += '<td class=niedz_nie_akt>'+j+'</td>';
				else kaltxt += '<td class=dzien_nie_akt>'+j+'</td>';
			}*/
			/*else if(mies==amies && rok==arok){
				else{
					if(i%7==0) kaltxt += '<td class=niedz><a class=niedz href="javascript:selectday='+j+';hideKal();" >'+j+'</a></td>';
					else kaltxt += '<td><a class=dzien href="javascript:selectday='+j+';hideKal();" >'+j+'</a></td>';
				}
			}*/
			else if(i%7==0)
					kaltxt += '<td class=niedz><a class=niedz href="javascript:selectday='+j+';hideKal();" >'+j+'</a></td>';
			else
					kaltxt += '<td><a class=dzien href="javascript:selectday='+j+';hideKal();" >'+j+'</a></td>';
			
			j++;
			if(i%7==6)
				kaltxt += '</tr><tr class=dzien>';
		}
		else
			kaltxt += '<td></td>';
	}

	kaltxt += '</tr></table></form>';
	
	document.getElementById("kalendarz").innerHTML = kaltxt;
}

// style kalendarza i warstwa, na której się znajduje
document.write('<div id="kalendarz" style="visibility:hidden;position:absolute;"></div>');
document.write('<style type="text/css">');
document.write('.dzien{font-family:Verdana;font-size:11px;color:'+kol[3]+';text-align:center;background-color:'+kol[1]+';text-decoration:none}');
document.write('.niedz{font-family:Verdana;font-size:11px;color:'+kol[3]+';text-align:center;background-color:'+kol[2]+';text-decoration:none}');
document.write('.dzien_nie_akt{font-family:Verdana;font-size:11px;color:'+kol[5]+';text-align:center;background-color:'+kol[1]+';text-decoration:none}');
document.write('.niedz_nie_akt{font-family:Verdana;font-size:11px;color:'+kol[5]+';text-align:center;background-color:'+kol[2]+';text-decoration:none}');
document.write('.aktday{color:'+kol[0]+';font-weight:bold;text-align:center;background-color:'+kol[3]+';text-decoration:none}');
document.write('.dnityg{font-family:Verdana;font-size:11px;color:'+kol[0]+';text-align:center;background-color:'+kol[4]+';}');
document.write('.lista{font-family:Verdana;font-size:11px;color:'+kol[3]+';}</style>');

//-->