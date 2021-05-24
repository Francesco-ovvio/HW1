document.addEventListener('DOMContentLoaded', function(){
    let datiDb = fetch('php/data.php').then(onResponse).then(creaSchede);
    Promise.resolve(datiDb).then(fetch('php/dataP.php').then(onResponse).then(assocValori).catch((error) => console.log("skippo")));
}, false);

function onResponse(response){
    console.log(response);
    return response.json();
}

function assocValori(json){
    console.log(json);
    let nElem = json.length;
    for(let i = 0; i<nElem; i++){
        let descrItem  = document.getElementById('descrizione'+i);

        let ul = document.createElement('ul');
            let kcal = document.createElement('li');
            kcal.textContent = 'Kcal: '+json[i].hits[0].fields.nf_calories;
            ul.appendChild(kcal);
            let fat = document.createElement('li');
            fat.textContent = 'Grassi: '+json[i].hits[0].fields.nf_total_fat;
            ul.appendChild(fat);
            let prot = document.createElement('li');
            prot.textContent = 'Proteine: '+json[i].hits[0].fields.nf_protein;
            ul.appendChild(prot);
            let carb = document.createElement('li');
            carb.textContent = 'Carboidrati: '+json[i].hits[0].fields.nf_total_carbohydrate;
            ul.appendChild(carb);
            descrItem.appendChild(ul);
    }
}

function creaSchede(json){
    let nElem = json.length;
    let prodGrid = document.getElementById('product-grid');

    for(let j = 0; j<nElem; j++){
        let div = document.createElement('div');
        div.className = 'product';
        div.id = 'prodotto'+j;

            let titolo = document.createElement('div');
            titolo.className = 'titolo';

                let h1 = document.createElement('h1');
                h1.id = 'h1'+j;
                h1.textContent = json[j].nomeProdotto;
                titolo.appendChild(h1);

                let btnimg = document.createElement('img');
                btnimg.id = 'icon';
                btnimg.setAttribute('onclick', 'addFav('+j+')');
                btnimg.src = 'https://i.imgur.com/wgYNMRa.png';
                titolo.appendChild(btnimg);
            div.appendChild(titolo);

            let img = document.createElement('img');
            img.setAttribute('onclick', 'showDesc('+j+')');
            img.id = 'img'+j;
            img.src = json[j].img;
            div.appendChild(img);

            let price = document.createElement('h3');
            price.id = 'price'+j;
            price.textContent = "Prezzo al kg: "+json[j].costoPerUnita+'€';
            div.appendChild(price);

            if(json[j].quantitaTot>0){
                let formOrder = document.createElement('form');
                formOrder.className = 'addCart';
                formOrder.method = 'post';
                formOrder.name = j;
                    
                    let hdnTitle = document.createElement('input');
                    hdnTitle.type = 'hidden';
                    hdnTitle.name = 'itemName';
                    hdnTitle.value = json[j].nomeProdotto;
                    formOrder.appendChild(hdnTitle);

                    let labelQ = document.createElement('label');
                    labelQ.id = 'labelQ';
                    labelQ.textContent = 'Quantità ';
                    formOrder.appendChild(labelQ);

                    let inputQ = document.createElement('input');
                    inputQ.id = 'qty';
                    inputQ.name = 'qty';
                    inputQ.type = 'number';
                    inputQ.max = json[j].quantitaTot;
                    inputQ.value = '1';
                    formOrder.appendChild(inputQ);

                    let btnOrder = document.createElement('input');
                    btnOrder.className = 'btn';
                    btnOrder.id = 'btnOrder';
                    btnOrder.name = 'submitCart';
                    btnOrder.type = 'submit';
                    btnOrder.value = 'Aggiungi al carrello';
                    formOrder.appendChild(btnOrder);
                div.appendChild(formOrder);
            }else{
                let oos = document.createElement('p');
                oos.textContent = 'Momentaneamente non disponibile';
                div.appendChild(oos);
            }

            let descr = document.createElement('div');
            descr.className = 'descr';
            descr.id = 'descrizione'+j;
            descr.textContent = json[j].descrizione;
            div.appendChild(descr);
        prodGrid.appendChild(div);
    }
}