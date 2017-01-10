
// Websocket Connection Open
var conn = new WebSocket("ws://192.168.43.138:8080");


// For First time
function init() {
  conn.send("OpenChat initiated..!");
}

conn.onopen = function() {
  console.log("Connection established!");
  init();
};

// SideBar Load Request
function sidebarRequest() {
  conn.send("Load Sidebar");
}

// Create Sidebar
function createSidebarElement(data, eleId)
{
  // organising content according to time
  $("#"+eleId).html("");
  var condition = data.length;
  for (var i = 0; i < condition; i++)
  {

    var div1 = $("<div></div>").addClass("row sideBar-body");

    div1.attr({
      "id" : data[i].username
    });

    var div2 = $("<div></div>").addClass("col-sm-3 col-xs-3 sideBar-avatar");
    var div3 = $("<div></div>").addClass("avatar-icon");
    var imgElement = $("<img>").attr({
      "src": "../public/assests/img/bg.png"
    });
    div3.append(imgElement);
    div2.append(div3);
    div1.append(div2);

    div2 = $("<div></div>").addClass("col-sm-9 col-xs-9 sideBar-main");
    div3 = $("<div></div>").addClass("row");
    var div4 = $("<div></div>").addClass("col-sm-8 col-xs-8 sideBar-name");
    var spanE = $("<span></span>").addClass("name-meta").text(data[i].name);
    div4.append(spanE);
    div3.append(div4);

    div4 = $("<div></div>").addClass("col-sm-4 col-xs-4 pull-right sideBar-time");
    spanE = $("<span></span>").addClass("time-meta pull-right").text(data[i].time);
     div4.append(spanE);
    div3.append(div4);
    div2.append(div3);

    div1.append(div2);
    $("#"+eleId).append(div1);

    if(eleId === "message" && $("#text_reply").attr("name") == data[i].login_id)
    {
      $("#"+data[i].username).addClass("active");
    }
  }
}


// For updating Sidebar
function SideBar(msg) {
  // Getting Div
  if (msg != null) {
    createSidebarElement(msg, "message");
  }
}

function toConversation() {
  $(".side").addClass("hide");
  $(".message").addClass("show");
  $(".lowerBar").addClass("show");
  $(".reply-emojis").addClass("hide");
  $(".reply-recording").addClass("hide");
}

function toSidebar() {
  $(".side").removeClass("hide");
  $(".message").removeClass("show");
  $(".lowerBar").removeClass("show");
  $(".reply-emojis").removeClass("hide");
  $(".reply-recording").removeClass("hide");
}

function width() {
  if (window.innerWidth < 500) {
    return true;
  }
  return false;
}

// Creating new Conversation or Loading Conversation
function newConversation(element, load)
{

  if(width())
  {
    toConversation();
  }

  var msg = {
    "username": element.id,
    "load": load,
    "newConversation": "Initiated"
  };
  conn.send(JSON.stringify(msg));
}

// Set Details
function setConversationDetails(details)
{
  $(".heading-name-meta").text(details.name);
  $(".heading-name-meta").attr({
    "href": location.href.substring(0, location.href.lastIndexOf("/") + 1) + "account.php/" + details.username
  });

  $(".heading-online").removeClass("show");
  if (details.login_status === "1") {
    $(".heading-online").addClass("show");
  }

  $("#text_reply").attr({
    "name": details.id
  });
}


// Update Current Conversation
function updateConversation(data)
{

  var ele = document.getElementById("conversation");
  ele.innerHTML = "";

  if (data[0].type === 1) {
    // For showing previous message
    if (data[0].load > 10)
    {
      var divE1 = $("<div></div>").addClass("row message-previous");
      var divE2 = $("<div></div>").addClass("col-sm-12 previous");
      var aElement = $("<a></a>").text("Show Previous Message!");
      aElement.attr({
        "id": data[0].username,
        "name": data[0].load
      });
      divE2.append(aElement);
      divE1.append(divE2);
      $("#conversation").append(divE1);
    }

    for (var i = data.length - 1; i >= 1; i--) {
      // create element
      var divElement1 = $("<div></div>").addClass("row message-body");
      var divElement2 = $("<div></div>").addClass("col-sm-12");
      var divElement3 = $("<div></div>");
      var messageText = $("<div></div>").addClass("message-text").text(data[i].message);
      var spanElement = $("<span></span>").addClass("message-time pull-right").text(data[i].time);

      if (data[i]["sent_by"] !== data[i].start)
      {
       divElement2.addClass("message-main-receiver");
       divElement3.addClass("receiver");
      }
      else
      {
        divElement2.addClass("message-main-sender");
       divElement3.addClass("sender");
      }
      divElement3.append(messageText);
      divElement3.append(spanElement);
      divElement2.append(divElement3);
      divElement1.append(divElement2);
      $("#conversation").append(divElement1);
    }

    setConversationDetails(data[0]);

    ele.scrollTop = ele.scrollHeight;
  } else {
    setConversationDetails(data[0]);
  }
}

// For reply to other messages
function reply() {

  var message = [$("#text_reply").val()];
  var id = $("#text_reply").attr("name");
  $("#text_reply").val("");
  // console.log(message);
  var q = {
    "name": id,
    "reply": message
  };
  conn.send(JSON.stringify(q));
}

function notFound(eleId)
{
  eleId = "#"+eleId;
  $(eleId).text("");
  var divElement = $("<div></div>").addClass("notFound").text("Not Found");
  $(eleId).append(divElement);
}

function composeChoose() {
  var text = document.getElementById("composeText").value;
  if (text !== "")
  {
    var msg =
    {
      "value": text,
      "Compose": "Compose"
    };
    conn.send(JSON.stringify(msg));
  }
  else
  {
    $("#compose").html("");
  }
}

//compose messages
function composeResult(arr)
{
  var ele = document.getElementById("compose");
  ele.innerHTML = "";

  if (arr !== "Not Found")
  {
    createSidebarElement(arr, "compose");
  }
  else
  {
    notFound("compose");
  }
}

function searchChoose() {
  var text = $("#searchText").val();
  if (text !== "") {
    var msg = {
      "value": text,
      "search": "search"
    };

    conn.send(JSON.stringify(msg));
  }
  else
  {
    sidebarRequest();
  }

}

function searchResult(arr) {
  if (arr !== "Not Found") {
    createSidebarElement(arr, "message");
  }
  else
  {
    notFound("message");
  }

}

// Load previous messages
function previous(element)
{
  var user = element.id;
  var lo = element.name;
  newConversation(element, lo);
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


// On Message
conn.onmessage = function(e)
{
  var msg = JSON.parse(e.data);
  // console.log(msg);
  if (!width())
  {
    SideBar(msg.sidebar);
  } else
  {
    if (!$(".side").hasClass("hide")) {
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

// Event Listeners
$(document).ready(function(){
  $("body").on("click", ".sideBar-body", function() {
      newConversation(this,10);
  });

  $("body").on("click", ".reply-send",
   function() {
    reply();
  });

  $("body").on("click", ".reply-recording",
   function() {
    startDictation();
  });

  $("body").on("click", ".lowerBar-recording",
   function() {
    startDictation();
  });

  $("body").on("click", ".lowerBar-back",
   function() {
    toSidebar();
    sidebarRequest();
  });

  $("body").on("click", ".previous a",
   function() {
    previous(this);
  });

  $("body").on("keyup", "#searchText",
   function() {
    searchChoose();
  });

  $("body").on("keyup", "#composeText",
   function() {
    composeChoose();
  });

  $(".heading-compose").click(function() {
    $(".side-two").css({
      "left": "0"
    });
  });

  $(".newMessage-back").click(function() {
    $(".side-two").css({
      "left": "-100%"
    });
  });

});

console.log("Hello, Contact me at ankitjain28may77@gmail.com");