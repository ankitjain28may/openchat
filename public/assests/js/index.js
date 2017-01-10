var flag = 0;
var pre = "";

// Websocket Connection Open
var conn = new WebSocket("ws://192.168.43.138:8080");
conn.onopen = function() {
  // console.log("Connection established!");
  init();
};

// On Message
conn.onmessage = function(e) {
  var msg = JSON.parse(e.data);
  console.log(msg);
  if (!width()) {
    SideBar(msg.sidebar);
  } else {
    if (document.getElementById("conversation").style.display == "none") {
      SideBar(msg.sidebar);
    }
  }

  if (msg.initial !== undefined) {
    SideBar(msg.initial);
  }

  if (msg.conversation !== undefined) {
    updateConversation(msg.conversation);
  }

  if (msg.reply !== undefined) {
    var textAreaId = $("#text_reply").attr("name");
    if (width()) {
      textAreaId = $(".text_icon #text_reply").attr("name");
    }
    if (msg.reply[0].id === textAreaId) {
      updateConversation(msg.reply);
    }
  }

  if (msg.Search !== undefined) {
    searchResult(msg.Search);
  }

  if (msg.Compose !== undefined) {
    composeResult(msg.Compose);
  }
};

// For First time
function init() {
  conn.send("OpenChat initiated..!");
}

// For updating Sidebar
function SideBar(msg) {
  mobile("sidebar");
  // Getting Div
  if (msg != null) {
    createSidebarElement(msg);
  }
}

// SideBar Load Request
function sidebarRequest() {
  conn.send("Load Sidebar");
}

// Update Current Conversation
function updateConversation(data) {

  if (!width()) {
    sidebarRequest();
  }

  var ele = document.getElementById("conversation");
  ele.innerHTML = "";

  if (data[0].type === 1) {
    // For showing previous message
    if (data[0].load > 10) {
      var aElement = $("<a></a>").text("Show Previous Message!");
      var divElement = $("<div></div>").append(aElement);
      $("#conversation").append(divElement);
      $("#conversation div").addClass("previous");
      $("#conversation div a").attr({
        "onclick": "previous(this)",
        "id": data[0].username,
        "name": data[0].load
      });
    }

    for (var i = data.length - 1; i >= 1; i--) {
      // create element
      var divElement = document.createElement("div");
      if (data[i]["sent_by"] !== data[i].start) {
        divElement.setAttribute("class", "receiver");
      } else {
        divElement.setAttribute("class", "sender");
      }

      ele.appendChild(divElement);
      var brElement = document.createElement("br");
      brElement.setAttribute("style", "clear:both;");
      ele.appendChild(brElement);

      var pElement = document.createElement("p");
      var pText = document.createTextNode(data[i].message);
      pElement.appendChild(pText);
      divElement.appendChild(pElement);

      var h6Element = document.createElement("h6");
      var h6Text = document.createTextNode(data[i].time);
      h6Element.appendChild(h6Text);
      h6Element.setAttribute("class", "message_time");
      pElement.appendChild(h6Element);
    }

    setConversationDetails(data[0]);

    ele.scrollTop = ele.scrollHeight;
  } else {
    setConversationDetails(data[0]);
  }
}

function setConversationDetails(details) {
  $("#chat_heading a").remove("a");
  var aElement = $("<a></a>").text(details.name);
  $("#chat_heading").append(aElement);
  $("#chat_heading a").attr({
    "href": "http://localhost/openchat/account.php/" + details.username
  });

  if (details.login_status === "1") {
    var online = document.createElement("p");
    online.setAttribute("class", "online");
    $("#chat_heading a").append(online);
    $("#chat_heading a p").css({
      "float": "right"
    });
  }

  if (width()) {
    $(".text_icon #text_reply").attr({
      "name": details.id
    });
  } else {
    $("#text_reply").attr({
      "name": details.id
    });
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
  var replyElement = "";
  if (width()) {
    replyElement = ".text_icon #text_reply";
  } else {
    replyElement = "#text_reply";
  }

  var message = [$(replyElement).val()];
  var id = $(replyElement).attr("name");
  $(replyElement).val("");
  // console.log(message);
  var q = {
    "name": id,
    "reply": message
  };
  conn.send(JSON.stringify(q));

}

// Compose new and direct message to anyone
function compose() {
  mobile("compose");
  flag = 1;
  $("#chat_heading a").remove("a");
  document.getElementById("conversation").innerHTML = "";
  $("#compose_text").show();
}

function composeChoose() {
  var text = document.getElementById("compose_name").value;
  if (text !== "") {
    var msg = {
      "value": text,
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

  if (arr !== "Not Found") {
    for (var i = arr.length - 1; i >= 0; i--) {
      var liElement = document.createElement("li");
      var aElement = document.createElement("a");
      var aText = document.createTextNode(arr[i].name);
      aElement.appendChild(aText);
      aElement.setAttribute("href", "#");
      aElement.setAttribute("onclick", "newConversation(this,10)");
      aElement.setAttribute("class", "suggestion");
      aElement.setAttribute("id", arr[i].username);
      liElement.appendChild(aElement);
      ele.appendChild(liElement);
    }
  } else {
    var aElement = $("<a></a>").text("Not Found");
    var liElement = $("<li></li>").append(aElement);
    $("#suggestion").append(liElement);

    $("#suggestion li a").attr({
      "onclick": "myFunction()"
    });
  }
  $("#compose_selection").css("visibility", "visible");
}

function search_choose() {
  var text = $("#search_item").val();
  if (text !== "") {
    var msg = {
      "value": text,
      "search": "search"
    };

    conn.send(JSON.stringify(msg));
  } else {
    conn.send("Load Sidebar");
  }

}

function searchResult(arr) {
  if (arr !== "Not Found") {
    createSidebarElement(arr);
  } else {
    $("#message").text("");
    var aElement = $("<a></a>").text("Not Found");
    $("#message").append(aElement);
    $("#message a").addClass("message");
  }

}

function createSidebarElement(data) {
  // organising content according to time
  var ele = document.getElementById('message');
  ele.innerHTML = "";
  var condition = data.length;
  for (var i = 0; i < condition; i++) {
    var aElement = document.createElement("a"); //creating element a
    var aText = document.createTextNode(data[i].name);
    aElement.appendChild(aText);
    aElement.setAttribute("id", data[i].username);
    aElement.setAttribute("href", "message.php#" + data[i].username);
    aElement.setAttribute("class", "message");
    aElement.setAttribute("onclick", "newConversation(this,10)");
    ele.appendChild(aElement);

    // creating element span for showing time
    var spanElement = document.createElement("span");
    var spanText = document.createTextNode(data[i].time);
    spanElement.appendChild(spanText);
    spanElement.setAttribute("class", "message_time");
    aElement.appendChild(spanElement);

    if (data[i].login_status === "1") {
      var online = document.createElement("div");
      online.setAttribute("class", "online");
      aElement.appendChild(online);
    }
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
    if (ele == "previous") {
      pre = "1";
    } else {
      pre = "";
    }
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
  if (window.innerWidth < 500) {
    return true;
  }
  return false;
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

    recognition.onerror = function() {
      recognition.stop();
    }

  }
}

console.log("Hello, Contact me at ankitjain28may77@gmail.com");