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

const targetDiv3 = document.getElementById("edit_post");
const btn3 = document.getElementById("toggle_edit_post");
if (btn3 != null && btn3.value == '') {
  btn3.onclick = function () {
    if (targetDiv3.style.display !== "none") {
      targetDiv3.style.display = "none";
    } else {
      targetDiv3.style.display = "block";
    }
  };
}

const targetDiv4 = document.getElementById("edit_comment");
const btn4 = document.getElementById("toggle_edit_comment");
if (btn4 != null && btn4.value == '') {
  btn4.onclick = function () {
    if (targetDiv4.style.display !== "none") {
      targetDiv4.style.display = "none";
    } else {
      targetDiv4.style.display = "block";
    }
  };
}

const targetDiv5 = document.getElementById("create_comment");
const btn5 = document.getElementById("toggle_create_comment");
if (btn5 != null && btn5.value == '') {
  btn5.onclick = function () {
    if (targetDiv5.style.display !== "none") {
      targetDiv5.style.display = "none";
    } else {
      targetDiv5.style.display = "block";
    }
  };
}

const targetDiv6 = document.getElementById("edit_reply");
const btn6 = document.getElementById("toggle_edit_reply");
if (btn6 != null && btn6.value == '') {
  btn6.onclick = function () {
    if (targetDiv6.style.display !== "none") {
      targetDiv6.style.display = "none";
    } else {
      targetDiv6.style.display = "block";
    }
  };
}

var btn = document.getElementsByClassName("toggle_create_reply");
for (var i = btn.length - 1; i >= 0; i--){
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      if (this.nextElementSibling.style.display !== "none") {
        this.nextElementSibling.style.display  = "none";
      } else {
        this.nextElementSibling.style.display  = "block";
      }
    };
  }
}

var btn = document.getElementsByClassName("comment-label");
for (var i = btn.length - 1; i >= 0; i--){
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      let node = this.parentElement;
      while (!(node.classList.contains('replies'))) {
        node = node.nextElementSibling;
      }
      if (node.style.display !== "none") {
        node.style.display  = "none";
      } else {
        node.style.display  = "block";
      }
    };
  }
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

if(document.getElementById("create_comment_form")){
  document.getElementById("create_comment_form").reset();
}

if(document.getElementById("edit_comment_form")){
  document.getElementById("edit_comment_form").reset();
}

if(document.getElementById("create_reply_form")){
  document.getElementById("create_reply_form").reset();
}

if(document.getElementById("edit_reply_form")){
  document.getElementById("edit_reply_form").reset();
}

if(document.getElementById("charNumName")){
  var count_chars_edit_profile_name = document.getElementById('charNumName');
  count_chars_edit_profile_name.onload = countChars(document.getElementById('edit_profile_name'),count_chars_edit_profile_name,30);
}

if(document.getElementById("charNumDescription")){
  var count_chars_edit_profile_description = document.getElementById('charNumDescription');
  count_chars_edit_profile_description.onload = countChars(document.getElementById('edit_profile_description'),count_chars_edit_profile_description,280);
}

if(document.getElementById("charNumTextEdit")){
  var count_chars_edit_post_text = document.getElementById('charNumTextEdit');
  count_chars_edit_post_text.onload = countChars(document.getElementById('edit_post_text'),count_chars_edit_post_text,280);
}

if(document.getElementById("charNumCommentEdit")){
  var count_chars_edit_comment_text = document.getElementById('charNumCommentEdit');
  count_chars_edit_comment_text.onload = countChars(document.getElementById('edit_comment_text'),count_chars_edit_comment_text,280);
}

if(document.getElementById("charNumReplyEdit")){
  var count_chars_edit_reply_text = document.getElementById('charNumReplyEdit');
  count_chars_edit_reply_text.onload = countChars(document.getElementById('edit_reply_text'),count_chars_edit_reply_text,280);
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

if(document.getElementById('edit_post_visibility')){
  selectOption(document.getElementById('edit_post_visibility'),document.getElementById('edit_post_visibility').getAttribute('data-visibility'));
}

  




