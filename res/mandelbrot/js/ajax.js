function createRequestObject() {

    var xmlHttp = false;
    // Mozilla, Opera, Safari sowie Internet Explorer 7
    if (typeof(XMLHttpRequest) != 'undefined') {
        xmlHttp = new XMLHttpRequest();
    }
    if (!xmlHttp) {
        // Internet Explorer 6 und älter
        try {
            xmlHttp  = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                xmlHttp  = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                xmlHttp  = false;
            }
        }
    }
    return xmlHttp;
}

var http = createRequestObject();

function sndReq() {

    var action = 'render';
    var startRe = document.getElementsByName("tx_chapfelm_pi1[startRe]")[0].value;
    var startIm = document.getElementsByName("tx_chapfelm_pi1[startIm]")[0].value;
    var endRe = document.getElementsByName("tx_chapfelm_pi1[endRe]")[0].value;
    var endIm = document.getElementsByName("tx_chapfelm_pi1[endIm]")[0].value;
    var preset = document.getElementsByName("tx_chapfelm_pi1[preset]")[0].selectedIndex;
    var color = document.getElementsByName("tx_chapfelm_pi1[color]")[0].selectedIndex;
    
    if (http) {
        http.open('GET', '###SNDREQ###&cmd='+action+'&startRe='+startRe+'&startIm='+startIm+'&endRe='+endRe+'&endIm='+endIm+'&preset='+preset+'&color='+color+'&eID=ch_apfelm',true);
        http.onreadystatechange = handleResponse;
        http.send(null);
    }
}

function zoom(action){

    var input_crop_x = document.getElementById("input_crop_x").value;
    var input_crop_y = document.getElementById("input_crop_y").value;
    var input_crop_width = document.getElementById("input_crop_width").value;
    var input_crop_height = document.getElementById("input_crop_height").value;
    
    var startRe = document.getElementsByName("tx_chapfelm_pi1[startRe]")[0].value;
    var startIm = document.getElementsByName("tx_chapfelm_pi1[startIm]")[0].value;
    var endRe = document.getElementsByName("tx_chapfelm_pi1[endRe]")[0].value;
    var endIm = document.getElementsByName("tx_chapfelm_pi1[endIm]")[0].value;
    var color = document.getElementsByName("tx_chapfelm_pi1[color]")[0].selectedIndex;
    
    if (http) {
        http.open('GET', '###SNDREQ###&cmd='+action+'&startRe='+startRe+'&startIm='+startIm+'&endRe='+endRe+'&endIm='+endIm+'&input_crop_x='+input_crop_x+'&input_crop_y='+input_crop_y+'&input_crop_width='+input_crop_width+'&input_crop_height='+input_crop_height+'&color='+color+'&eID=ch_apfelm',true);
        http.onreadystatechange = handleResponse;
        http.send(null);
    }
}

function handleResponse() {
    if(http.readyState == 4){
        var response = http.responseText;            
        var update = new Array();        
     
        if(response.indexOf('|' != -1)) {
            update = response.split('|');
            document.getElementById("input_image_ref").value = update[0];
            document.getElementById("imageContainer").innerHTML = '<img src="###TYPO3SITEURL###'+update[0]+'" onClick="getDetails(\'mouseLeft\');">';
            document.getElementsByName("tx_chapfelm_pi1[startRe]")[0].value = update[1];
            document.getElementsByName("tx_chapfelm_pi1[startIm]")[0].value = update[2];
            document.getElementsByName("tx_chapfelm_pi1[endRe]")[0].value = update[3];
            document.getElementsByName("tx_chapfelm_pi1[endIm]")[0].value = update[4];
       
            init_imageCrop();       
        }        
    }
}