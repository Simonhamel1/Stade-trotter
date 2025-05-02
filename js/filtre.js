document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const filterButton = document.getElementById('filterButton');
    const stadiumList = document.getElementById('stadium-list');
    const stadiumItems = document.querySelectorAll('.stadium-item');
    
    // Filtres
    const paysFilter = document.getElementById('pays');
    const capacityFilter = document.getElementById('capacity');
    const typeFilter = document.getElementById('type');
    const roofFilter = document.getElementById('roof');
    const yearFilter = document.getElementById('year');
    const priceFilter = document.getElementById('price');
    
    // Ajouter des événements aux boutons
    searchButton.addEventListener('click', applyFilters);
    filterButton.addEventListener('click', applyFilters);
    
    // Appliquer aussi les filtres lorsqu'on appuie sur Entrée dans le champ de recherche
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        applyFilters();
      }
    });
    
    // Appliquer le filtrage à chaque changement dans les sélecteurs (optionnel)
    const allFilters = [paysFilter, capacityFilter, typeFilter, roofFilter, yearFilter, priceFilter];
    allFilters.forEach(filter => {
      filter.addEventListener('change', applyFilters);
    });
    
    // Fonction principale de filtrage
    function applyFilters() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const paysValue = paysFilter.value.toLowerCase();
      const capacityValue = capacityFilter.value;
      const typeValue = typeFilter.value.toLowerCase();
      const roofValue = roofFilter.value.toLowerCase();
      const yearValue = yearFilter.value;
      const priceValue = priceFilter.value;
      
      let visibleCount = 0;
      
      // Parcourir tous les éléments de stade
      stadiumItems.forEach(item => {
        // Obtenir les données de l'élément
        const name = item.dataset.name || '';
        const description = item.dataset.description || '';
        const pays = item.dataset.pays || '';
        const capacity = parseInt(item.dataset.capacity) || 0;
        const type = item.dataset.type || '';
        const roof = item.dataset.roof || '';
        const year = parseInt(item.dataset.year) || 0;
        const price = parseInt(item.dataset.price) || 0;
        
        // Filtrage par recherche textuelle
        const matchesSearch = !searchValue || 
                             name.includes(searchValue) || 
                             description.includes(searchValue);
        
        // Filtrage par pays
        const matchesPays = !paysValue || pays === paysValue;
        
        // Filtrage par capacité
        let matchesCapacity = true;
        if (capacityValue) {
          if (capacityValue === 'moins-de-20000' && capacity >= 20000) {
            matchesCapacity = false;
          } else if (capacityValue === '20000-50000' && (capacity < 20000 || capacity > 50000)) {
            matchesCapacity = false;
          } else if (capacityValue === 'plus-de-50000' && capacity <= 50000) {
            matchesCapacity = false;
          }
        }
        
        // Filtrage par type de stade
        const matchesType = !typeValue || type === typeValue;
        
        // Filtrage par type de toit
        const matchesRoof = !roofValue || roof === roofValue;
        
        // Filtrage par année de construction
        let matchesYear = true;
        if (yearValue) {
          if (yearValue === 'avant-1950' && year >= 1950) {
            matchesYear = false;
          } else if (yearValue === '1950-2000' && (year < 1950 || year > 2000)) {
            matchesYear = false;
          } else if (yearValue === 'apres-2000' && year <= 2000) {
            matchesYear = false;
          }
        }
        
        // Filtrage par prix
        let matchesPrice = true;
        if (priceValue) {
          if (priceValue === 'moins-de-1000' && price >= 1000) {
            matchesPrice = false;
          } else if (priceValue === '1000-1500' && (price < 1000 || price > 1500)) {
            matchesPrice = false;
          } else if (priceValue === 'plus-de-1500' && price <= 1500) {
            matchesPrice = false;
          }
        }
        
        // Si l'élément correspond à tous les filtres, l'afficher
        const shouldDisplay = matchesSearch && matchesPays && matchesCapacity && 
                            matchesType && matchesRoof && matchesYear && matchesPrice;
        
        if (shouldDisplay) {
          item.style.display = '';
          visibleCount++;
        } else {
          item.style.display = 'none';
        }
      });
      
      // Afficher un message si aucun résultat
      if (visibleCount === 0) {
        // Si un message "aucun résultat" existe déjà, ne pas en créer un nouveau
        if (!document.getElementById('no-results')) {
          const noResults = document.createElement('div');
          noResults.id = 'no-results';
          noResults.className = 'no-results';
          noResults.textContent = 'Aucun stade ne correspond à vos critères de recherche.';
          stadiumList.appendChild(noResults);
        }
      } else {
        // Supprimer le message "aucun résultat" s'il existe
        const noResults = document.getElementById('no-results');
        if (noResults) {
          noResults.remove();
        }
      }
    }
    
    // Appliquer les filtres au chargement initial pour prendre en compte les paramètres d'URL
    // (Optionnel, si vous voulez supporter la navigation par URL)
    function applyURLFilters() {
      const urlParams = new URLSearchParams(window.location.search);
      
      // Remplir les champs avec les valeurs de l'URL
      if (urlParams.has('search')) searchInput.value = urlParams.get('search');
      if (urlParams.has('pays')) paysFilter.value = urlParams.get('pays');
      if (urlParams.has('capacity')) capacityFilter.value = urlParams.get('capacity');
      if (urlParams.has('type')) typeFilter.value = urlParams.get('type');
      if (urlParams.has('roof')) roofFilter.value = urlParams.get('roof');
      if (urlParams.has('year')) yearFilter.value = urlParams.get('year');
      if (urlParams.has('price')) priceFilter.value = urlParams.get('price');
      
      // Appliquer les filtres
      applyFilters();
    }
    
    // Exécuter les filtres de l'URL au chargement
    applyURLFilters();
  });