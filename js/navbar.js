document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('header');
    if (header) {
      header.addEventListener('click', () => {
        header.classList.toggle('active');
      });
    }
});
