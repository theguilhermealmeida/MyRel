const targetDiv1 = document.getElementById("create_post");
const btn1 = document.getElementById("toggle_create_post");
if (btn1 != null && btn1.value == '') {
  btn1.onclick = function () {
    if (targetDiv1.style.display !== "none") {
      targetDiv1.style.display = "none";
    } else {
      targetDiv1.style.display = "block";
    }
  };
}


const targetDiv2 = document.getElementById("edit_profile");
const btn2 = document.getElementById("toggle_edit_profile");
if (btn2 != null && btn2.value == '') {
  btn2.onclick = function () {
    if (targetDiv2.style.display !== "none") {
      targetDiv2.style.display = "none";
    } else {
      targetDiv2.style.display = "block";
    }
  };
}



var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

function countChars(obj,obj2,length){
    var maxLength = length;
    var strLength = obj.value.length;
    
    if(strLength == maxLength){
        obj2.innerHTML = '<span style="color: red;">'+strLength+' out of '+maxLength+' characters</span>';
    }else{
        obj2.innerHTML = strLength+' out of '+maxLength+' characters';
    }
}

if(document.getElementById("edit_profile_form")){
  document.getElementById("edit_profile_form").reset();
}

if(document.getElementById("create_post_form")){
  document.getElementById("create_post_form").reset();
}

if(document.getElementById("charNumName")){
  var count_chars_edit_profile_name = document.getElementById('charNumName');
  count_chars_edit_profile_name.onload = countChars(document.getElementById('edit_profile_name'),count_chars_edit_profile_name,30);
}

if(document.getElementById("charNumDescription")){
  var count_chars_edit_profile_description = document.getElementById('charNumDescription');
  count_chars_edit_profile_description.onload = countChars(document.getElementById('edit_profile_description'),count_chars_edit_profile_description,280);
}

function sameValue(element,value){
  if(element.value == value){
    element.selected = "selected";
  }
}

function selectOption(element,value){
  var options = element.getElementsByTagName("option");
  for (var i = options.length - 1; i >= 0; i--){
    sameValue(options[i],value);
  }
}

if(document.getElementById('edit_profile_gender')){
  selectOption(document.getElementById('edit_profile_gender'),document.getElementById('edit_profile_gender').getAttribute('data-gender'));
}

  




