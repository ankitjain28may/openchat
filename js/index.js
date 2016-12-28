var store='';
var recursive;
var last_time=''  ;
var flag=0;
var pre='';
var ch;

var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
        init(0);
    };

    conn.onmessage = function(e) {
        var msg = JSON.parse(e.data);
        console.log(msg);
        if(!width())
          SideBar(msg['sidebar'][0]);
        updateConversation(msg['conversation']);
    };

    conn.onerror = function(evt){
     console.log(evt.data);
   };

// For updating sidebar and load conversation for first time
function init(index)
{
  mobile("sidebar");
  var q={"last_time":last_time};
  q="q="+JSON.stringify(q);
  // Getting Div
  var ele=document.getElementById("message");
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      // Response From change.php
      var arr=JSON.parse(xmlhttp.responseText);
      // console.log(arr);
      if(arr!=null)
      {
        if (last_time!=arr[arr.length-1]['last_time'])
        {
          last_time=arr[arr.length-1]['last_time'];
          $("#message a").remove();
          // organising content according to time
          for (var i = 0; i < arr.length-1; i++)
          {
            var para=document.createElement("a");
            var node=document.createTextNode(arr[i]['name']);
            para.appendChild(node);
            para.setAttribute('id',arr[i]['username']);
            para.setAttribute('href','message.php#'+arr[i]['username']);
            para.setAttribute('class','message');
            para.setAttribute('onclick','chat(this,10)');
            ele.appendChild(para);

            var bre=document.createElement("span");
            var inp=document.createTextNode(arr[i]['time']);
            bre.appendChild(inp);
            bre.setAttribute('class','message_time');
            para.appendChild(bre);

            if(arr[i]['login_status']=='1')
            {
              var online = document.createElement("div");
              online.setAttribute('class','online');
              para.appendChild(online);
            }


          }

          // Load messgage for the first conversation
          if(index==0 && !width())
          {
            // console.log(1);
            chat(document.getElementById(arr[0].username),10);
          }
        }
      }
  start();

    }
  };
  xmlhttp.open("POST", "ajax/change.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(q);
}


function SideBar(msg)
{
  mobile("sidebar");

  // Getting Div
  var ele=document.getElementById("message");
  if(msg!=null)
  {
    $("#message a").remove();
    // organising content according to time
    for (var i = 0; i < msg.length; i++)
    {
      var para=document.createElement("a");
      var node=document.createTextNode(msg[i]['name']);
      para.appendChild(node);
      para.setAttribute('id',msg[i]['username']);
      para.setAttribute('href','message.php#'+msg[i]['username']);
      para.setAttribute('class','message');
      para.setAttribute('onclick','chat(this,10)');
      ele.appendChild(para);

      var bre=document.createElement("span");
      var inp=document.createTextNode(msg[i]['time']);
      bre.appendChild(inp);
      bre.setAttribute('class','message_time');
      para.appendChild(bre);

      if(msg[i]['login_status']=='1')
      {
        var online = document.createElement("div");
        online.setAttribute('class','online');
        para.appendChild(online);
      }
    }
  }
}

function updateConversation(arr)
{
    var ele=document.getElementById("conversation");

            if (arr[arr.length-1]==1)
          {
            ele.innerHTML="";

            // For showing previous message
            if(arr[arr.length-2].load>10)
            {
              var txt=$("<a></a>").text("Show Previous Message!");
              var te=$("<div></div>").append(txt);
              $("#conversation").append(te);
              $("#conversation div").addClass("previous");
              $("#conversation div a").attr({"onclick":"previous(this)","id":arr[0].username,"name":arr[arr.length-2].load});
            }

            for (var i = arr.length -3; i >= 0; i--)
            {
              // create element
              var para=document.createElement("div");
              if(arr[i]['sent_by']!=arr[i]['start'])
                para.setAttribute('class','receiver');
              else
                para.setAttribute('class','sender');

              ele.appendChild(para);
              var bre=document.createElement("br");
              bre.setAttribute("style","clear:both;");
              ele.appendChild(bre);

              var info=document.createElement("p");
              var node=document.createTextNode(arr[i]['message']);
              info.appendChild(node);
              para.appendChild(info);

              var tt=document.createElement("h6");
              var inp=document.createTextNode(arr[i]['time']);
              tt.appendChild(inp);
              tt.setAttribute('class','message_time');
              info.appendChild(tt);
            }

            $("#chat_heading a").remove('a');
            var txt=$("<a></a>").text(arr[0].name);
            $("#chat_heading").append(txt);
            $("#chat_heading a").attr({"href":"http://localhost/openchat/account.php/"+arr[0].username});
            // Online
            if(arr[0]['login_status']=='1')
            {
              var online = document.createElement("p");
              online.setAttribute('class','online');
              $("#chat_heading a").append(online);
              $("#chat_heading a p").css({"float":'right'});
            }

            if(width())
              $(".text_icon #text_reply").attr({'name':arr[0]['identifier_message_number']});
            else
              $("#text_reply").attr({'name':arr[0]['identifier_message_number']});
            ele.scrollTop = ele.scrollHeight;
          }

}


// For loading conversation between two persons
function chat(element,num)
{
  mobile("main");
  $("#compose_selection").css("visibility","hidden");
  flag=0;
  $("#compose_name").val('');
  $("#search_item").val('');
  $('#compose_text').hide();


  repeat();
  function repeat()
  {


    var q={"username":element.id,"load":num,"store":store};
    q="q="+JSON.stringify(q);

    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");
    xmlhttp.onreadystatechange = function()
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
      {
        var arr=xmlhttp.responseText;
        arr=JSON.parse(arr);
        // console.log(arr);

        if(arr!=null && flag==0)
        {
          // Old User
          if (arr[arr.length-2]==1 && store!=arr[arr.length-1]['store'])
          {
            store=arr[arr.length-1]['store'];
            ele.innerHTML="";

            // For showing previous message
            if(arr[arr.length-3].load>10)
            {
              var txt=$("<a></a>").text("Show Previous Message!");
              var te=$("<div></div>").append(txt);
              $("#conversation").append(te);
              $("#conversation div").addClass("previous");
              $("#conversation div a").attr({"onclick":"previous(this)","id":arr[0].username,"name":arr[arr.length-3].load});
            }

            for (var i = arr.length -4; i >= 0; i--)
            {
              // create element
              var para=document.createElement("div");
              if(arr[i]['sent_by']!=arr[i]['start'])
                para.setAttribute('class','receiver');
              else
                para.setAttribute('class','sender');

              ele.appendChild(para);
              var bre=document.createElement("br");
              bre.setAttribute("style","clear:both;");
              ele.appendChild(bre);

              var info=document.createElement("p");
              var node=document.createTextNode(arr[i]['message']);
              info.appendChild(node);
              para.appendChild(info);

              var tt=document.createElement("h6");
              var inp=document.createTextNode(arr[i]['time']);
              tt.appendChild(inp);
              tt.setAttribute('class','message_time');
              info.appendChild(tt);
            }

            $("#chat_heading a").remove('a');
            var txt=$("<a></a>").text(arr[0].name);
            $("#chat_heading").append(txt);
            $("#chat_heading a").attr({"href":"http://localhost/openchat/account.php/"+arr[0].username});
            // Online
            if(arr[0]['login_status']=='1')
            {
              var online = document.createElement("p");
              online.setAttribute('class','online');
              $("#chat_heading a").append(online);
              $("#chat_heading a p").css({"float":'right'});
            }

            if(width())
              $(".text_icon #text_reply").attr({'name':arr[0]['identifier_message_number']});
            else
              $("#text_reply").attr({'name':arr[0]['identifier_message_number']});
            ele.scrollTop = ele.scrollHeight;
          }

          // New User
          else if(arr['new']==0)
          {
            ele.innerHTML="";
            $("#chat_heading a").remove('a');

            var txt=$("<a></a>").text(arr.name);
            $("#chat_heading").append(txt);
            $("#chat_heading a").attr({"href":"http://localhost/openchat/account.php/"+arr.username});

            if(arr['login_status']=='1')
            {
              var online = document.createElement("p");
              online.setAttribute('class','online');
              $("#chat_heading a").append(online);
              $("#chat_heading a p").css({"float":'right'});
            }

            if(width())
              $(".text_icon #text_reply").attr({'name':arr['identifier_message_number']});
            else
              $("#text_reply").attr({'name':arr['identifier_message_number']});
          }


        }
      }
    };
    xmlhttp.open("POST", "ajax/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(q);
  }
}


// For reply to other messages

function reply()
{
  if(width())
    var re=".text_icon #text_reply";
  else
    var re="#text_reply";

  var ele=[$(re).val()];
  var id=$(re).attr("name");
  $(re).val('');
  // console.log(ele);
  var p='';
  var q={"name":id,"reply":ele};
  q=JSON.stringify(q);
  // console.log(q);
  conn.send(q);

}



// Compose new and direct message to anyone

function compose()
{
  mobile('compose');
  flag=1;
  $("#chat_heading a").remove('a');
  document.getElementById("conversation").innerHTML="";
  $('#compose_text').show();
}



//compose messages

function compose_message()
{
  var q=document.getElementById("compose_name").value;
  // console.log(q);
  var ele=document.getElementById("suggestion");
  ele.innerHTML="";
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      arr=xmlhttp.responseText;
      arr=JSON.parse(arr);
      // console.log(arr);

      if (arr!=[] && arr!="Not Found")
      {
        for (var i = arr.length - 1; i >= 0; i--)
        {
          var para=document.createElement("li");
          var active=document.createElement("a");
          var node=document.createTextNode(arr[i].name);
          active.appendChild(node);
          active.setAttribute("href","#");
          active.setAttribute("onclick","chat(this,10)");
          active.setAttribute("class","suggestion");
          active.setAttribute("id",arr[i].username);
          para.appendChild(active);
          ele.appendChild(para);
        }
      }
      else if(arr=="Not Found")
      {
        var txt=$("<a></a>").text('Not Found');
        var l=$("<li></li>").append(txt);
        $("#suggestion").append(l);
        $("#suggestion li a").attr({"onclick":"myFunction()"});

      }
    }
    $("#compose_selection").css("visibility","visible");
  };
  if(q!="")
  {
    xmlhttp.open("GET", "ajax/suggestion.php?q=" + q, true);
    xmlhttp.send();
  }
  else
    $("#compose_selection").css("visibility","hidden");  //for hidding the suggestion

}



// For Search

function search_choose()
{
  // console.log(1);
  var q=$("#search_item").val();
  var ele=document.getElementById("message");

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      arr=JSON.parse(xmlhttp.responseText);                                 // Response From change.php
      // console.log(arr);
      if($("#search_item").val()=='')
      {
        last_time='';
        init(1);
      }
      if (arr!=null)
      {
        ele.innerHTML="";
        for (var i = arr.length - 1; i >= 0; i--)                         // organising content according to time
        {
          var para=document.createElement("a");                             //creating element a
          var node=document.createTextNode(arr[i]['name']);
          para.appendChild(node);
          para.setAttribute('id',arr[i]['username']);
          para.setAttribute('href','message.php#'+arr[i]['username']);
          para.setAttribute('class','message');
          para.setAttribute('onclick','chat(this,10)');
          ele.appendChild(para);

          var bre=document.createElement("span");                                 // creating element span for showing time
          var inp=document.createTextNode(arr[i]['time']);
          bre.appendChild(inp);
          bre.setAttribute('class','message_time');
          para.appendChild(bre);
        }
      }
      else
      {
        $("#message").text('');
        // console.log("None");
        var txt=$("<a></a>").text("Not Found");
        $("#message").append(txt);
        $("#message a").addClass('message');
      }

    }
  };
  xmlhttp.open("GET", "ajax/search_item.php?q=" + q, true);
  xmlhttp.send();
}


window.ondblclick=myFunction;

function myFunction()                                                     // Hidden compose message input
{
  $("#compose_selection").css("visibility","hidden");
  last_time='';
  init(1);
  flag=0;
  store='';
  $("#compose_name").val('');
  $("#search_item").val('');
  $('#compose_text').hide();
}


function previous(element)                                                // Load previous messages
{
  mobile("previous");
  var user=element.id;
  var lo=element.name;
  store='';
  chat(element,lo);
}

function mobile(ele)
{
  if(width())
  {
    mob_hide();
    if(ele=="main")
    {
      store='';
      $(".sidebar").hide();
      $(".mob-reply").show();
      $('.chat_name').show();
      $(".chat_name #chat_heading").show();
      if(pre=='')
      {
        $(".main div").remove('div');
        $(".main br").remove('br');
        $(".chat_name #chat_heading a").remove('a');
      }
      $(".main").show();
    }
    if(ele=="compose")
    {
      $(".chat_name").show();
      $(".chat_name .compose_text").show();
      $(".sidebar").hide();
      $("#compose_selection").show();
    }
    if(ele=="sidebar")
    {
      $(".sidebar").show();
    }
    if(ele=="previous")
      pre='1';
    else
      pre='';
  }
}
function show_search()
{
  // console.log("HE0");
  mob_hide();
  // $(".sidebar a").remove('a');
  $(".search_message").show();
  $(".sidebar").show();
  // $('.sidebar').hide();
}

function mob_hide()
{
  $(".search_message").hide();
  $(".sidebar").hide();
  $(".main").hide();
  $(".chat_name").hide();
  $(".mob-reply").hide();
  stop();
  stop_it();
}

function stop()
{
  clearInterval(recursive);
  // console.log("recursive");
}

function start()
{
  if(width())
  {
    // console.log("Hello");
    ch=setInterval(update,1500);
    stop();
  }
}

function stop_it()
{
  if(width())
  {
    clearInterval(ch);
  }
}

function width()
{
  if(window.innerWidth<500)
    return true;
}

function update()
{
  // console.log("H");
  init(1);
}

// Audio Recognization

function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-IN";
      recognition.start();

      recognition.onresult = function(e) {
        document.getElementById('text_reply').value
                                 = e.results[0][0].transcript;
        recognition.stop();
        reply();
      };

      recognition.onerror = function(e) {
        recognition.stop();
      }

    }
  }

console.log("Hello, Contact me at ankitjain28may77@gmail.com");