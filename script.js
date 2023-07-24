document.addEventListener("DOMContentLoaded", async () => {
    const pokemonContainer = document.getElementById("pokemon-container");
    const viewBagButton = document.getElementById("viewBag");
    let bag = [];
  
    // Función para obtener los datos de la API
    async function getPokemonData() {
      try {
        const response = await fetch("https://pokeapi.co/api/v2/pokemon?limit=20&offset=0");
        const data = await response.json();
        return data.results;
      } catch (error) {
        console.error("Error fetching Pokémon data:", error);
        return [];
      }
    }
  
    // Función para mostrar la información en tarjetas
    function displayPokemonCards(pokemonData) {
      pokemonContainer.innerHTML = "";
      pokemonData.forEach((pokemon) => {
        fetch(pokemon.url)
          .then((response) => response.json())
          .then((pokemonDetails) => {
            const card = document.createElement("div");
            card.className = "card";
            card.innerHTML = `
              <img src="${pokemonDetails.sprites.front_default}" alt="${pokemonDetails.name}">
              <h3>${pokemonDetails.name}</h3>
              <p>Tipo: ${pokemonDetails.types.map((type) => type.type.name).join(", ")}</p>
              <button class="addToBagBtn">Agregar a la bolsa</button>
            `;
            pokemonContainer.appendChild(card);
  
            // Evento para agregar a la bolsa al hacer clic en el botón
            const addToBagButton = card.querySelector(".addToBagBtn");
            addToBagButton.addEventListener("click", () => {
              const name = pokemonDetails.name;
              const type = pokemonDetails.types.map((type) => type.type.name).join(", ");
              const imageSrc = pokemonDetails.sprites.front_default;
              bag.push({ name, type, imageSrc });
              // Almacenar en LocalStorage
              localStorage.setItem("pokemonBag", JSON.stringify(bag));
            });
          })
          .catch((error) => console.error("Error fetching Pokémon details:", error));
      });
    }
  
    // Evento para cargar y mostrar los Pokémon
    getPokemonData().then((pokemonData) => {
      displayPokemonCards(pokemonData);
    });
    viewBagButton.addEventListener("click", () => {
        window.location.href = "bag.html";
      });
  });
  