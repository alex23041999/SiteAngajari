function createLanguageField() {
    var button_add = document.createElement('button');
    button_add.type = "button";
    button_add.name = "button_add";
    button_add.id = "button_add";
    button_add.value = "Adauga";
    button_add.textContent = "Adauga";
    document.getElementById('divSelect').appendChild(button_add);
    document.getElementById('button_add').onclick = function () { addAnotherLanguage() };

    var button_delete = document.createElement('button');
    button_delete.type = "button";
    button_delete.name = "button_delete";
    button_delete.id = "button_delete";
    button_delete.value = "Sterge";
    button_delete.textContent = "Sterge";
    document.getElementById('divSelect').appendChild(button_delete);
    document.getElementById('button_delete').onclick = function () { deleteLastLanguage() };

    document.getElementById('divSelect').style.display = "unset";
    document.getElementById('generate').style.display = "none";
}

function addAnotherLanguage() {
    if (!document.getElementById('language' + 1)) {
        document.getElementById('maxLimit').style.display = "none";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+1;
        input_language.id = "language"+1;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages').appendChild(input_language);
        document.getElementById('language'+1).value = document.getElementById('limbaj').value + ".";
        document.getElementById('saveLanguages').style.display ="unset";
    }else if (document.getElementById('language' + 1) && !document.getElementById('language' + 2)) {
        document.getElementById('maxLimit').style.display = "none";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+2;
        input_language.id = "language"+2;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages').appendChild(input_language);
        document.getElementById('language'+2).value = document.getElementById('limbaj').value + ".";
    }else if (document.getElementById('language' + 2) && !document.getElementById('language' + 3)) {
        document.getElementById('maxLimit').style.display = "unset";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+3;
        input_language.id = "language"+3;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages').appendChild(input_language);
        document.getElementById('language'+3).value = document.getElementById('limbaj').value + ".";
    } else if (document.getElementById('language' + 3)) {
        //document.getElementById('maxLimit').style.display = "unset";
        document.getElementById('button_add').disabled = true;
    }
}

function deleteLastLanguage() {
    if (document.getElementById('language' + 3)) {
        document.getElementById('language' + 3).parentNode.removeChild(document.getElementById('language' + 3));
        document.getElementById('maxLimit').style.display = "none";
        document.getElementById('button_add').disabled = false;
    } else if(document.getElementById('language' + 2)){
        document.getElementById('language' + 2).parentNode.removeChild(document.getElementById('language' + 2));
        document.getElementById('maxLimit').style.display = "none";
    }else if(document.getElementById('language' + 1)){
        document.getElementById('language' + 1).parentNode.removeChild(document.getElementById('language' + 1));
        document.getElementById('maxLimit').style.display = "none";
    }else if(!document.getElementById('language'+1)){

    }
}

function createLanguageField1() {
    var button_add = document.createElement('button');
    button_add.type = "button";
    button_add.name = "button_add";
    button_add.id = "button_add";
    button_add.value = "Adauga";
    button_add.textContent = "Adauga";
    document.getElementById('divSelect1').appendChild(button_add);
    document.getElementById('button_add').onclick = function () { addAnotherLanguage1() };

    var button_delete = document.createElement('button');
    button_delete.type = "button";
    button_delete.name = "button_delete";
    button_delete.id = "button_delete";
    button_delete.value = "Sterge";
    button_delete.textContent = "Sterge";
    document.getElementById('divSelect1').appendChild(button_delete);
    document.getElementById('button_delete').onclick = function () { deleteLastLanguage1() };

    document.getElementById('divSelect1').style.display = "unset";
    document.getElementById('generate1').style.display = "none";
}

function addAnotherLanguage1() {
    if (!document.getElementById('language' + 1)) {
        document.getElementById('maxLimit1').style.display = "none";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+1;
        input_language.id = "language"+1;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages1').appendChild(input_language);
        document.getElementById('language'+1).value = document.getElementById('limbaj1').value + ".";
        document.getElementById('updateLanguages').style.display ="unset";
    }else if (document.getElementById('language' + 1) && !document.getElementById('language' + 2)) {
        document.getElementById('maxLimit1').style.display = "none";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+2;
        input_language.id = "language"+2;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages1').appendChild(input_language);
        document.getElementById('language'+2).value = document.getElementById('limbaj1').value + ".";
    }else if (document.getElementById('language' + 2) && !document.getElementById('language' + 3)) {
        document.getElementById('maxLimit1').style.display = "unset";
        document.getElementById('button_add').disabled = false;
        var input_language = document.createElement("input");
        input_language.type = "text";
        input_language.name = "language"+3;
        input_language.id = "language"+3;
        input_language.setAttribute('readonly', true);
        document.getElementById('languages1').appendChild(input_language);
        document.getElementById('language'+3).value = document.getElementById('limbaj1').value + ".";
    } else if (document.getElementById('language' + 3)) {
        //document.getElementById('maxLimit').style.display = "unset";
        document.getElementById('button_add').disabled = true;
    }
}

function deleteLastLanguage1() {
    if (document.getElementById('language' + 3)) {
        document.getElementById('language' + 3).parentNode.removeChild(document.getElementById('language' + 3));
        document.getElementById('maxLimit1').style.display = "none";
        document.getElementById('button_add').disabled = false;
    } else if(document.getElementById('language' + 2)){
        document.getElementById('language' + 2).parentNode.removeChild(document.getElementById('language' + 2));
        document.getElementById('maxLimit1').style.display = "none";
    }else if(document.getElementById('language' + 1)){
        document.getElementById('language' + 1).parentNode.removeChild(document.getElementById('language' + 1));
        document.getElementById('maxLimit1').style.display = "none";
    }else if(!document.getElementById('language'+1)){

    }
}