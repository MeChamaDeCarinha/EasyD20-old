function togglePass(arr_input, id_check){
    arr_input.forEach(element => {
        if(document.getElementById(id_check).checked == true){
            document.getElementById(element).type = "text";
        }
        else{
            document.getElementById(element).type = "password";
        }
    });
}