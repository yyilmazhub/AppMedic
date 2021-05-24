// --- Config --- //
var purecookieTitle = "Cookies."; // Title
var purecookieDesc = "En accédant à ce site, vous autorisez l'utilisation de cookies."; // Description
var purecookieLink = '(Les cookies servent seulement à garder votre token en mémoire afin de vous identifier.)'; // Cookiepolicy link
var purecookieButtonYes = "J'accepte"; // Bouton accepter
var purecookieButtonNo = "Je refuse"; // Bouton refuser
// ---        --- //


function pureFadeIn(elem, display){
  var el = document.getElementById(elem);
  el.style.opacity = 0;
  el.style.display = display || "block";

  (function fade() {
    var val = parseFloat(el.style.opacity);
    if (!((val += .02) > 1)) {
      el.style.opacity = val;
      requestAnimationFrame(fade);
    }
  })();
};
function pureFadeOut(elem){
  var el = document.getElementById(elem);
  el.style.opacity = 1;

  (function fade() {
    if ((el.style.opacity -= .02) < 0) {
      el.style.display = "none";
    } else {
      requestAnimationFrame(fade);
    }
  })();
};

function setCookie(choix) {

  var dateExpiration = new Date();
  dateExpiration.setMonth(dateExpiration.getMonth() + 13);

  document.cookie = "choix=" + choix + "; expires=" + dateExpiration + "; path=/";

  document.location.reload();
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

function cookieConsent() {
  if (getCookie('choix') != 'yes') {
    document.body.innerHTML += '<div class="cookieConsentContainer" id="cookieConsentContainer"><div class="cookieTitle"><p>' + purecookieTitle + '</p></div><div class="cookieDesc"><p>' + purecookieDesc + ' ' + purecookieLink + '</p></div><div class="cookieButtonYes"><a onClick="purecookieDismiss(\'yes\');">' + purecookieButtonYes + '</a></div><div class="cookieButtonNo"><a onClick="purecookieDismiss(\'no\');">' + purecookieButtonNo + '</a></div></div>';
	pureFadeIn("cookieConsentContainer");
  }
}

function purecookieDismiss(choix) {
  setCookie(choix);
  pureFadeOut("cookieConsentContainer");
}

window.onload = function() { cookieConsent(); };
