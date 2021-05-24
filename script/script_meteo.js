let rest_url_weath;

document.addEventListener('DOMContentLoaded', function(){
    rest_url_pos = "https://freegeoip.app/json/";
    var pos = fetch(rest_url_pos).then(onResponse).then(setPosition);
    Promise.resolve(pos).then(datiMeteo);
}, false);

function onResponse(response){
    console.log("ha risposto");
    return response.json();
}

function setPosition(json){
    let cittaLon = json.longitude;
    let cittaLat = json.latitude;
    citta = json.city;
    rest_url_weath = "http://www.7timer.info/bin/api.pl?lon="+cittaLon+"&lat="+cittaLat+"&product=civillight&output=json";
}

function datiMeteo(){
    fetch(rest_url_weath).then(onResponse).then(setDatiMeteo).catch((error) => console.log('errore fetch meteo'));
}

function setDatiMeteo(json){
    let meteoinfo = document.getElementById("meteoinfo"); 
        
    /*let pos = document.createElement("p");
    pos.id = "currentPos";
    pos.textContent = "Posizione: "+citta;
    meteoinfo.appendChild(pos);*/

    let day = document.createElement("p");
    day.id= "currentDay";
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth()+1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = dd+'/'+mm+'/'+yyyy;
    day.textContent = "Data: "+today;
    meteoinfo.appendChild(day);

    let tempMax = document.createElement("p");
    tempMax.id="currentTempMax",
    tempMax.textContent = "Temperatura Max: "+json.dataseries[0].temp2m.max+"°C";
    meteoinfo.appendChild(tempMax);

    let tempMin = document.createElement("p");
    tempMin.id="currentTempMin",
    tempMin.textContent = "Temperatura Min: "+json.dataseries[0].temp2m.min+"°C";
    meteoinfo.appendChild(tempMin);

    let meteoicon = document.getElementById("meteoicon");

    let img = document.createElement("img");
    img.id = "currentIcon";
    img.src = "http://www.7timer.info/img/misc/about_two_"+setIcona(json.dataseries[0].weather)+".png";
    meteoicon.appendChild(img);
}

function setIcona(str){
    if(str == "mcloudy"){
        return "pcloudy";
    }else{
        return str;
    }
}