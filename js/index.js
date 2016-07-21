var store='';
var recursive;
var last_time=''  ;
var flag=0;




// For updating sidebar and load conversation for first time

function init(index)  
{
  var q="q=total_messages";
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function()                                     // Ajax Call
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
    {
      var arr=JSON.parse(xmlhttp.responseText);                                // Response From change.php
      // console.log(arr); 
      if(arr!=null)         
      {                 
        var a=arr[arr.length-1].time;
        var b=arr[arr.length-1].username;
        var c=a+b;
        if (last_time!=c) 
        {
          last_time=c;
          $("#message a").remove();

          for (var i = arr.length - 1; i >= 0; i--)                              // organising content according to time
          {
            var sp=$("<span></span>").text(arr[i]['time']);                      // creating element span
            sp.addClass('message_time');
            var para=$("<a></a>").text(arr[i]['name']);                          //creating element a
             para.append(sp);
            $("#message").append(para);                             
            para.attr({'id':arr[i]['username'],'href':'message.php#'+arr[i]['username'],'class':'message','onclick':'chat(this,10)'});
          }

          if(index==0)
            chat(document.getElementById(arr[arr.length-1].username),10);         // Load messgage for the first conversation
        }
      }
    }
  };
  xmlhttp.open("POST", "ajax/change.php", true);                                // ajax request post
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(q); 
}




// For loading conversation between two persons

function chat(element,num)   
{
  // console.log(num);
  $("#compose_selection").css("visibility","hidden");
  last_time='';
  flag=0;
  $("#compose_name").val('');
  $("#search_item").val('');
  $('#compose_text').hide();

  stop();                                                                  // stopping previous setinterval call
  
  recursive =setInterval(repeat,1500);                                     // refresh conversation
  function repeat() 
  {
    // console.log(element);
    init(1);

    var q={"username":element.id,"load":num};
    q="q="+JSON.stringify(q);

    // console.log(q);

    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");                                  // ajax call
    xmlhttp.onreadystatechange = function() 
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
      {
        var arr=xmlhttp.responseText;
        arr=JSON.parse(arr);
        // console.log(arr);

        if(arr!=null && flag==0)
        {
          // console.log(1);
          if (arr[arr.length-1]==1)                                                  // Old User
          {
            var a=arr[0].id;
            if(store!=a)
            {
              store=a;
              // console.log(1);
              ele.innerHTML="";

              if(arr[arr.length-2].load>10)                                     // For showing previous message
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
                 para.setAttribute('class','sender');
                else
                  para.setAttribute('class','receiver');

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
              $("#text_reply").attr({'name':arr[0]['identifier_message_number']});
              ele.scrollTop = ele.scrollHeight;
            }
          }
          else if(arr['new']==0)                              // New User
          {
            ele.innerHTML="";
            $("#chat_heading a").remove('a');
            var txt=$("<a></a>").text(arr.name);
            $("#chat_heading").append(txt);
            $("#chat_heading a").attr({"href":"http://localhost/openchat/account.php/"+arr.username});
            $("#text_reply").attr({'name':arr['identifier_message_number']});
          } 
        }  
      }
    };
    xmlhttp.open("POST", "ajax/chat.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(q); 
  }
  function stop()
  {
      clearInterval(recursive);
      // console.log("recursive");
  }
}




// For reply to other messages

function reply()
{
  var ele=[document.getElementById("text_reply").value];
  var id=document.getElementById("text_reply").name;

  // console.log(ele);
  var p='';
  var q={"name":id,"reply":ele};
  q="q="+JSON.stringify(q);
  // console.log(q);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      arr=xmlhttp.responseText;
        // console.log(arr);
      if (arr=="Messages is sent")                                        // Message is sent
      {
        document.getElementById("text_reply").value="";
      }
      else{

      }
    }    
  };
  xmlhttp.open("POST", "ajax/reply.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(q); 
}



// Compose new and direct message to anyone

function compose() 
{
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
          last_time='';
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
          para.setAttribute('onclick','chat(this)');
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
  flag=0;
  store='';
  $("#compose_name").val('');
  $("#search_item").val('');
  $('#compose_text').hide();
}


function previous(element)                                                // Load previous messages
{
  var user=element.id;
  var lo=element.name;
  chat(element,lo);
  store='';
}

console.log("Hello, Contact me at ankitjain28may77@gmail.com");