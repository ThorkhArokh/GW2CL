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

function afficheInfo() {
	display = document.getElementById('divDroite').style.display
	
	if(display == '') {
		document.getElementById('divDroite').style.display = 'none';
		document.getElementById('divDroite').style.width='0';
		document.getElementById('divCentre').style.marginRight='0';
		
	} else {
		document.getElementById('divDroite').style.display = '';
		document.getElementById('divDroite').style.width='350px';
		document.getElementById('divCentre').style.marginRight='370px';
	}
}

function selectArmePrincipale(cbx, idUser) {
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			
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
