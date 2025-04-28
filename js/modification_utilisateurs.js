
function modification_utilisateurs(buttonId) {
    // modifier attribut read-only
    const dernierChar = buttonId.charAt(buttonId.length - 1);
    const input_id = "inputbutton" + dernierChar;
    document.getElementById(input_id).readOnly = false;
    
    // Valeur initale de l'input
    const valeur_initale = document.getElementById(input_id).value;

    // Mise en place des nouveaux boutons
    const valider_prefixe = "valider";
    const annuler_prefixe = "annuler";
    document.getElementById(buttonId).innerHTML = '<button class="enregistrer_modifications" id="' + valider_prefixe +'_'+ buttonId + '" onclick="enregistrer_changements(this.id);" value="enregistrer"> Enregistrer </button> <button class="annuler_modifications" id="' + annuler_prefixe +'_'+ buttonId + '" onclick="annuler_changements(this.id);" value="annuler"> Annuler </button>';
    
}

function enregistrer_changements(id) {
    // For module4 Change the value in session and in files and display the new right value (session is enough)
    // valider la modification
    // modifier attribut read-only
    const dernierChar = id.charAt(id.length - 1);
    const valider_id = "inputbutton" + dernierChar;
    // accéder à l'input via son Id
}


function annuler_changements(id) {
    // Put it back to normal with the old data
    const dernierChar = id.charAt(id.length - 1);
    const annuler_id = "inputbutton" + dernierChar;
    // accéder à l'input via son Id
}