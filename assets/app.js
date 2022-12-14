/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

function programCounter(){
    let nowDate = new Date();
    nowDate.setMinutes(nowDate.getMinutes() + parseInt(document.getElementById("totalDuration").dataset.time));
    document.getElementById("programCounter").innerHTML = nowDate.toLocaleString('fr-FR', {weekday:"long", year:"numeric", month:"long", day:'numeric', hour:'numeric', minute:'numeric',second:"numeric" });
    setTimeout(programCounter,1000); /* rappel après 2 secondes = 2000 millisecondes */
}


let counterAlert = 5;
console.log("coucou")
function alertCounter() {
    const alerts = document.getElementsByClassName("alert");
    for (alert of alerts) {
        console.log(alert)
        if (counterAlert === 5) {
            alert.textContent += " || suppression dans 5 secondes";
        }
        counterAlert--;
        if (counterAlert >=0) {
            setTimeout(alertCounter, 1000);
        } else {
            alert.remove();
        }
    }
}


programCounter();
alertCounter();