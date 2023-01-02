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

var btn = document.getElementsByClassName("toggle_edit_comment");
for (var i = btn.length - 1; i >= 0; i--) {
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      let node = this.parentElement.nextElementSibling;
      if (node.style.display !== "none") {
        node.style.display = "none";
      } else {
        node.style.display = "block";
      }
    };
  }
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

var btn = document.getElementsByClassName("toggle_edit_reply");
for (var i = btn.length - 1; i >= 0; i--) {
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      let node = this.parentElement.nextElementSibling;
      if (node.style.display !== "none") {
        node.style.display = "none";
      } else {
        node.style.display = "block";
      }
    };
  }
}

var btn = document.getElementsByClassName("toggle_create_reply");
for (var i = btn.length - 1; i >= 0; i--) {
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      if (this.nextElementSibling.style.display !== "none") {
        this.nextElementSibling.style.display = "none";
      } else {
        this.nextElementSibling.style.display = "block";
      }
    };
  }
}

var btn = document.getElementsByClassName("comment-label");
for (var i = btn.length - 1; i >= 0; i--) {
  if (btn[i] != null && btn[i].value == '') {
    btn[i].onclick = function () {
      let node = this.parentElement.nextElementSibling;
      while (!(node.classList.contains('post-replies'))) {
        node = node.nextElementSibling;
      }
      let childnode = node.firstElementChild;
      if (childnode.style.display !== "none") {
        childnode.style.display = "none";
      } else {
        childnode.style.display = "block";
      }
    };
  }
}




var loadFile = function (event) {
  var output = document.getElementById('output');
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function () {
    URL.revokeObjectURL(output.src) // free memory
  }
};

function countChars(obj, obj2, length) {
  var maxLength = length;
  var strLength = obj.value.length;

  if (strLength == maxLength) {
    obj2.innerHTML = '<span style="color: red;">' + strLength + ' out of ' + maxLength + ' characters</span>';
  } else {
    obj2.innerHTML = strLength + ' out of ' + maxLength + ' characters';
  }
}

if (document.getElementById("edit_profile_form")) {
  document.getElementById("edit_profile_form").reset();
}

if (document.getElementById("create_post_form")) {
  document.getElementById("create_post_form").reset();
}

if (document.getElementById("create_comment_form")) {
  document.getElementById("create_comment_form").reset();
}

if (document.getElementById("edit_comment_form")) {
  document.getElementById("edit_comment_form").reset();
}

if (document.getElementById("create_reply_form")) {
  document.getElementById("create_reply_form").reset();
}

if (document.getElementById("edit_reply_form")) {
  document.getElementById("edit_reply_form").reset();
}

if (document.getElementById("charNumName")) {
  var count_chars_edit_profile_name = document.getElementById('charNumName');
  count_chars_edit_profile_name.onload = countChars(document.getElementById('edit_profile_name'), count_chars_edit_profile_name, 30);
}

if (document.getElementById("charNumDescription")) {
  var count_chars_edit_profile_description = document.getElementById('charNumDescription');
  count_chars_edit_profile_description.onload = countChars(document.getElementById('edit_profile_description'), count_chars_edit_profile_description, 280);
}

if (document.getElementById("charNumTextEdit")) {
  var count_chars_edit_post_text = document.getElementById('charNumTextEdit');
  count_chars_edit_post_text.onload = countChars(document.getElementById('edit_post_text'), count_chars_edit_post_text, 280);
}

if (document.getElementById("charNumCommentEdit")) {
  var count_chars_edit_comment_text = document.getElementById('charNumCommentEdit');
  count_chars_edit_comment_text.onload = countChars(document.getElementById('edit_comment_text'), count_chars_edit_comment_text, 280);
}

if (document.getElementById("charNumReplyEdit")) {
  var count_chars_edit_reply_text = document.getElementById('charNumReplyEdit');
  count_chars_edit_reply_text.onload = countChars(document.getElementById('edit_reply_text'), count_chars_edit_reply_text, 280);
}

function sameValue(element, value) {
  if (element.value == value) {
    element.selected = "selected";
  }
}

function selectOption(element, value) {
  var options = element.getElementsByTagName("option");
  for (var i = options.length - 1; i >= 0; i--) {
    sameValue(options[i], value);
  }
}

if (document.getElementById('edit_profile_gender')) {
  selectOption(document.getElementById('edit_profile_gender'), document.getElementById('edit_profile_gender').getAttribute('data-gender'));
}

if (document.getElementById('edit_post_visibility')) {
  selectOption(document.getElementById('edit_post_visibility'), document.getElementById('edit_post_visibility').getAttribute('data-visibility'));
}



function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);

  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').getAttribute('value'));
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function build_search_results_dropdown(response_json) {
  let new_dropdown = document.createElement('div');
  new_dropdown.classList.add('dropdown');
  new_dropdown.classList.add('search_results_dropdown');
  let new_dropdown_menu = document.createElement('div');
  new_dropdown_menu.classList.add('dropdown-menu');
  new_dropdown_menu.classList.add('show');
  new_dropdown_menu.setAttribute('aria-labelledby', 'dropdownMenuLink');
  new_dropdown.appendChild(new_dropdown_menu);
  let new_dropdown_menu_ul = document.createElement('ul');
  new_dropdown_menu_ul.classList.add('list-group');
  new_dropdown_menu_ul.classList.add('list-group-flush');
  new_dropdown_menu.appendChild(new_dropdown_menu_ul);
  // add response_json.posts 
  for (let i = 0; i < response_json.posts.length; i++) {
    let new_dropdown_menu_ul_li = document.createElement('li');
    new_dropdown_menu_ul_li.classList.add('list-group-item');
    new_dropdown_menu_ul_li.classList.add('search_results_dropdown_item');
    new_dropdown_menu_ul_li.setAttribute('data-post-id', response_json.posts[i].id);
    new_dropdown_menu_ul_li.style.cursor = 'pointer';
    new_dropdown_menu_ul_li.style.display = 'flex';
    new_dropdown_menu_ul_li.style.justifyContent = 'space-between';


    let img_el = document.createElement('img');
    img_el.setAttribute('src', response_json.posts[i].photo);
    img_el.setAttribute('width', '50px');
    img_el.setAttribute('height', '50px');
    img_el.setAttribute('alt', 'post photo');
    img_el.style.borderRadius = '50%';
    img_el.style.marginRight = '10px';

    new_dropdown_menu_ul_li.appendChild(img_el);


    let text_el = document.createElement('span');
    text_el.innerHTML = response_json.posts[i].text;
    //cut the text to the first 50 characters and add '...' if the text is longer than 50 characters
    if (text_el.innerHTML.length > 50) {
      text_el.innerHTML = text_el.innerHTML.substring(0, 50) + '...';
    }

    new_dropdown_menu_ul_li.appendChild(text_el);

    new_dropdown_menu_ul.appendChild(new_dropdown_menu_ul_li);

    new_dropdown_menu_ul_li.addEventListener('click', function (event) {
      let post_id = event.target.getAttribute('data-post-id');
      let post_title = event.target.getAttribute('data-post-title');
      let search_input = document.querySelector('input[name="search"]');

      search_input.value = post_title;
      remove_dropdown_from_search_input(search_input);
      window.location.href = '/posts/' + post_id;
    });
  }



  // add response_json.users
  for (let i = 0; i < response_json.users.data.length; i++) {
    let new_dropdown_menu_ul_li = document.createElement('li');
    new_dropdown_menu_ul_li.classList.add('list-group-item');
    new_dropdown_menu_ul_li.classList.add('search_results_dropdown_item');
    new_dropdown_menu_ul_li.setAttribute('data-user-id', response_json.users.data[i].id);
    new_dropdown_menu_ul_li.style.cursor = 'pointer';
    new_dropdown_menu_ul_li.style.display = 'flex';
    new_dropdown_menu_ul_li.style.justifyContent = 'left';
    new_dropdown_menu_ul_li.style.alignItems = 'center';



    let img_el = document.createElement('img');
    img_el.setAttribute('src', response_json.users.data[i].photo);
    img_el.setAttribute('width', '50px');
    img_el.setAttribute('height', '50px');
    img_el.setAttribute('alt', 'user photo');
    img_el.style.borderRadius = '50%';
    img_el.style.marginRight = '10px';

    new_dropdown_menu_ul_li.appendChild(img_el);


    let text_el = document.createElement('span');
    text_el.innerHTML = response_json.users.data[i].name;
    //cut the text to the first 50 characters and add '...' if the text is longer than 50 characters
    if (text_el.innerHTML.length > 50) {
      text_el.innerHTML = text_el.innerHTML.substring(0, 50) + '...';
    }

    new_dropdown_menu_ul_li.appendChild(text_el);

    new_dropdown_menu_ul.appendChild(new_dropdown_menu_ul_li);

    new_dropdown_menu_ul_li.addEventListener('click', function (event) {
      let user_id = event.target.getAttribute('data-user-id');
      let user_name = event.target.getAttribute('data-user-name');
      let search_input = document.querySelector('input[name="search"]');
      search_input.value = user_name;
      remove_dropdown_from_search_input(search_input);
      window.location.href = '/users/' + user_id;
    });
  }




  return new_dropdown;
}


function add_dropwon_to_search_input(search_input, dropdown) {
  let search_input_parent = search_input.parentNode;
  search_input_parent.appendChild(dropdown);
}

function remove_dropdown_from_search_input(search_input) {
  let search_input_parent = search_input.parentNode;
  let dropdown = search_input_parent.querySelector('.search_results_dropdown');
  if (dropdown) search_input_parent.removeChild(dropdown);
}



window.addEventListener("load", function () {

  let search = document.querySelector('input[name="search"]');

  //remove autocomplete
  search.setAttribute('autocomplete', 'off');

  search.addEventListener('input', function (event) {
    let search_query = event.target.value;
    if (!search_query) {
      remove_dropdown_from_search_input(search);
      return;
    }
    sendAjaxRequest("POST", "/api/search", { "search": search_query }, function (event) {
      let response_json_str = event.target.response;
      let response_json = JSON.parse(response_json_str);
      console.log(response_json);

      remove_dropdown_from_search_input(search);
      let dropdown = build_search_results_dropdown(response_json);
      add_dropwon_to_search_input(search, dropdown);


    })
  })
})

$(document).ready(function () {
  // Bind a click event to the "Add Reaction" button
  $('.reaction-label').click(function () {
    e.preventDefault(); // Prevent the form from being submitted

    var form = $(this).closest('form');

    var reactionType = $(this).val();
    var postId = form.find('input[name=id]').val();
    var csrfToken = form.find('input[name=_token]').val();

    // Make an AJAX request to the server
    $.ajax({
      url: '/posts/' + postId + '/reaction',
      method: 'POST',
      data: {
        reaction_type: reactionType,
        post_id: postId,
        _token: csrfToken
      },
      success: function (response) {
        // The reaction was added successfully
        // Update the reaction count and display a message
        // ...
        var reactionCountSpan = form.find('.reaction-count');
        var currentReactionCount = parseInt(reactionCountSpan.text());
        reactionCountSpan.text(currentReactionCount + 1);
      },
      error: function (error) {
        // There was an error adding the reaction
        // Display an error message
        // ...
      },
    });
  });
});
