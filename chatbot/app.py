from flask import Flask, render_template, request, jsonify

app = Flask(__name__)

# Arbre décisionnel pré-enregistré (les réponses sont fixes)
conversation_tree = {
    "accueil": {
        "texte": "Bienvenue sur notre site ! Que souhaitez-vous faire ?",
        "options": ["Voir Informations", "Contacter", "Voir Produits"]
    },
    "Voir Informations": {
        "texte": "Voici quelques informations sur notre entreprise : Nous sommes spécialisés dans la vente en ligne depuis 10 ans.",
        "options": ["Retour"]
    },
    "Contacter": {
        "texte": "Pour nous contacter, envoyez-nous un email à contact@exemple.com ou appelez-nous au 01 23 45 67 89.",
        "options": ["Retour"]
    },
    "Voir Produits": {
        "texte": "Nous proposons une large gamme de produits. Choisissez une catégorie :",
        "options": ["Produits Électroniques", "Produits de Maison", "Retour"]
    },
    "Produits Électroniques": {
        "texte": "Nos produits électroniques incluent smartphones, ordinateurs et accessoires. Pour plus de détails, visitez notre catalogue.",
        "options": ["Retour"]
    },
    "Produits de Maison": {
        "texte": "Nous offrons également des articles pour la maison, allant des meubles aux décorations. Consultez notre site pour en savoir plus.",
        "options": ["Retour"]
    },
    "Retour": {
        "texte": "Retour à l'accueil.",
        "options": ["accueil"]
    }
}


@app.route('/')
def home():
    return render_template("chatbot.html") # Affiche le chatbot

@app.route("/chat", methods=["POST"])
def chat():
    data = request.get_json()
    current_node = data.get("node", "accueil")
    if current_node not in conversation_tree:
        return jsonify({"texte": "Option invalide.", "options": []})
    return jsonify(conversation_tree[current_node])

if __name__ == '__main__':
    app.run(debug=True)
