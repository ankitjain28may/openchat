var flag = 0;
var pre = "";

// Websocket Connection Open
var conn = new WebSocket("ws://localhost:8080");
conn.onopen = function (e) {
  console.log("Connection established!");
  init();
};

// On Message
conn.onmessage = function(e) {
  var msg = JSON.parse(e.data);
  // console.log(msg);
  if (!width())
  {
    SideBar(msg.sidebar);
  }

  if (msg.initial !== undefined)
  {
    SideBar(msg.initial);
  }

  if (msg.conversation !== undefined)
  {
    updateConversation(msg.conversation);
  }

  if (msg.Search !== undefined)
  {
    searchResult(msg.Search);
  }

  if (msg.Compose !== undefined)
  {
    composeResult(msg.Compose);
  }
};

conn.onerror = function(evt) {
  console.log(evt.data);
};

// For First time
function init() {
  conn.send("OpenChat initiated..!");
}

// For updating Sidebar
function SideBar(msg) {
  mobile("sidebar");
  // Getting Div
  var ele = document.getElementById("message");
  if (msg != null) {
    $("#message a").remove();
    // organising content according to time
    for(var i = 0; i < msg.length; i++) {
      var para = document.createElement("a");
      var node = document.createTextNode(msg[i]["name"]);
      para.appendChild(node);
      para.setAttribute("id", msg[i]["username"]);
      para.setAttribute("href", "message.php#" + msg[i]["username"]);
      para.setAttribute("class", "message");
      para.setAttribute("onclick", "newConversation(this,10)");
      ele.appendChild(para);

      var bre = document.createElement("span");
      var inp = document.createTextNode(msg[i]["time"]);
      bre.appendChild(inp);
      bre.setAttribute("class", "message_time");
      para.appendChild(bre);

      if (msg[i]["login_status"] == "1") {
        var online = document.createElement("div");
        online.setAttribute("class", "online");
        para.appendChild(online);
      }
    }
  }
}

// SideBar Load Request
function SidebarRequest() {
  conn.send("Load Sidebar");
}

// Update Current Conversation
function updateConversation(arr) {
  SidebarRequest();
  var ele = document.getElementById("conversation");

  if (arr[0].type == 1) {
    ele.innerHTML = "";

    // For showing previous message
    if (arr[0].load > 10) {
      var txt = $("<a></a>").text("Show Previous Message!");
      var te = $("<div></div>").append(txt);
      $("#conversation").append(te);
      $("#conversation div").addClass("previous");
      $("#conversation div a").attr({
        "onclick": "previous(this)",
        "id": arr[0].username,
        "name": arr[0].load
      });
    }

    for (var i = arr.length - 1; i >= 1; i--) {
      // create element
      var para = document.createElement("div");
      if (arr[i]["sent_by"] != arr[i]["start"])
        para.setAttribute("class", "receiver");
      else
        para.setAttribute("class", "sender");

      ele.appendChild(para);
      var bre = document.createElement("br");
      bre.setAttribute("style", "clear:both;");
      ele.appendChild(bre);

      var info = document.createElement("p");
      var node = document.createTextNode(arr[i]["message"]);
      info.appendChild(node);
      para.appendChild(info);

      var tt = document.createElement("h6");
      var inp = document.createTextNode(arr[i]["time"]);
      tt.appendChild(inp);
      tt.setAttribute("class", "message_time");
      info.appendChild(tt);
    }

    $("#chat_heading a").remove("a");
    var txt = $("<a></a>").text(arr[0].name);
    $("#chat_heading").append(txt);
    $("#chat_heading a").attr({
      "href": "http://localhost/openchat/account.php/" + arr[0].username
    });
    // Online
    if (arr[0]["login_status"] == "1") {
      var online = document.createElement("p");
      online.setAttribute("class", "online");
      $("#chat_heading a").append(online);
      $("#chat_heading a p").css({
        "float": "right"
      });
    }

    if (width())
      $(".text_icon #text_reply").attr({
        "name": arr[0]["id"]
      });
    else
      $("#text_reply").attr({
        "name": arr[0]["id"]
      });
    ele.scrollTop = ele.scrollHeight;
  } else {
    ele.innerHTML = "";
    $("#chat_heading a").remove("a");

    var txt = $("<a></a>").text(arr[0].name);
    $("#chat_heading").append(txt);
    $("#chat_heading a").attr({
      "href": "http://localhost/openchat/account.php/" + arr[0].username
    });

    if (arr[0]["login_status"] == "1") {
      var online = document.createElement("p");
      online.setAttribute("class", "online");
      $("#chat_heading a").append(online);
      $("#chat_heading a p").css({
        "float": "right"
      });
    }

    if (width()) {
      $(".text_icon #text_reply").attr({
        "name": arr[0]["id"]
      });
    } else {
      $("#text_reply").attr({
        "name": arr[0]["id"]
      });
    }
  }

}

// Creating new Conversation or Loading Conversation
function newConversation(element, load) {
  mobile("main");
  $("#compose_selection").css("visibility", "hidden");
  flag = 0;
  $("#compose_name").val("");
  $("#search_item").val("");
  $("#compose_text").hide();

  var msg = {
    "username": element.id,
    "load": load,
    "newConversation": "Initiated"
  };
  conn.send(JSON.stringify(msg));

}

// For reply to other messages
function reply() {
  if (width())
    var re = ".text_icon #text_reply";
  else
    var re = "#text_reply";

  var ele = [$(re).val()];
  var id = $(re).attr("name");
  $(re).val("");
  // console.log(ele);
  var p = "";
  var q = {
    "name": id,
    "reply": ele
  };
  q = JSON.stringify(q);
  // console.log(q);
  conn.send(q);

}

// Compose new and direct message to anyone
function compose() {
  mobile("compose");
  flag = 1;
  $("#chat_heading a").remove("a");
  document.getElementById("conversation").innerHTML = "";
  $("#compose_text").show();
}

function ComposeChoose() {
  var value = document.getElementById("compose_name").value;
  if (value != "") {
    var msg = {
      "value": value,
      "Compose": "Compose"
    };
    conn.send(JSON.stringify(msg));
  } else {
    $("#compose_selection").css("visibility", "hidden");
  }
}

//compose messages
function composeResult(arr) {
  var ele = document.getElementById("suggestion");
  ele.innerHTML = "";

  if (arr != "Not Found") {
    for (var i = arr.length - 1; i >= 0; i--) {
      var para = document.createElement("li");
      var active = document.createElement("a");
      var node = document.createTextNode(arr[i].name);
      active.appendChild(node);
      active.setAttribute("href", "#");
      active.setAttribute("onclick", "newConversation(this,10)");
      active.setAttribute("class", "suggestion");
      active.setAttribute("id", arr[i].username);
      para.appendChild(active);
      ele.appendChild(para);
    }
  } else {
    var txt = $("<a></a>").text("Not Found");
    var l = $("<li></li>").append(txt);
    $("#suggestion").append(l);
    $("#suggestion li a").attr({
      "onclick": "myFunction()"
    });
  }
  $("#compose_selection").css("visibility", "visible");
}

function search_choose() {
  var value = $("#search_item").val();
  if (value != "") {
    var msg = {
      "value": value,
      "search": "search"
    };
    conn.send(JSON.stringify(msg));
  } else {
    conn.send("Load Sidebar");
  }

}

function searchResult(arr) {
  var ele = document.getElementById("message");
  if (arr != "Not Found") {
    ele.innerHTML = "";
    for (var i = arr.length - 1; i >= 0; i--) // organising content according to time
    {
      var para = document.createElement("a"); //creating element a
      var node = document.createTextNode(arr[i]["name"]);
      para.appendChild(node);
      para.setAttribute("id", arr[i]["username"]);
      para.setAttribute("href", "message.php#" + arr[i]["username"]);
      para.setAttribute("class", "message");
      para.setAttribute("onclick", "newConversation(this,10)");
      ele.appendChild(para);

      var bre = document.createElement("span"); // creating element span for showing time
      var inp = document.createTextNode(arr[i]["time"]);
      bre.appendChild(inp);
      bre.setAttribute("class", "message_time");
      para.appendChild(bre);
    }
  } else {
    $("#message").text("");
    var txt = $("<a></a>").text("Not Found");
    $("#message").append(txt);
    $("#message a").addClass("message");
  }

}

window.ondblclick = myFunction;

function myFunction() // Hidden compose message input
{
  $("#compose_selection").css("visibility", "hidden");
  init();
  flag = 0;
  $("#compose_name").val("");
  $("#search_item").val("");
  $("#compose_text").hide();
}

function previous(element) // Load previous messages
{
  mobile("previous");
  var user = element.id;
  var lo = element.name;
  newConversation(element, lo);
}

function mobile(ele) {
  if (width()) {
    mob_hide();
    if (ele == "main") {
      $(".sidebar").hide();
      $(".mob-reply").show();
      $(".chat_name").show();
      $(".chat_name #chat_heading").show();
      if (pre == "") {
        $(".main div").remove("div");
        $(".main br").remove("br");
        $(".chat_name #chat_heading a").remove("a");
      }
      $(".main").show();
    }
    if (ele == "compose") {
      $(".chat_name").show();
      $(".chat_name .compose_text").show();
      $(".sidebar").hide();
      $("#compose_selection").show();
    }
    if (ele == "sidebar") {
      $(".sidebar").show();
    }
    if (ele == "previous")
      pre = "1";
    else
      pre = "";
  }
}

function show_search() {
  // console.log("HE0");
  mob_hide();
  $(".search_message").show();
  $(".sidebar").show();
}

function mob_hide() {
  $(".search_message").hide();
  $(".sidebar").hide();
  $(".main").hide();
  $(".chat_name").hide();
  $(".mob-reply").hide();
}

function width() {
  if (window.innerWidth < 500)
    return true;
}

// Audio Recognization

function startDictation() {

  if (window.hasOwnProperty("webkitSpeechRecognition")) {

    var recognition = new webkitSpeechRecognition();

    recognition.continuous = false;
    recognition.interimResults = false;

    recognition.lang = "en-IN";
    recognition.start();

    recognition.onresult = function(e) {
      document.getElementById("text_reply").value = e.results[0][0].transcript;
      recognition.stop();
      reply();
    };

    recognition.onerror = function(e) {
      recognition.stop();
    }

  }
}

console.log("Hello, Contact me at ankitjain28may77@gmail.com");