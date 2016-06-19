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
            // para.setAttribute('id',arr[i]['username']);
            para.setAttribute('href','message.php#'+arr[i]['username']);
            para.setAttribute('class','message');
            para.setAttribute('onclick','chat(\''+arr[i]['username']+'\')');
            ele.appendChild(para);
            // var bre=document.createElement("br");
            // ele.appendChild(bre);
            // para.appendChild('<br>');
            // ele.appendChild('<br>');

          };
        };
      }
    };
    xmlhttp.open("GET", "change.php?q=" + q, true);
    xmlhttp.send(); 
    document.getElementById("conversation").innerHTML="";
}

function chat(username)
{
  console.log(username);
    var p='';
    var arr;
    var q=username;
    var xmlhttp = new XMLHttpRequest();
    var ele=document.getElementById("conversation");
    ele.innerHTML="";
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=xmlhttp.responseText;
        if (arr!='[]') {
          // console.log(arr);
          arr=JSON.parse(arr);
          console.log(arr);
          for (var i = arr.length - 1; i >= 0; i--) {
            // var para='<p id="'+arr[i]['username']+'">'+arr[i]['name']+'</p>';
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
            ele.appendChild(bre);
            // para.appendChild('<br>');
            // ele.appendChild('<br>');

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