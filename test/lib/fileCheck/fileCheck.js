/**
 * check and show status (size) of (log) files, allow upload and emptying of the files
 *
 * allow upload (review)       (read rights)
 *       emptying of the files (opt. wites rights)
 *
 * @package    fileCheck
 * @copyright  Copyright (c) 2015 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @author     Kjell-Inge Gustafsson <ical@kigkonsult.se>
 * @link       http://kigkonsult.se/defs/index.php
 * @license    non-commercial use: Creative Commons
 *             Attribution-NonCommercial-NoDerivatives 4.0 International License
 *             (http://creativecommons.org/licenses/by-nc-nd/4.0/)
 * @version    1.0
 *
 *  fileCheck.js
 */
function fCcreateXHR() {
 try { return new XMLHttpRequest(); }                    catch(e) {}
 try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch(e) {}
 try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch(e) {}
 try { return new ActiveXObject("Msxml2.XMLHTTP"); }     catch(e) {}
 try { return new ActiveXObject("Microsoft.XMLHTTP"); }  catch(e) {}
 alert("XMLHttpRequest not supported");
 return null;
}
function fCfixButton(r,data) {
  var t2=document.getElementById('fCrBox'),td4Id='fcEmpty'+r,td4=document.getElementById(td4Id),b2;
  if(('0' < data.writeable) && ('0' < data.size)) {
    if(0 == td4.childNodes.length) {
      b2  = document.createElement('button');
      b2.innerHTML = txtEmpty;
      b2.setAttribute('onclick','fCsendRequest(\'' + r + '\');');
      b2.setAttribute('title',txtEmptyTitle+data.file);
      td4.appendChild(b2);    // add empty file button
    }
  }
  else {
    if(0 < td4.childNodes.length)
      td4.removeChild(td4.childNodes[0]);
    td4.innerHTML = '&nbsp;';
  }
}
function fChandleResponse(data) {
  var t2=document.getElementById('fCrBox'),
      r,tr,td1,td2,td3,b1,td4,b2,rl=t2.rows.length,w='30px',bl,blc;
  for(r=0; r<data.length; r++) {
// alert('file='+data[r].file+', size='+data[r].size+', writeable='+data[r].writeable); // test ###
    if(0 < rl) {
      t2.rows[r].cells[1].innerHTML = data[r].size;
      fCfixButton(r,data[r])
      continue;
    }
    tr  = t2.insertRow(r);
    td1 = tr.insertCell(0);
    td1.innerHTML = data[r].file;  // show file name
    td2 = tr.insertCell(1);
    td2.style.textAlign = 'right';
    td2.setAttribute('width','40px');
    td2.innerHTML = data[r].size;  // show file size
    td3 = tr.insertCell(2);
    td3.setAttribute('width',w);
    b1  = document.createElement('button');
    b1.setAttribute('onclick','fCuplRequest(\'' + r + '\');');
    b1.setAttribute('title',txtUplTitle+data[r].file);
    b1.innerHTML = txtUpl;
    td3.appendChild(b1);           // upload button
    td4 = tr.insertCell(3);
    td4.setAttribute('id','fcEmpty'+r);
    td4.setAttribute('width',w);
    fCfixButton(r,data[r])
    if((data.length-r)>1) {
      td1.style.borderBottom = '1px solid '+fCBox.style.borderColor;
      td2.style.borderBottom = '1px solid '+fCBox.style.borderColor;
      td3.style.borderBottom = '1px solid '+fCBox.style.borderColor;
      td4.style.borderBottom = '1px solid '+fCBox.style.borderColor;
    }
  }
}
function fCsendRequest(itemNo) {
  var xhr, url2 = url + '?fc=';
  url2 += (typeof(itemNo)=='undefined') ? 'status' : (parseInt(itemNo,10) + 1);
  xhr = fCcreateXHR();
  if (xhr) {
    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4  && xhr.status == 200) {
          fChandleResponse(JSON.parse(xhr.responseText));
        }
      };
    xhr.open("GET", url2, true);
    xhr.send(null);
  }
}
function fCtoogle() {
  var fCrBox = document.getElementById('fCrBox');
  fCsendRequest();
  if('block'== fCrBox.style.display)
    fCrBox.style.display = 'none';
  else {
    fCrBox.style.display = 'block';
    fCrBox.style.fontFamily = document.getElementById('fCBox').style.fontFamily;
  }
}
function fCuplRequest(itemNo) {
  itemNo = parseInt(itemNo,10) + 1;
  location.href = url + '?fc=upl' + itemNo;
}
/*
document.onreadystatechange = function() {
  if('complete' === document.readyState)
    fCsendRequest();
}
*/
