function init()
{
  //Ajax to send data
  // var recursive =setInterval(repeatt,1000);
    // function repeatt() {
    // console.log("1");
    var p='';
    var arr;
    var q="total_messages";
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("message");
    ele.innerHTML="";
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=JSON.parse(xmlhttp.responseText);
        if (arr!=null) {
          // console.log(arr);
          for (var i = arr.length - 1; i >= 0; i--) {
            // var para='<p id="'+arr[i]['username']+'">'+arr[i]['name']+'</p>';
            var para=document.createElement("a");
            var node=document.createTextNode(arr[i]['name']);
            para.appendChild(node);
            para.setAttribute('id',arr[i]['username']);
            para.setAttribute('href','message.php#'+arr[i]['username']);
            para.setAttribute('class','message');
            // para.setAttribute('onclick','chat(\''+arr[i]['username']+'\')');
            para.setAttribute('onclick','chat(this)');
            ele.appendChild(para);
            var bre=document.createElement("span");
            var inp=document.createTextNode(arr[i]['time']);
            bre.appendChild(inp);
            bre.setAttribute('class','message_time');
            para.appendChild(bre);

          };
          // console.log(document.getElementById(arr[0].username));
          chat(document.getElementById(arr[arr.length-1].username));
        };
      }
    };
    xmlhttp.open("GET", "change.php?q=" + q, true);
    xmlhttp.send(); 
    document.getElementById("conversation").innerHTML="";
  }
// }

function chat(element)
{
  var store;
  var recursive =setInterval(repeat,1500);
  function repeat() 
  {
    // console.log(element);
    var p='';
    var arr;
    var q=element.id;
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=xmlhttp.responseText;
          arr=JSON.parse(arr);
          // console.log(store);
          
        if (arr!='[]' && store!=arr[0].id) 
        {
          document.getElementById("text_reply").name="";
          ele.innerHTML="";
          // console.log(arr);
          for (var i = arr.length - 1; i >= 0; i--) 
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
    };
    xmlhttp.open("GET", "chat.php?q=" + q, true);
    xmlhttp.send(); 
  }
}


function reply()
{
  var ele=[document.getElementById("text_reply").value];
  var id=document.getElementById("text_reply").name;

  // console.log(ele);
  var p='';
  var q={"name":id,"reply":ele};
  q=JSON.stringify(q);
  // console.log(q);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
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