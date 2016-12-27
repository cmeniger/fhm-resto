var hinclude;!function(){"use strict";hinclude={classprefix:"include_",set_content_async:function(e,t){4===t.readyState&&(200!==t.status&&304!==t.status||(e.innerHTML=t.responseText),hinclude.set_class(e,t.status),hinclude.trigger_event(e))},buffer:[],set_content_buffered:function(e,t){4===t.readyState&&(hinclude.buffer.push([e,t]),hinclude.outstanding-=1,0===hinclude.outstanding&&hinclude.show_buffered_content())},show_buffered_content:function(){for(var e;hinclude.buffer.length>0;)e=hinclude.buffer.pop(),200!==e[1].status&&304!==e[1].status||(e[0].innerHTML=e[1].responseText),hinclude.set_class(e[0],e[1].status),hinclude.trigger_event(e[0])},outstanding:0,includes:[],run:function(){var e,t=0,n=this.get_meta("include_mode","buffered");if(this.includes=document.getElementsByTagName("hx:include"),0===this.includes.length&&(this.includes=document.getElementsByTagName("include")),"async"===n)e=this.set_content_async;else if("buffered"===n){e=this.set_content_buffered;var i=1e3*this.get_meta("include_timeout",2.5);setTimeout(hinclude.show_buffered_content,i)}for(t;t<this.includes.length;t+=1)this.include(this.includes[t],this.includes[t].getAttribute("src"),this.includes[t].getAttribute("media"),e)},include:function(e,t,n,i){if(!n||!window.matchMedia||window.matchMedia(n).matches){var d=t.substring(0,t.indexOf(":"));if("data"===d.toLowerCase()){var s=decodeURIComponent(t.substring(t.indexOf(",")+1,t.length));e.innerHTML=s}else{var c=!1;if(window.XMLHttpRequest)try{c=new XMLHttpRequest,e.hasAttribute("data-with-credentials")&&(c.withCredentials=!0)}catch(u){c=!1}else if(window.ActiveXObject)try{c=new ActiveXObject("Microsoft.XMLHTTP")}catch(a){c=!1}if(c){this.outstanding+=1,c.onreadystatechange=function(){i(e,c)};try{c.open("GET",t,!0),c.send("")}catch(o){this.outstanding-=1,alert("Include error: "+t+" ("+o+")")}}}}},refresh:function(e){var t,n=0;for(t=this.set_content_buffered,n;n<this.includes.length;n+=1)this.includes[n].getAttribute("id")===e&&this.include(this.includes[n],this.includes[n].getAttribute("src"),this.includes[n].getAttribute("media"),t)},get_meta:function(e,t){var n,i=0,d=document.getElementsByTagName("meta");for(i;i<d.length;i+=1)if(n=d[i].getAttribute("name"),n===e)return d[i].getAttribute("content");return t},addDOMLoadEvent:function(e){if(!window.__load_events){var t=function(){var e=0;if(!hinclude.addDOMLoadEvent.done){for(hinclude.addDOMLoadEvent.done=!0,window.__load_timer&&(clearInterval(window.__load_timer),window.__load_timer=null),e;e<window.__load_events.length;e+=1)window.__load_events[e]();window.__load_events=null}};document.addEventListener&&document.addEventListener("DOMContentLoaded",t,!1),/WebKit/i.test(navigator.userAgent)&&(window.__load_timer=setInterval(function(){/loaded|complete/.test(document.readyState)&&t()},10)),window.onload=t,window.__load_events=[]}window.__load_events.push(e)},trigger_event:function(e){var t;document.createEvent?(t=document.createEvent("HTMLEvents"),t.initEvent("hinclude",!0,!0),t.eventName="hinclude",e.dispatchEvent(t)):document.createEventObject&&(t=document.createEventObject(),t.eventType="hinclude",t.eventName="hinclude",e.fireEvent("on"+t.eventType,t))},set_class:function(e,t){var n=e.className.split(/\s+/),i=n.filter(function(e){return!e.match(/^include_\d+$/i)&&!e.match(/^included/i)}).join(" ");e.className=i+(i?" ":"")+"included "+hinclude.classprefix+t}},hinclude.addDOMLoadEvent(function(){hinclude.run()})}();