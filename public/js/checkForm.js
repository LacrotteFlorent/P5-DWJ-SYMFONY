// script for test form

// constraints
function AbstractConstraint(id, value) {
    
}

function constraintLength(id, values){
    if(values['min']){
        if($(id).val() >= values['min']){
            $(id).css('background-color', '#fff'),$(id).css('color', '#495057');
            $(id + ' + .errorFormJs ').remove();
            console.log("slength" + this.status);
            return true;
        }
        else{
            $(id).css('background-color', '#D78E8C'),$(id).css('color', 'white');
            $(id + ' + .errorFormJs ').remove();
            $(id).after('<small class="text-info errorFormJs">' + values.minMessage + '</small>');
            console.log("lenght" + this.status);
            return false;
        }
    }
    if(values['max']){
        if($(id).val() >= values['max']){
            $(id).css('background-color', '#fff'),$(id).css('color', '#495057');
            $(id + ' + .errorFormJs ').remove();
            return true;
        }
        else{
            $(id).css('background-color', '#D78E8C'),$(id).css('color', 'white');
            $(id + ' + .errorFormJs ').remove();
            $(id).after('<small class="text-info errorFormJs">' + values.maxMessage + '</small>');
            return false;
        }
    }
}

function constraintNotBlank(id, values){
    if($(id).val()){
        $(id).css('background-color', '#fff'),$(id).css('color', '#495057');
        $(id + ' + .errorFormJs ').remove();
        return true;
    }
    else{
        $(id).css('background-color', '#D78E8C'),$(id).css('color', 'white');
        $(id + ' + .errorFormJs ').remove();
        $(id).after('<small class="text-info errorFormJs">Ce champ ne peut être vide</small>');
        return false;
    }
}

function constraintEmail(id, values){
    let mailFormat = new RegExp(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,3}$/);
    if(mailFormat.test($(id).val())){
        $(id).css('background-color', '#fff'),$(id).css('color', '#495057');
        $(id + ' + .errorFormJs ').remove();
        return true;
    }
    else{
        $(id).css('background-color', '#D78E8C'),$(id).css('color', 'white');
        $(id + ' + .errorFormJs ').remove();
        $(id).after('<small class="text-info errorFormJs">Entrez un email valide</small>');
        return false;
    }
}

// Input class
class Input{
    constructor(id, constraints){
        this.id = id;
        this.constraints = constraints;
        this.response = true;
    }

    test() {
        if(Array.isArray(this.constraints)){
            for(var constraint of this.constraints){
                if(!window[constraint[0]](this.id, constraint[1])){
                    this.response = false;
                };
            }
        }
        else{
            if(!window[this.constraint[0]](this.id, this.constraint[1])){
                this.response = false;
            };
        }

        return this.response;
    }
}

// Form class
class Form{
    constructor(name, requestURL, idSubmitBtn){
        this.idSubmitBtn = idSubmitBtn;
        this.requestURL = requestURL;
        this.name = name;
        this.inputs = [];
        this.status = false;
        this.requestData();
    }

    requestData(){
        var request = new XMLHttpRequest();
        request.open('GET', this.requestURL);
        request.responseType = 'json';
        request.send();

        request.onload = function () {
            console.log(request.response);
            this.addInputs(request.response);
    
            $(this.idSubmitBtn).click(function(){
                this.validate();
                if(!this.status){
                    event.preventDefault();
                }
                console.log("status after click" + this.status);
            }.bind(this));
        }.bind(this);
    }

    validate(){
        this.status = true;
        console.log(this.inputs);
        for(let input of this.inputs){
            if(!input.test()){
                this.status = false;
            }
            console.log("status loop click" + this.status);
        }
        return this.status;
    }

    addInputs(apiResponse){
        for(let inputForm of apiResponse){
            var constraints = []; 
            for(let i = 0; i < inputForm[1].length; i++){
                let bag = [];
                bag.push(inputForm[1][i]);
                bag.push(inputForm[2][i]);
                constraints.push(bag);
            }
            this.inputs.push(new Input(inputForm[0], constraints));
        }
    }
}

// script
$(document).ready(function(){
    let form = new Form("contact", 'http://localhost:8000/contact/checkForm', '#contact_send');
})