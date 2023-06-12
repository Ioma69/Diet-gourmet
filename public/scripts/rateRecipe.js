document.addEventListener('DOMContentLoaded', function() {
  // On va chercher toutes les étoiles
  const stars = document.querySelectorAll(".la-star");
  
  // On va chercher l'input
  const note = document.querySelector("#note");

  // On boucle sur les étoiles pour ajouter des écouteurs d'évènements
  for (const star of stars) {
    // On écoute le survol
    star.addEventListener("mouseover", function() {
      resetStars();
      this.style.color = "highlights";
      this.classList.add("las");
      this.classList.remove("lar");
      
      // L'élément précédent dans le DOM (de même niveau, balise soeur)
      let previousStar = this.previousElementSibling;

      while (previousStar) {
        // On passe l'étoile qui précède en rouge
        previousStar.style.color = "highlights";
        previousStar.classList.add("las");
        previousStar.classList.remove("lar");
        
        // récupère l'étoile qui la précède
        previousStar = previousStar.previousElementSibling;
      }
    });

    // On écoute le clic
    star.addEventListener("click", function() {
      note.value = this.dataset.value;
      const starContainer = this.parentNode; // Get the parent container of stars
      const recipeId = starContainer.dataset.recipe;
      const rating = this.dataset.value;
      rateRecipe(recipeId, rating);
    });

    star.addEventListener("mouseout", function() {
      resetStars(note.value);
      
    });

  
  }

  /**
   * Reset des étoiles en vérifiant la note dans l'input caché
   * @param {number} note 
   */
  function resetStars(note = 0) {
    for (const star of stars) {
      if (star.dataset.value > note) {
        star.style.color = "highlights";
        star.classList.add("lar");
        star.classList.remove("las");
      } else {
        star.style.color = "hightlights";
        star.classList.add("las");
        star.classList.remove("lar");
      }
    }

  }

  function rateRecipe(recipeId, rating) {
    fetch('/recipe/rate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        recipeId: recipeId,
        rating: rating,
      }),
    })
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          throw new Error("Erreur lors de l'enregistrement de la note");
        }
      })
      .then(data => {
        const starContainer = document.querySelector(`.stars[data-recipe="${recipeId}"]`);
        resetStars(data.averageRating);
        displayAverageRating(starContainer, data.averageRating, data.totalRatings);
      })
      .catch(error => {
        swal({
          title: 'Erreur',
          text: error.message,
          icon: 'error',
        });
        console.error(error);
      });
  }
  
  // Récupére tous les éléments contenant les étoiles
const starsContainers = document.querySelectorAll('.stars');

// Parcour chaque conteneur d'étoiles
starsContainers.forEach(starsContainer => {
  const averageRating = starsContainer.dataset.averageRating; // Récupére la note moyenne


  // Parcour chaque étoile
  const stars = starsContainer.querySelectorAll('i');
  stars.forEach(star => {
    const value = star.dataset.value;

    // Vérifie si l'étoile doit être coloriée ou non
    if (value <= averageRating) {
      star.classList.add('filled-star');
      star.classList.remove('empty-star');
    } else {
      star.classList.add('empty-star');
      star.classList.remove('filled-star');
    }
  });
});

  function displayAverageRating(starContainer, averageRating, totalRatings) {
    const averageRatingElement = starContainer.nextElementSibling.querySelector(".average-rating span");
    averageRatingElement.textContent = averageRating.toFixed(1);
    
    const totalRatingsElement = starContainer.nextElementSibling.nextElementSibling.querySelector(".total-ratings span");
    totalRatingsElement.textContent = totalRatings;
  }
});