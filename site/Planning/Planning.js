// Fonction retournant un XMLHttpRequest, (légèrement) adaptée pour tenir compte du navigateur
function getXHR() {
   var res = null;     
   if (window.XMLHttpRequest) // Firefox 
      res = new XMLHttpRequest(); 
   else if (window.ActiveXObject) // Internet Explorer 
      res = new ActiveXObject("Microsoft.XMLHTTP"); 
   // else XMLHttpRequest non supporté ?
   return res; 
}

// L'objet XMLHttpRequest, global pour la fonction callback
var xhr = getXHR();

/**
 * fonction qui change les valeurs des champs cachés pour enregistrer le nom entré dans le planning
 * 
 * @param valeur
 * @param id_match
 * @param fonction
 */
function enregistrerFonction(idTexte, input, id_match, fonction) {
   if (xhr == null) {
      alert("Désolé, enregistrement impossible.");
      return;
   }

   // Ouverture d'une connexion en mode synchrone (on attend les données)
   var requete = "plugins/system/sourcerer/Planning/www/enregistrerFonction.php?id_match=" + id_match 
                  + "&fonction=" + fonction + "&noms=" + input.value;
   xhr.open("GET", encodeURI(requete), false);
   xhr.send(null);
   // On réécrit la valeur à jour
   var texte = document.getElementById(idTexte);
   while (texte.firstChild != null)
       texte.removeChild(texte.firstChild);

    texte.appendChild(document.createTextNode(xhr.responseText));
   /*
   var liste = xhr.responseText.split("/");
   if (liste.length > 0) {
        texte.appendChild(document.createTextNode(liste[0]));
        var i = 1;
        while (i < liste.length) {
            texte.appendChild(document.createElement('br'));
            texte.appendChild(document.createTextNode(liste[i]));
            i = i + 1;
        }
   }
   */
   // texte.firstChild.data = xhr.responseText;
   input.value = "";
   // document.location.reload();
}

// Ajuste la largeur de la table planning (pour affichage dans une fenêtre séparée)
function ajustePlanning() {
    var table = document.getElementById('tablePlanning');
    table.setAttribute("style", "width: 95%; margin: 10px auto;");
}

