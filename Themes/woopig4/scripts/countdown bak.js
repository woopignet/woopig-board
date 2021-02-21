// Andrew Urquhart : CountDown Timer : http://andrewu.co.uk/clj/countdown/
function CD_T(id,e){var n=new Date();CD_D(+n,id,e);setTimeout("if(typeof CD_T=='function'){CD_T('"+id+"',"+e+")}",1100-n.getMilliseconds())};function CD_D(n,id,e){var ms=e-n;if(ms<=0) ms*=-1;var d=Math.floor(ms/864E5);ms-=d*864E5;var h=Math.floor(ms/36E5);ms-=h*36E5;var m=Math.floor(ms/6E4);ms-=m*6E4;var s=Math.floor(ms/1E3);if(CD_OBJS[id]){CD_OBJS[id].innerHTML=d+" : ")+CD_ZP(h)+" : "+CD_ZP(m)+" : "+CD_ZP(s)}};function CD_ZP(i){return(i<10?"0"+i:i)};function CD_Init(){var pref="countdown";var objH=1;if(document.getElementById||document.all){for(var i=1;objH;++i){var id=pref+i;objH=document.getElementById?document.getElementById(id):document.all[id];if(objH&&(typeof objH.innerHTML)!='undefined'){var s=objH.innerHTML;var dt=CD_Parse(s);if(!isNaN(dt)){CD_OBJS[id]=objH;CD_T(id,dt.valueOf());if(objH.style){objH.style.visibility="visible"}}else {objH.innerHTML=s+"<a href=\"http://andrewu.co.uk/clj/countdown/\" title=\"Countdown Error:Invalid date format used,check documentation (see link)\">*</a>"}}}}};function CD_Parse(strDate){var objReDte=/(\d{4})\-(\d{1,2})\-(\d{1,2})\s+(\d{1,2}):(\d{1,2}):(\d{0,2})\s+GMT([+\-])(\d{1,2}):?(\d{1,2})?/;if(strDate.match(objReDte)){var d=new Date(0);d.setUTCFullYear(+RegExp.$1,+RegExp.$2-1,+RegExp.$3);d.setUTCHours(+RegExp.$4,+RegExp.$5,+RegExp.$6);var tzs=(RegExp.$7=="-"?-1:1);var tzh=+RegExp.$8;var tzm=+RegExp.$9;if(tzh){d.setUTCHours(d.getUTCHours()-tzh*tzs)}if(tzm){d.setUTCMinutes(d.getUTCMinutes()-tzm*tzs)};return d}else {return NaN}};var CD_OBJS=new Object();if(window.attachEvent){window.attachEvent('onload',CD_Init)}else if(window.addEventListener){window.addEventListener("load",CD_Init,false)}else {window.onload=CD_Init};
