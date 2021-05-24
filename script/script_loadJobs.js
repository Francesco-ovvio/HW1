document.addEventListener('DOMContentLoaded', function(){
    fetch('php/currentJobs.php').then(onResponse).then(tabellaLavori);
    fetch('php/prodotti.php').then(onResponse).then(tabellaProdotti);
}, false);

function onResponse(response){
    console.log(response);
    return response.json();
}

function tabellaProdotti(json){
    let div = document.getElementById('tabellaProdotti');

    let table = document.createElement('table');
    table.id = 'cartTable';
        let trH = document.createElement('tr');
            let cf = document.createElement('th');
            cf.innerText = "ID Prodotto";
            trH.appendChild(cf);
            let nome = document.createElement('th');
            nome.innerText = "Nome Prodotto";
            trH.appendChild(nome);
            let tp = document.createElement('th');
            tp.innerText = "Tipologia";
            trH.appendChild(tp);
            let unita = document.createElement('th');
            unita.innerText = "Unità Disp.";
            trH.appendChild(unita);
            let spazDisp = document.createElement('th');
            spazDisp.innerText = "Spazio Disp.";
            trH.appendChild(spazDisp);
        table.appendChild(trH);

        for(i = 0; i<json.length; i++){
            let tr = document.createElement('tr');
                let tdid = document.createElement('td');
                tdid.innerText = json[i].IDprodotto;
                tr.appendChild(tdid);
                let tdn = document.createElement('td');
                tdn.innerText = json[i].nomeProdotto;
                tr.appendChild(tdn);
                let tdtp = document.createElement('td');
                tdtp.innerText = json[i].tipologia;
                tr.appendChild(tdtp);
                let tdqt = document.createElement('td');
                tdqt.innerText = json[i].quantitaTot;
                tr.appendChild(tdqt);
                let tdsd = document.createElement('td');
                tdsd.innerText = json[i].spazioDisp;
                tr.appendChild(tdsd);
            table.appendChild(tr);
        }
    div.appendChild(table);
}

function tabellaLavori(json){
    let div = document.getElementById('tabellaLavori');

    let table = document.createElement('table');
    table.id = 'cartTable';
        let trH = document.createElement('tr');
            let cf = document.createElement('th');
            cf.innerText = "Codice Fiscale";
            trH.appendChild(cf);
            let nome = document.createElement('th');
            nome.innerText = "Nome";
            trH.appendChild(nome);
            let cognome = document.createElement('th');
            cognome.innerText = "Cognome";
            trH.appendChild(cognome);
            let nProd = document.createElement('th');
            nProd.innerText = "In Lavorazione";
            trH.appendChild(nProd);
        table.appendChild(trH);

        for(i = 0; i<json.length; i++){
            let tr = document.createElement('tr');
                let tdcf = document.createElement('td');
                tdcf.innerText = json[i].CF;
                tr.appendChild(tdcf);
                let tdn = document.createElement('td');
                tdn.innerText = json[i].nome;
                tr.appendChild(tdn);
                let tdc = document.createElement('td');
                tdc.innerText = json[i].cognome;
                tr.appendChild(tdc);
                let tdnp = document.createElement('td');
                tdnp.innerText = json[i].nomeProdotto;
                tr.appendChild(tdnp);
            table.appendChild(tr);
        }
    div.appendChild(table);       
}