
function ajoutQuantite(idRecette)
{
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("resultatAjax").innerHTML=xmlhttp.responseText;
	    }
	}

	quantite = document.getElementById("zoneSaisieQuantite"+idRecette).value;
	
	xmlhttp.open("GET","./ajax/ajoutQuantite.php?id="+idRecette+"&quantite="+quantite,true);
	xmlhttp.send();
}

function suppresionQuantite(idRecette)
{
	if (window.XMLHttpRequest)
	{xmlhttp=new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */}
	else
	{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("resultatAjax").innerHTML=xmlhttp.responseText;
	    }
	}

	quantite = document.getElementById("zoneSaisieQuantite"+idRecette).value;
	
	xmlhttp.open("GET","./ajax/suppressionQuantite.php?id="+idRecette+"&quantite="+quantite,true);
	xmlhttp.send();
}