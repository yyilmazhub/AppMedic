var mymap = L.map('mapid').setView([48.8245306, 2.2743419], 14);


L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoicmF5bWF0ZXIiLCJhIjoiY2tpaWttc3p6MGFidDM0cXBtaWZjZjVhbSJ9.xz86c2JJ41kvHWnkR6eVVA'
}).addTo(mymap);


    $.ajax({
        url: "https://data.issy.com/api/records/1.0/search/?dataset=medecins-generalistes-et-infirmiers&rows=100",
        type: "GET",
        dataType: "json",

    success: function(data, textStatus) {

        for(let nb = 0; nb < 100; nb++){
            if(data.records[nb].fields.specialite == "MEDECIN GENERALISTE"){

                var marker = L.marker(data.records[nb].fields.geolocalisation).addTo(mymap);
                marker.bindPopup("<p style =\"color: #3996B9\";>" + data.records[nb].fields.specialite + "</p>" + "</br></br>" + data.records[nb].fields.civilite + " " + data.records[nb].fields.nom + " "+ data.records[nb].fields.prenom + "</br></br>" + data.records[nb].fields.adresse + "</br></br>" + "<button type=\"submit\" OnClick=\" window.location.href ='./html/reserver.html?idMedecin=" + data.records[nb].recordid + "';\" class=\"btn btn-outline-primary boutton\">Prendre rendez-vous</button>");
            }
        }
    }
});

