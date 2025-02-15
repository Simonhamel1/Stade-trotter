document.addEventListener('DOMContentLoaded', () => {
    // Sélectionnez l'élément du header
    const header = document.querySelector('header');
  
    // Vérifier que l'élément existe
    if (header) {
      // Ajoutez un écouteur d'événement sur le clic
      header.addEventListener('click', () => {
        // Bascule la classe "active" pour déclencher la transition
        header.classList.toggle('active');
      });
    }
});
