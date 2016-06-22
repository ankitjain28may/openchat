var store;
var recursive;
var sidebar_store;
// For updating sidebar and load conversation for first time

function init(index)  
{
  var arr;
  var q="total_messages";

  var ele=document.getElementById("message");  // Getting Div

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() // Ajax Call
  {
  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
  {
    arr=JSON.parse(xmlhttp.responseText);  // Response From change.php
    // console.log(arr);

    if (arr!=null) 
    {
      ele.innerHTML="";
      for (var i = arr.length - 1; i >= 0; i--) // organising content according to time
      {
        var para=document.createElement("a");                 //creating element a
        var node=document.createTextNode(arr[i]['name']);
        para.appendChild(node);
        para.setAttribute('id',arr[i]['username']);
        para.setAttribute('href','message.php#'+arr[i]['username']);
        para.setAttribute('class','message');
        para.setAttribute('onclick','chat(this)');
        ele.appendChild(para);

        var bre=document.createElement("span");               // creating element span for showing time
        var inp=document.createTextNode(arr[i]['time']);
        bre.appendChild(inp);
        bre.setAttribute('class','message_time');
        para.appendChild(bre);
      };
      if(index==0)
        chat(document.getElementById(arr[arr.length-1].username));  // Load messgage for the first conversation
      };
    }
  };
  xmlhttp.open("GET", "change.php?q=" + q, true);  // ajax request
  xmlhttp.send(); 
}

// For loading conversation between two persons

function chat(element)   
{
  document.getElementById("compose_selection").style="visibility:hidden";  //for hidding the suggestion
  // console.log((location.href).endsWith('.php'));

  stop(); // stopping previous setinterval call
  
  document.getElementById("compose_text").style="display:none;";
  recursive =setInterval(repeat,1500);  // refresh conversation
  function repeat() 
  {
    // console.log(element);
    init(1);

    var p='';
    var arr;
    var q=element.id;
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");
    xmlhttp.onreadystatechange = function() 
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
      {
        arr=xmlhttp.responseText;
        arr=JSON.parse(arr);
        // console.log(arr);
          
        if (arr!='[]' && arr[arr.length-1]==1) 
        {
          if(store!=arr[0].id)
          {
            // console.log(1);
            document.getElementById("text_reply").name="";
            ele.innerHTML="";
            // console.log(arr.length-1);
            for (var i = arr.length -2; i >= 0; i--) 
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

            };
            document.getElementById("chat_heading").innerHTML=arr[0].name;
            document.getElementById("text_reply").name=arr[0]['identifier_message_number'];
            store=arr[0].id;
            ele.scrollTop = ele.scrollHeight;
          }
        }
        else if(arr['new']==0)
        {
          document.getElementById("chat_heading").innerHTML=arr.name;
          document.getElementById("text_reply").name=arr['identifier_message_number'];
          ele.innerHTML="";
          flag="compose";
        }   
      }
    };
    xmlhttp.open("GET", "chat.php?q=" + q, true);
    xmlhttp.send(); 
  }
  function stop()
  {
      clearInterval(recursive);
      console.log("recursive");
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
  q=JSON.stringify(q);
  console.log(q);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      arr=xmlhttp.responseText;
        // console.log(arr);
      if (arr=="Messages is sent") 
      {
        document.getElementById("text_reply").value="";
      }
      else{

      }
    }    
  };
  xmlhttp.open("GET", "reply.php?q=" + q, true);
  xmlhttp.send(); 
}

// Compose new and direct message to anyone

function compose() 
{
  document.getElementById("chat_heading").innerHTML="";
  document.getElementById("conversation").innerHTML="";
  document.getElementById("compose_text").style="display:block;";
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
          active.setAttribute("onclick","chat(this)");
          active.setAttribute("class","suggestion");
          active.setAttribute("id",arr[i].username);
          para.appendChild(active);
          ele.appendChild(para);
        };
      }
      else if(arr=="Not Found")
      {
        var para=document.createElement("li");
        var node=document.createTextNode(arr);
        para.appendChild(node);
        ele.appendChild(para);
      }
    }
    document.getElementById("compose_selection").style="visibility:visible"; 
  };
  if(q!="")
  {
    xmlhttp.open("GET", "suggestion.php?q=" + q, true);
    xmlhttp.send();
  }
  else
    document.getElementById("compose_selection").style="visibility:hidden";  //for hidding the suggestion

}

// For search 

function suggestion_choose(element)
{
  document.getElementById("suggestion").innerHTML="";
  var para=element;
  console.log(para);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() 
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      arr=xmlhttp.responseText;
        // console.log(arr);
      
      if (arr=="Messages is sent") 
      {
          document.getElementById("text_reply").value="";
      }
      else{

      }
    }  
  };
  xmlhttp.open("GET", "reply.php?q=" + q, true);
  xmlhttp.send(); 
}