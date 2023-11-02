document.addEventListener("DOMContentLoaded", function () {
    const eliminarBtn = document.getElementById("eliminarBtn");
    let confirmado = false;

    eliminarBtn.addEventListener("click", function () {
        if (!confirmado) {
            eliminarBtn.textContent = "Seguro? No hay vuelta atrás";
            confirmado = true;
        } else {
            // Envía el formulario si se ha confirmado
            const formEliminarUser = document.forms["formEliminarUser"];
          
            formEliminarUser.submit();
            console.log("se envia el formulario");
        }
    });
});

