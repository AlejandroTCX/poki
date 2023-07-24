document.addEventListener("DOMContentLoaded", () => {
    const bagContainer = document.getElementById("bag-container");
    const bagData = JSON.parse(localStorage.getItem("pokemonBag")) || [];
  
    function displayBagItems() {
      bagContainer.innerHTML = "";
      bagData.forEach((pokemon) => {
        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
          <img src="${pokemon.imageSrc}" alt="${pokemon.name}">
          <h3>${pokemon.name}</h3>
          <p>Tipo: ${pokemon.type}</p>
        `;
        bagContainer.appendChild(card);
      });
    }
  
    displayBagItems();
    document.getElementById("generatePDF").addEventListener("click", function() {
        // Obtener la bolsa de LocalStorage
        const bagData = JSON.parse(localStorage.getItem("pokemonBag")) || [];
        if (bagData.length > 0) { // Verifica si la bolsa contiene datos antes de enviarla
            // Enviar la bolsa al archivo PHP para generar el PDF
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "generate_pdf.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.responseType = "blob"; // Indicar que se espera una respuesta binaria (Blob)
            xhr.send("pokemonBag=" + JSON.stringify(bagData));
            xhr.addEventListener("readystatechange", function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Crear un Blob con la respuesta del servidor
                        const blob = new Blob([xhr.response], { type: "application/pdf" });
    
                        // Generar un enlace para descargar el archivo PDF
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement("a");
                        a.href = url;
                        a.download = "pokemon_bag.pdf"; // Nombre del archivo a descargar
                        document.body.appendChild(a);
                        a.click(); // Simular el clic para iniciar la descarga
    
                        // Limpiar el enlace y liberar el Blob
                        a.remove();
                        URL.revokeObjectURL(url);
                    } else {
                        console.error("Error en la respuesta del servidor:", xhr.status, xhr.statusText);
                    }
                }
            });
        } else {
            alert("La bolsa está vacía. Agrega Pokémon antes de generar el PDF.");
        }
    });
  });