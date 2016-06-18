function init()
 {
  //Ajax to send data
    // var name=response.name;000
    // var arr=JSON.stringify(response);
    var p=''
    var arr;
    var q="total_messages";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         arr=JSON.parse(xmlhttp.responseText);
        if (arr!=null) {
          
        };
      }
    };
    xmlhttp.open("GET", "change.php?q=" + q, true);
    xmlhttp.send(); 

}