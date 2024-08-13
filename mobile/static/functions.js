function resetStyle(id){
    document.getElementById(id).classList.remove("is-valid");
}

function updateRegistro(id){
    let data = {
        id: id,
        obs: document.getElementById(id).value
    }
    fetch('/registros',{ 
        method: 'post',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(
        r=>r.json()
    ).then(json=>{
        console.log(json)
        document.getElementById(id).classList.add("is-valid");
        document.getElementById("salida"+id).innerHTML = json.salida;
        /* do stuff with response */
    }).catch((error) => {
        console.log(error)
    })
}
