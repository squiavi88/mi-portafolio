let operacion;
let div = document.getElementById("article-1");
let frmr = document.getElementById("frm-r");
let frmfd = document.getElementById("frm-fd");
let frmd = document.getElementById("frm-m");

document.querySelector("#sidebar").addEventListener("click", (e) => {

  if (e.target.matches("#home")) {
    document.getElementById('article-1').style.display = "none";
    document.getElementById('frm-2').style.display = "none";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('modif').style.display = "none";
    document.getElementById('consulta').style.display = "none";
  }

  if (e.target.matches("#cargar")) {
    operacion = document.getElementById("cargar").getAttribute("value");
    document.getElementById("article-1").style.display = "none";
    document.getElementById("frm-2").style.display = "none";

    cargarOperacion(operacion);
  }
  if (e.target.matches("#mostrar")) {
    operacion = document.getElementById("mostrar").getAttribute("value");
    document.getElementById("frm-2").style.display = "none";
    document.getElementById("article-1").style.display = "block";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('consulta').style.display = "none";
    document.getElementById('modif').style.display = "none";
    cargarOperacion(operacion);
  }

  if (e.target.matches("#verificar")) {
    document.getElementById("frm-2").style.display = "block";
    document.getElementById("article-1").style.display = "none";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('consulta').style.display = "none";
    document.getElementById('modif').style.display = "none";
  }

  if (e.target.matches("#faltas")) {
    document.getElementById('article-1').style.display = 'none';
    document.getElementById('frm-2').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('ctr-f').style.display = "block";
    document.getElementById('consulta').style.display = "none";
    document.getElementById('modif').style.display = "none";

  }
  if (e.target.matches("#devolucion")) {
    document.getElementById('article-1').style.display = "none";
    document.getElementById('frm-2').style.display = "none";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "block";
    document.getElementById('modif').style.display = "none";
    document.getElementById('consulta').style.display = "none";

  }
  if (e.target.matches("#consultar")) {
    document.getElementById('article-1').style.display = "none";
    document.getElementById('frm-2').style.display = "none";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('modif').style.display = "none";
    document.getElementById('consulta').style.display = "block";
  }

  if (e.target.matches("#mod")) {
    document.getElementById('article-1').style.display = "none";
    document.getElementById('frm-2').style.display = "none";
    document.getElementById('ctr-f').style.display = "none";
    document.getElementById('ctr-d').style.display = "none";
    document.getElementById('consulta').style.display = "none"
    document.getElementById('modif').style.display = "block";

  }

  function cargarOperacion(operacion) {
    if (operacion === "cargar") {
      fetch("php/controlador.php?operacion=" + operacion)
        .then((res) => res.text())
        .then((res) => {
          Swal.fire({
            text: 'La factura se ha descargado correctamente!',
            confirmButtonColor: '#198754',
          })
        });
    }

    if (operacion === "mostrar") {

      fetch("php/controlador.php?operacion=" + operacion)
        .then((res) => res.json())
        .then((res) => {
          listarTablaFactura(res);
        });
    }

    function listarTablaFactura(res) {
      let tablaFactura = '';
      tablaFactura = `<h3 class="fs-3">Productos Factura</h3>
      <table class="table table-striped"> <thead>
      <th>Codigo</th>
      <th>Nombre</th>
      <th>Unidades</th>
      <th>Caja</th>
      <th>P.Unidad</th>
      <th></thead`;
      for (const data of res) {
        tablaFactura += `    
       <tr><td>${data.codigo_producto}</td>
       <td>${data.nombre_producto}</td>
       <td>${data.unidades_caja}</td>
       <td>${data.caja_facturada}</td>
       <td>${data.precio_unidades}</td> </tr>`;
      }
      tablaFactura += `</table>`;
      div.innerHTML = tablaFactura;
    }
  }
});

/*Codigo para la verificacion de los productos que no se encuentran en la factura */
document.getElementById("frm-2").addEventListener("click", (e) => {

  if (e.target.matches("#cargar")) {
    let codigo = document.getElementById("codigo");
    let bulto = document.getElementById("bulto");

    if (isNaN(parseInt(bulto.value)) || isNaN(parseInt(codigo.value))) {

      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No has ingresado los valores correctos!',
        confirmButtonColor: '#198754',


      })
    } else {
      let cadena =
        "operacion=verificar" + "&codigo=" + codigo.value + "&bulto=" + bulto.value;
      fetch("php/controlador.php?" + cadena)
        .then((res) => res.json())
        //.then((res) => console.log(res));
        .then((res) => {

          if (res == 0) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El producto no existe!',
              confirmButtonColor: '#198754',

            })
          } else {
            listarTablaRepasar(res);
            div.innerHTML = " ";
            codigo.value = " ";
            bulto.value = " ";
          }
        })
    }
  }
  function listarTablaRepasar(res) {
    let info = " ";
    info += `<table class="table table-striped"><tr><th>codigo</th>
    <th>Nombre</th>
    <th>Bulto</th>
    <th>Opcion</th></tr>`;
    for (const data of res) {
      info += `<tr>
     <td>${data.codigo_producto_factura}</td>
     <td>${data.nombre_producto}</td>
     <td>${data.caja_recibida}</td>
     <td><button class="btn btn-danger d-flex " type='button' id='eliminar' 
     value='${data.codigo_producto_factura}'>Eliminar<i class="bi bi-trash ms-2"></i></button></td></tr>`;
    }
    info += `</table>`;
    frmr.innerHTML = info;
  }

  if (e.target.matches("#filtrar")) {
    Swal.fire({
      title: '¿Quieres guardar los cambios?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: 'Guardar',
      confirmButtonColor: '#198754',
      denyButtonText: `No guardar`,
      denyButtonColor: `F2F2F2`,

    }).then((result) => {

      if (result.isConfirmed) {

        fetch("php/controlador.php?operacion=filtrar")
          .then((res) => res.text())
          .then((res) => {
            if (res == 'ok') {
              document.getElementById("frm-2").style.display = "none";
              document.getElementById("article-1").style.display = "block";
            }
          });
        Swal.fire('Guardado!', '', 'success')
      } else if (result.isDenied) {
        Swal.fire('Los cambios no han sido guardados', '', 'info')
      }
    })
  }

  if (e.target.matches('#eliminar')) {
    let codigo = e.target.value;
    fetch("php/controlador.php?operacion=eliminar&codigo=" + codigo)
      .then((res) => (res.ok ? Promise.resolve(res) : Promise.reject(res)))
      .then((res) => res.json())
      .then((res) => {
        listarTablaRepasar(res);
      });
  }
});
/*Codigo para la consulta de productos faltantes y extras*/
document.querySelector('#frm-3').addEventListener('click', (e) => {

  if (e.target.matches('#devolucion')) {

    let fechad = document.getElementById('fecha-d');
    fetch("php/controlador.php?operacion=devolucion&fecha=" + fechad.value)
      .then((res) => res.json())
      .then((res) => {

        if (res == 0) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se ha encontrado productos extras para esta fecha!',
            confirmButtonColor: '#198754',

          })
        } else {
          listarProductos(res);
        }
      });
  }

  if (e.target.matches('#verFaltas')) {

    let fechaf = document.getElementById('fecha-f');
    fetch("php/controlador.php?operacion=faltas&fecha=" + fechaf.value)
      .then((res) => res.json())
      .then((res) => {
        if (res == 0) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se ha encontrado productos Faltantes para esta fecha!',
            confirmButtonColor: '#198754',

          })
        } else {
          listarProductos(res);

        }
      });
  }

  function listarProductos(res) {
    for (const data of res) {
      document.getElementById('article-1').style.display = "block"

      let tablaFactura = ' ';
      tablaFactura = `<table class="table table-striped"> <thead>
      <th>Codigo</th>
      <th>Nombre</th>
      <th>C.facturada</th>
      <th>C.recibida</th>
      <th>Precio Unidad</th>
      <th>Fecha Entrada</th>
      <th></thead`;
      for (const data of res) {
        tablaFactura += `    
       <tr><td>${data.codigo_producto}</td>
       <td>${data.nombre_producto}</td>
       <td>${data.caja_facturada}</td>
       <td>${data.caja_recibida}</td>
       <td>${data.precio_unidades}</td>
       <td>${data.fecha_entrada}</td> </tr>`;
      }
      tablaFactura += `</table>`;
      div.innerHTML = tablaFactura;
    }

  }
})
/*Consultar el catalogo de la tienda*/
document.getElementById('frm-4').addEventListener('click', (e) => {
  if (e.target.matches('#consultar')) {
    let codigo = document.getElementById('cod');

    if (isNaN(parseInt(codigo.value
    )) || isNaN(codigo.value)) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No has ingresado los valores correctos!',
        confirmButtonColor: '#198754',

      })

    } else {

      fetch("php/controlador.php?operacion=consultar&codigo=" + codigo.value)
        .then((res) => (res.ok ? Promise.resolve(res) : Promise.reject(res)))
        .then((res) => res.json())
        //.then((res) => (console.log(res))
        .then((res) => {
          if (res == 0) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El producto no existe!',
              confirmButtonColor: '#198754',

            })
          } else {
            listarCatalogoTienda(res);

          }
        }
        );
    }
  }
  function listarCatalogoTienda(res) {
    document.getElementById('article-1').style.display = "block"

    let tablaFactura = '';
    tablaFactura = `<table class="table table-striped"> <thead>
    <th>Codigo</th>
    <th>Nombre</th>
    <th>P.Unidad</th>
    <th>U.Caja</th>
    <th>Stock</th>
    <th></thead`;
    for (const data of res) {
      tablaFactura += `    
     <tr><td>${data.codigo_producto}</td>
     <td>${data.nombre_producto}</td>
     <td>${data.precio_unidades}</td>
     <td>${data.unidades_caja}</td>
     <td>${data.unidades_tienda}</td> </tr>`;
    }
    tablaFactura += `</table>`;
    div.innerHTML = tablaFactura;
  }
})
/*Modificar el catalogo de la tienda*/
document.getElementById('frm-5').addEventListener('click', (e) => {

  if (e.target.matches('#modificar')) {

    let codigo = document.querySelector('.cod');

    if (codigo.value.length == 0 || isNaN(codigo.value)) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No has ingresado los valores correctos!',
        confirmButtonColor: '#198754',

      })

    }
    else {

      fetch("php/controlador.php?operacion=modificar&codigo=" + codigo.value)
        .then((res) => (res.ok ? Promise.resolve(res) : Promise.reject(res)))
        .then((res) => res.json())
        //then((res) => (console.log(res))
        .then((res) => {

          if (res == 0) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El producto no existe!',
              confirmButtonColor: '#198754',

            })
          } else {

            frmd.style.display = "block";
            modificarCatalogoTienda(res);
          }
        }
        )
    }
  }

  if (e.target.matches('#mod')) {

    let codigo = e.target.value;
    let unidades = document.querySelector('#und');

    if (isNaN(parseInt(unidades.value)) || isNaN(unidades.value)) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No has ingresado los valores correctos!',
        confirmButtonColor: '#198754',

      })
    } else {


      Swal.fire({
        title: '¿Quieres guardar los cambios?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        confirmButtonColor: '#198754',
        denyButtonText: `No guardar`,
        confirmButtonColor: `F2F2F2`,


      }).then((result) => {
        if (result.isConfirmed) {
          let cadena =
            "operacion=mod&codigo=" + codigo + "&unidades=" + unidades.value;

          fetch("php/controlador.php?" + cadena)
            .then((res) => (res.ok ? Promise.resolve(res) : Promise.reject(res)))
            .then((res) => res.text())
            //.then((res) => (console.log(res)))
            .then((res) => {

              if (res == 'ok') {
                frmd.style.display = "none";
              }
            })

          Swal.fire('Guardado!', '', 'success')
        } else if (result.isDenied) {
          Swal.fire('Los cambios no han sido guardados', '', 'info')
        }
      })
    }

  }
  function modificarCatalogoTienda(res) {
    let tablaFactura = '';
    tablaFactura = `<table class="table table-striped"> <thead>
    <th>Codigo</th>
    <th>Nombre</th>
    <th>Stock</th>
    <th>Nuevo Valor</th>
    <th>Opcion</th>
    </thead`;
    for (const data of res) {
      tablaFactura += `    
     <tr><td>${data.codigo_producto}</td>
     <td>${data.nombre_producto}</td>
     <td>${data.unidades_tienda}</td>
     <td><input type="text" class="form-control" id="und" placeholder="Unidades"">
     </td>
     <td>
     <button class="btn btn-danger text-white d-flex" type='button' id='mod' 
     value='${data.codigo_producto}'>Modificar<i class="bi bi-pen ms-2"></i></button>
     </td>
     </tr>`;
    }
    tablaFactura += `</table>`;
    frmd.innerHTML = tablaFactura;
  }

})

/***************LOCAL-STORAGE****************** */
window.addEventListener('load', () => {

  let usuario = JSON.parse(localStorage.getItem('user'));
  let user = `  `;
  let rol = `  `;
  for (const data of usuario) {
    user += ` ${data.nombre} ${data.descripcion} `
    rol += `${data.id}`
  }

  document.querySelector('.user').innerHTML = user;
  if (rol == 1) {
    document.querySelector('.admin').style.display = "block";
  }

})

document.querySelector('#log-out').addEventListener('click', () => {

  localStorage.removeItem('user');
  location.href = "login.html";

})