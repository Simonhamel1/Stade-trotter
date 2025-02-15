document.addEventListener("DOMContentLoaded", function() {
  const chatToggleBtn = document.getElementById("chat-toggle-btn");
  const chatIframe = document.getElementById("chat-iframe");

  // État initial : fermé
  chatIframe.dataset.open = '0';
  chatIframe.style.transform = "translateY(110%)";
  chatIframe.style.display = "none";

  chatToggleBtn.addEventListener("click", function() {
    if (chatIframe.dataset.open === '1') {
      // Fermer l'iframe
      chatIframe.style.transform = "translateY(110%)";
      chatIframe.dataset.open = '0';
      // Optionnel : masquer après l'animation
      setTimeout(() => {
        chatIframe.style.display = "none";
      }, 300);
    } else {
      // Afficher et ouvrir l'iframe
      chatIframe.style.display = "block";
      setTimeout(() => { // Pour déclencher la transition
        chatIframe.style.transform = "translateY(0)";
        chatIframe.dataset.open = '1';
      }, 50);
    }
  });
});
