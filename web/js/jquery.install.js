!function(t,n,i){function s(t){this.settings=t,this.process=this.settings.process,this.post="",this.init()}s.prototype={init:function(){this.initProcess()},initProcess:function(){var t=this;0===t.process.length?t.initEnd():(t.initStart(),t.initLogs())},initStart:function(){var n=this;t(n.settings.container_end).fadeOut(400,"linear",function(){t(n.settings.container_start).fadeIn(400,"linear",function(){n.next(0,!0)})})},initEnd:function(){var n=this;t(n.settings.container_start).fadeOut(400,"linear",function(){t(n.settings.container_end).fadeIn(400,"linear",function(){n.initTimer(t(n.settings.container_end+" .timer"))})})},initLogs:function(){var n=this;t(n.settings.container_logs).fadeIn(400,"linear",function(){})},initTimer:function(i){var s=i.attr("data-time"),e="timer-"+Math.floor(1e3*Math.random());i.append(" <span id='"+e+"'>"+s+"</span> s.");var a=setInterval(function(){s<=0?(clearInterval(a),n.location.href=i.attr("data-url")):t("#"+e).html(--s)},1e3)},initAjax:function(n,i){var s=this;t.ajax({type:"POST",url:s.settings.route+"/"+s.process[n],data:s.post,success:function(t){var n=!0;200!==t.status&&(n=!1),s.post=t.post,s.addLogs(t.logs),i(n)}})},next:function(t,n){var i=this;n&&"undefined"!=typeof i.process[t]?i.initAjax(t,function(n){i.percent(t),i.next(t+1,n)}):n&&i.initEnd()},addLogs:function(n){for(var i=this,s=0;s<n.length;s++)t(i.settings.container_logs).append("<div class='line'><span class='time'>"+n[s][0]+"</span><span class='text "+n[s][1]+"'>"+n[s][2]+"</span></div>")},percent:function(n){var i=this,s=Math.floor(100*n/i.process.length);t(i.settings.container_start+" .percent").addClass("p"+s),t(i.settings.container_start+" .percent span").html(s+"%")}},t.fn.install=function(n){var i=t.extend({container:"#install-container",container_start:"#install-start",container_end:"#install-end",container_logs:"#install-logs",route:"/app_dev.php/install",process:[]},n);new s(i)}}(jQuery,window,window.document);