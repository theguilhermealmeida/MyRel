const targetDiv = document.getElementById("create_post");
const btn = document.getElementById("toggle_create_post");
btn.onclick = function () {
  if (targetDiv.style.display !== "none") {
    targetDiv.style.display = "none";
  } else {
    targetDiv.style.display = "block";
  }
};