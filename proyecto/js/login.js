
document.getElementById('form').addEventListener('submit', (e) => {
    e.preventDefault();
    let user = document.querySelector('#user');
    let pass = document.querySelector('#pass');
    if (user.value.length == 0 || pass.value.length == 0) {

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Llenar todos los datos!',
            confirmButtonColor:'#198754'   
        })
        
    } else {

        let data = new FormData(e.currentTarget)

        fetch('php/login.php', {
            method: 'POST',
            body: data
        })
            .then((res) => (res.ok ? Promise.resolve(res) : Promise.reject(res)))
            .then((res) => res.json())
            .then((res) => {
                if (res == 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Datos Incorectos!',
                        confirmButtonColor:'#198754'

                    })
                } else {
                    console.log(res);
                    localStorage.setItem('user',JSON.stringify(res));
                    location.href = "index.html";
                }

            })


    }
});