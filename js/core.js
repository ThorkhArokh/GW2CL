function setQuantite(idRecette, idUser, type) {
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("loading").style.visibility='hidden';
			document.getElementById("resultatAjax").innerHTML=xmlhttp.responseText;
			//location.assign(location.href);
	    } else if (xmlhttp.readyState < 4) {
	    	document.getElementById("loading").style.visibility='visible';
	    }
	}

	quantite = document.getElementById("zoneSaisieQuantite"+idRecette).value;
	
	xmlhttp.open("GET","./ajax/setQuantite.php?type="+type+"&id="+idRecette+"&quantite="+quantite+"&user="+idUser,true);
	xmlhttp.send();
}

/*Affiche les lignes de détails sélectionnées*/
function afficheDetail(btn, div, idRecettePere) {
	var divs = document.getElementsByName(div);
	var isAffiche = '';
	for (var i=0; i<divs.length; i++) {
		if(divs[i].id == idRecettePere) {
			if(document.getElementById(btn).className == 'moinsBtn') {
				isAffiche = 'none';
				document.getElementById(btn).className = 'plusBtn';
			} else {
		 		isAffiche = "";
				document.getElementById(btn).className = 'moinsBtn';
			}
			break;
		}
	}
	j=0;
	for (var i=0; i<divs.length; i++) {
		if(divs[i].id != idRecettePere && divs[i].id.indexOf(idRecettePere) == 0 ) {
			divs[i].style.display = isAffiche;
		}
		if(divs[i].style.display == "") {
			if(j%2==0){
				divs[i].className = "recette";
			} else {
				divs[i].className = "recette2";
			}
			j++;
		}
		
	}	
}

function afficheColorTab() {
	var divs = document.getElementsByName("divRecette");
	j=0;
	for (var i=0; i<divs.length; i++) {
		if(divs[i].style.display == "") {
			if(j%2==0){
				divs[i].className = "recette";
			} else {
				divs[i].className = "recette2";
			}
			j++;
		}
	}
}

function afficheInfo() {
	display = document.getElementById('divDroite').className;
	
	isAffiche = true;
	if(display == 'divDroiteShow') {
		document.getElementById('divDroite').className = "divDroiteHide";
		document.getElementById('divCentre').className = "divCentreMax";
		isAffiche = false;
	} else {
		document.getElementById('divDroite').className = "divDroiteShow";
		document.getElementById('divCentre').className = "divCentreMin";
		isAffiche = true;
	}
	
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){} 
	}
	
	xmlhttp.open("GET","./ajax/setAfficheInfos.php?isAffiche="+isAffiche,true);
	xmlhttp.send();
}

function selectArmePrincipale(cbx, idUser) {
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("DEBUG").innerHTML=xmlhttp.responseText;
		} else if (xmlhttp.readyState < 4) {
	    	
	    }
	}

	armeSelect = "-1";
	if(cbx.checked) {
		armeSelect = document.getElementById("selectRecettePere").value;
	}
	console.log("arme : "+armeSelect);
	xmlhttp.open("GET","./ajax/setArmePrincipale.php?armeId="+armeSelect+"&idUser="+idUser,true);
	xmlhttp.send();
}

function afficheDiv(div) {
	document.getElementById(div).style.visibility='visible';
}

function masqueDiv(div) {
	document.getElementById(div).style.visibility='hidden';
}

function masqueParam() {
	masqueDiv("param");
	document.getElementById('erreurs').innerHTML = "";
	document.forms['modifierParamForm'].elements['zoneSaisieLoginParam'].className="";
}

