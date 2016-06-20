function init()
{
  //Ajax to send data
    console.log("1");
    var p='';
    var arr;
    var q="total_messages";
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("message");
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=JSON.parse(xmlhttp.responseText);
        if (arr!=null) {
          console.log(arr);
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
        };
      }
    };
    xmlhttp.open("GET", "change.php?q=" + q, true);
    xmlhttp.send(); 
    document.getElementById("conversation").innerHTML="";
}

function chat(element)
{
  console.log(element);
    var p='';
    var arr;
    var q=element.id;
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");
    document.getElementById("chat_heading").innerHTML=element.innerHTML;
    ele.innerHTML="";
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=xmlhttp.responseText;
        if (arr!='[]') {
          // console.log(arr);
          arr=JSON.parse(arr);
          console.log(arr);
          for (var i = arr.length - 1; i >= 0; i--) {
            var para=document.createElement("p");
            var node=document.createTextNode(arr[i]['message']);
            para.appendChild(node);
            // para.setAttribute('id',arr[i]['username']);
            // para.setAttribute('href','message.php/?user='+arr[i]['username']);
            if(arr[i]['sent_by']!=arr[i]['start'])
              para.setAttribute('class','sender');
            else
              para.setAttribute('class','receiver');
            // para.setAttribute('onclick','chat("'+arr[i]['username']+'")');
            ele.appendChild(para);
            var bre=document.createElement("br");
            bre.setAttribute("style","clear:both;");
            ele.appendChild(bre);

            var tt=document.createElement("sub");
            var inp=document.createTextNode(arr[i]['time']);
            tt.appendChild(inp);
            tt.setAttribute('class','message_time');
            para.appendChild(tt);


          };
        }
        else
        {
          ele.innerHTML="No Message";
        }
      }
    };
    xmlhttp.open("GET", "chat.php?q=" + q, true);
    xmlhttp.send(); 
}