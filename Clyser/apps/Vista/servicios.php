<?php 
session_start();

if (!isset($_SESSION["idUsuario"])) {
header("Location: index.php?error=no_logueado");
exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clyser - servicios</title>
<link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
 <link rel="icon" type="image/png" href="../../public/img/C.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
    

</head>
<body>



  <h2>Servicios disponibles</h2>
 <div class="mensajeria-header">
    <div class="opciones">
        <a href="reservasCliente.php"><i class="fa-solid fa-calendar-check"></i> Ver reservas</a> |
  <?php 
       if ($_SESSION["rol"] === "proveedor") {
        echo '<a href="principalProveedor.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "cliente") {
      echo '<a href="principalCliente.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    } elseif ($_SESSION["rol"] === "admin") {
          echo '<a href="principalAdmin.php"><i class="fa-solid fa-house icono"></i> Inicio</a>';
    }
    ?>
</div>
</div>

  
    <form id="filtroForm">
  <label for="categoria">Filtrar por categoría</label>
  <select id="categoria" name="categoria">
    <option value="">Todas</option>
    <option value="informatica">Informática</option>
    <option value="carpinteria">Carpintería</option>
    <option value="limpieza">Limpieza</option>
    <option value="mecanica">Mecánica</option>
    <option value="construccion">Construcción</option>
    <option value="pintureria">Pinturería</option>
    <option value="plomeria">Plomería</option>
    <option value="otros">Otros</option>
  </select>
  <button type="submit">Buscar</button>
</form>


    <hr>

  
  <div id="listaServicios"></div>
  <script>
   fetch("../Controlador/servicioControlador.php?accion=categorias")
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById("categoria");
                data.forEach(c => {
                    let option = document.createElement("option");
                    option.value = c.categoria;
                    option.textContent = c.categoria;
                    select.appendChild(option);
                });
            });


   function cargarServicios(categoria = "") {
    let url = "../Controlador/servicioControlador.php?accion=mostrar";
    if (categoria !== "") {
        url += "&categoria=" + encodeURIComponent(categoria);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let contenedor = document.getElementById("listaServicios");
            contenedor.innerHTML = ""; // limpiar antes de volver a cargar

            data.forEach(s => {
              const avatar = s.fotoPerfil && s.fotoPerfil !== ""
                ? `../../public/img/uploads/${s.fotoPerfil}`
                : "../../public/img/avatarPredeterminada.png";


                let div = document.createElement("div");
                div.className = "tarjeta-servicio";
                div.innerHTML = `
               <div class="servicio-contenido">

                 <div class="servicio-izquierda">
                 <a href="verPerfil.php?id=${s.idProveedor}" class="servicio-enlace">
                  <img src="${avatar}" alt="${s.proveedor}" class="servicio-avatar">
                   <div class="servicio-info">
                     <h4 class="servicio-proveedor">${s.proveedor}</h4>
                    <p class="servicio-categoria">${s.categoria}</p>
                 </div>
                 </a>
                </div>

                <div class="servicio-derecha">
                    <h3 class="servicio-titulo">${s.titulo}</h3>
                    <p class="servicio-descripcion">${s.descripcion}</p>
                    <p class="servicio-precio"><strong>Precio por hora:</strong> ${s.precio} UYU</p>
                  
                    <div class="servicio-botones">
                    <a href="reservas.php?id=${s.idServicio}" class="btn-reservar"><i class="fa-solid fa-calendar-check"></i>Reservar</a> 
                  <a href="enviarMensaje.php?idDestinatario=${s.idProveedor}" class="btn-contactar">
                  <i class="fa-solid fa-envelope"></i>Contactar</a>
                  </div>
                  </div>
                  </div>

                `;
                contenedor.appendChild(div);
            });
        })
        .catch(err => console.error("error", err));
  }
  
cargarServicios();

        // Cuando se hace submit al formulario, buscar por categoría
        document.getElementById("filtroForm").addEventListener("submit", function(e) {
            e.preventDefault();
            let categoria = document.getElementById("categoria").value;
            cargarServicios(categoria);
        });
  
  </script>

</body>
</html>