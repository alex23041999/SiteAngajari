//functie pentru update nume test
function updateNumeTest(newvalue) {
    document.getElementById("numeTest").value = newvalue;
}
//functie update pentru valorile select-ului
function changeNumber(newvalue) {
    document.getElementById("quiznr").value = newvalue;
}
//update input value
function updateInputValue(i) {
    input_text.value = document.getElementById("intrebare" + i).value;
}
//functie care sterge input-urile in caz ca se selecteaza alt numar
function deleteIntrebari(number) {
    var div = document.getElementById(number);
    div.parentNode.removeChild(div);
}
//functie care creaza un nr de input-uri care depinde de ce returneaza select-ul
function createIntrebari() {
    if (document.getElementById("numeTest").value != "") {
        if (document.getElementById("span_error_intrebare")) {
            deleteIntrebari("span_error_intrebare");
        }
        if (document.getElementById("span_error_generare")) {
            deleteIntrebari("span_error_generare");
        }
        var number = document.getElementById("quiznr").value;
        if (number == 0) {
            if (document.getElementById("span_error_intrebare")) {
                deleteIntrebari("span_error_intrebare");
            }
            for (var i = 0; i < 5; i++) {
                if (document.getElementById("intrebare" + i)) {
                    deleteIntrebari("labelIntrebare" + i);
                    deleteIntrebari("intrebare" + i);
                }
            }
            var span_error_intrebare = document.createElement("span");
            span_error_intrebare.textContent = "Nu puteti crea un test cu 0 intrebari!";
            span_error_intrebare.className = "span_intrebare";
            span_error_intrebare.id = "span_error_intrebare";
            document.getElementById('intrebari').appendChild(span_error_intrebare);
        } else {
            if (document.getElementById("span_error_intrebare")) {
                deleteIntrebari("span_error_intrebare");
            }
            for (var i = 0; i < 5; i++) {
                if (document.getElementById("intrebare" + i)) {
                    deleteIntrebari("labelIntrebare" + i);
                    deleteIntrebari("intrebare" + i);
                }
            }
            for (var i = 0; i < number; i++) {
                var label_text = document.createElement("label");
                label_text.className = "label";
                label_text.textContent = "Intrebarea " + (i + 1);
                label_text.id = "labelIntrebare" + i;
                document.getElementById('intrebari').appendChild(label_text);
                var input_text = document.createElement("input");
                input_text.type = "text";
                input_text.placeholder = "Intrebarea este ...";
                input_text.name = "intrebare" + (i + 1);
                input_text.id = "intrebare" + i;
                document.getElementById('intrebari').appendChild(input_text);
            }
            document.getElementById("generare").style.display = "none";
            document.getElementById("adaugare").style.display = "unset";
            const selectNrIntrebari = document.getElementById("quiznr");
            selectNrIntrebari.style.pointerEvents = "none";
        }
    } else if (document.getElementById("quiznr").value == 0 && document.getElementById("numeTest").value == "") {
        if (document.getElementById("span_error_intrebare")) {
            deleteIntrebari("span_error_intrebare");
        }
        if (document.getElementById("span_error_generare")) {
            deleteIntrebari("span_error_generare");
        }
        var span_error_generare = document.createElement("span");
        span_error_generare.textContent = "Nu puteti crea un test fara nume!";
        span_error_generare.className = "span_generare";
        span_error_generare.id = "span_error_generare";
        document.getElementById('intrebari').appendChild(span_error_generare);
        if (document.getElementById("span_error_intrebare")) {
            deleteIntrebari("span_error_intrebare");
        }
        for (var i = 0; i < 5; i++) {
            if (document.getElementById("intrebare" + i)) {
                deleteIntrebari("labelIntrebare" + i);
                deleteIntrebari("intrebare" + i);
            }
        }
        var span_error_intrebare = document.createElement("span");
        span_error_intrebare.textContent = "Nu puteti crea un test cu 0 intrebari!";
        span_error_intrebare.className = "span_intrebare";
        span_error_intrebare.id = "span_error_intrebare";
        document.getElementById('intrebari').appendChild(span_error_intrebare);
    }
    else if (document.getElementById("numeTest").value == "") {
        if (document.getElementById("span_error_intrebare")) {
            deleteIntrebari("span_error_intrebare");
        }
        if (document.getElementById("span_error_generare")) {
            deleteIntrebari("span_error_generare");
        }
        var span_error_generare = document.createElement("span");
        span_error_generare.textContent = "Nu puteti crea un test fara nume!";
        span_error_generare.className = "span_generare";
        span_error_generare.id = "span_error_generare";
        document.getElementById('intrebari').appendChild(span_error_generare);
    }
}
//functie care creaza 4 input-uri pentru fiecare intrebare si dezactiveaza butonul Genereaza cand este apasat
function createRaspunsuri() {
    var counter = 0;
    while (document.getElementById("intrebare" + counter)) {
        counter++;
    }
    $counter_necompletare = 0;
    document.getElementById("quiznr").value = counter;
    for (var i = 0; i < counter; i++) {
        if (document.getElementById('intrebare' + i).value == "") {
            if (document.getElementById("span_error_generare")) {
                deleteIntrebari("span_error_generare");
            }
            var span_error_generare = document.createElement("span");
            span_error_generare.textContent = "Nu puteti lasa intrebarile necompletate!";
            span_error_generare.className = "span_generare";
            span_error_generare.id = "span_error_generare";
            document.getElementById('intrebari').appendChild(span_error_generare);
            $counter_necompletare++;
        }
    }
    if ($counter_necompletare == 0) {
        if (document.getElementById("span_error_generare")) {
            deleteIntrebari("span_error_generare");
        }
        const butonGenereaza = document.getElementById("generare");
        butonGenereaza.disabled = true;
        const selectNrIntrebari = document.getElementById("quiznr");
        selectNrIntrebari.style.pointerEvents = "none";
        var number = document.getElementById("quiznr").value;
        for (var i = 0; i < number; i++) {
            document.getElementById("intrebare" + i).readOnly = true;
        }
        for (var i = 0; i < number; i++) {
            var label_text = document.createElement("label");
            label_text.className = "label";
            label_text.id = "label" + (i + 1);
            label_text.textContent = "Raspunsurile intrebarii numarul  " + (i + 1);
            document.getElementById('raspunsuri').appendChild(label_text);

            //avem cate 4 div-uri diferite cu raspunsuri pentru fiecare intrebare.
            for (var x = 0; x < 4; x++) {
                var div_raspuns = document.createElement("div");
                div_raspuns.className = "raspuns_style";
                div_raspuns.id = "divRaspuns" + i + x;
                document.getElementById('raspunsuri').appendChild(div_raspuns);

                var input_text = document.createElement("input");
                input_text.type = "text";
                input_text.placeholder = "Raspunsul este ...";
                input_text.name = "raspuns" + i + x;
                input_text.id = "raspuns" + i + x;
                input_text.setAttribute('required','');
                document.getElementById('divRaspuns' + i + x).appendChild(input_text);

                //folosesc radiobutton-uri cu acelasi nume pentru a bifa raspunsul corect
                var input_checkbox = document.createElement('input');
                input_checkbox.type = "radio"; 
                input_checkbox.name = "raspuns_checkbox" + i;
                input_checkbox.id = "raspunsCheckBox" + i + x;
                input_checkbox.value =  "raspuns" + i + x;
                input_checkbox.setAttribute('required','');
                document.getElementById('divRaspuns' + i + x).appendChild(input_checkbox);

                var label_check = document.createElement("label");
                label_check.setAttribute('for',"raspunsCheckBox" + i + x);
                document.getElementById('divRaspuns' + i + x).appendChild(label_check);
            }
        }
        const butonRaspunsuri = document.getElementById("adaugare");
        butonRaspunsuri.disabled = true;
        document.getElementById("adaugare").style.display = "none";
        document.getElementById("creareTest").style.display = "unset";
    }
}