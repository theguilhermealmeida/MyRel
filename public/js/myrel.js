const targetDiv = document.getElementById("create_post");
const btn = document.getElementById("toggle_create_post");
btn.onclick = function () {
  if (targetDiv.style.display !== "none") {
    targetDiv.style.display = "none";
  } else {
    targetDiv.style.display = "block";
  }
};

var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };

document.getElementById("create_post_form").reset();

function countChars(obj){
    var maxLength = 280;
    var strLength = obj.value.length;
    
    if(strLength == maxLength){
        document.getElementById("charNum").innerHTML = '<span style="color: red;">'+strLength+' out of '+maxLength+' characters</span>';
    }else{
        document.getElementById("charNum").innerHTML = strLength+' out of '+maxLength+' characters';
    }
}