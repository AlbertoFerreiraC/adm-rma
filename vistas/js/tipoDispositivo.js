$(document).ready(function(){
  /*if(getMobileOperatingSystem() == "desconocido"){
        swal({
            type: "error",
            title: "Su dispositivo no es compatible con la aplicación!!",
            showConfirmButton: true,
            confirmButtonText: "Aceptar"
            }).then((value) => {
                location.href ="http://127.0.0.1/reserva"; 
            });
          
    }*/
});    
function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
  
    // Windows Phone debe ir primero porque su UA tambien contiene "Android"
   if (/windows phone/i.test(userAgent)) {
      return "desconocido";
   }
  
   if (/android/i.test(userAgent)) {
      return "Android";
  }
  
       if (/iPhone|iPod/.test(userAgent) && !window.MSStream) {
      return "iOS";
  }
  
  return "desconocido";
  }