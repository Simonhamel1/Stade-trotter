let modifiedInputs = [];

function modification_utilisateurs(buttonId) {

    // modifier attribut read-only
    const dernierChar = buttonId.charAt(buttonId.length - 1);
    const input_id = "inputbutton" + dernierChar;
    document.getElementById(input_id).readOnly = false;

    if (!modifiedInputs.includes(input_id)) {
        modifiedInputs.push(input_id);
    }

    // Mise en place des nouveaux boutons
    const valider_prefixe = "valider";
    const annuler_prefixe = "annuler";
    document.getElementById(buttonId).innerHTML = '<button class="enregistrer_modifications" id="' + valider_prefixe +'_'+ buttonId + '" onclick="enregistrer_changements(this.id);" value="enregistrer"> Enregistrer </button> <button class="annuler_modifications" id="' + annuler_prefixe +'_'+ buttonId + '" onclick="annuler_changements(this.id);" value="annuler"> Annuler </button>';
}

function enregistrer_changements(id) {
    // For module4 Change the value in session and in files and display the new right value (session is enough)
    const dernierChar = id.charAt(id.length - 1);
    const input_id = "inputbutton" + dernierChar;
    document.getElementById(input_id).readOnly = true;
    const buttonId = "button" + dernierChar;
    document.getElementById(buttonId).innerHTML = '<button class="permettre_modifications" onclick="modification_utilisateurs(\'' + buttonId + '\');" value="modifier"> Modifier </button>'

    const soumettreBtn = document.getElementById("soumettre_button");
    if (soumettreBtn) {
        soumettreBtn.style.display = "inline-block"; 
    }
} 


function annuler_changements(id) {
    const dernierChar = id.charAt(id.length - 1);
    const input_id = "inputbutton" + dernierChar;
    const buttonId = "button" + dernierChar;
    
    // Get the old value and set it back to read-only
    const input = document.getElementById(input_id);
    input.value = input.defaultValue;
    input.readOnly = true;
    
    // Restore the original modify button
    document.getElementById(buttonId).innerHTML = '<button class="permettre_modifications" onclick="modification_utilisateurs(\'' + buttonId + '\');" value="modifier"> Modifier </button>';
}

function soumettreTousLesChamps() {
    // Récupérer les valeurs de tous les champs modifiés
    const valeursModifiees = {};
    modifiedInputs.forEach(id => {
        valeursModifiees[id] = document.getElementById(id).value;
    });

    // Faire un appel fetch ou AJAX, ou peupler un formulaire caché
    console.log(valeursModifiees);
    // ...envoyer ces données vers un script PHP...
}