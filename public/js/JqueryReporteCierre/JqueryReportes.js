function reporteCierrePdf(idApertura, idCierre) {
    window.open("generarReportes-pdf/".concat(idApertura, "/", idCierre), "_blank");

}

function reporteCierreTicket(idApertura, idCierre) {
    window.open("generarReportes-ticket/"+idApertura+"/"+idCierre,"_blank");
}


function reporteCuadreCaja(idApertura, idCierre) {
    window.open("generarReportes-cuadreCaja/"+idApertura+"/"+idCierre,"_blank");
}