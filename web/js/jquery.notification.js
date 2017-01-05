var http = require('http');

var fs = require('fs');


// Chargement du fichier index.html affiché au client

var server = http.createServer(function(req, res) {

    fs.readFile('./index.html', 'utf-8', function(error, content) {

        res.writeHead(200, {"Content-Type": "text/html"});

        res.end(content);

    });

});


// Chargement de socket.io

var io = require('socket.io').listen(server);


// Quand un client se connecte, on le note dans la console

io.sockets.on('connection', function (socket) {

    console.log('Un client est connecté !');

});



server.listen(8080);

// !function(t,n,i){function a(t){this.settings=t,this.init()}a.prototype={init:function(){var n=this;n.settings.project.construct||n.settings.project.maintenance||(t("body").append("<div id='"+n.settings.modal.container+"' class='reveal-modal' data-reveal aria-labelledby='modalTitle' aria-hidden='true' role='dialog'><div id='"+n.settings.modal.content+"'></div><a class='close-reveal-modal' aria-label='Close'>&#215;</a></div>"),this.initAction(),this.initModal(),this.initCounter())},initAction:function(){var n=this;t(n.settings.action.container).unbind("click").click(function(i){i.preventDefault(),t.ajax({type:"POST",url:t(this).attr("data-url"),data:{},success:function(i){t("#"+n.settings.modal.content).html(i),n.initModal(),n.initAction()}})})},initModal:function(){var n=this;t(n.settings.modal.action).unbind("click").click(function(i){i.preventDefault(),t.ajax({type:"POST",url:t(this).attr("data-url"),data:{},success:function(i){t("#"+n.settings.modal.content).html(i),t("#"+n.settings.modal.container).foundation("reveal","open"),n.initModal(),n.initAction()}})})},initCounter:function(){var n=this;setInterval(function(){t.ajax({type:"POST",url:n.settings.counter.url,data:{},success:function(i){t(n.settings.counter.container).each(function(){0==i.count?t(this).removeClass("new").html(i.count):t(this).addClass("new").html(i.count)})}})},n.settings.counter.time)}},t.fn.notification=function(n){var i=t.extend({project:{maintenance:!1,construct:!1},modal:{container:"notificationModal",content:"notificationModalContent",action:".notification a[data-modal]"},action:{container:".notification a[data-action]"},counter:{container:".notification [data-counter]",url:"/api/notification/counter/number",time:1e4}},n);new a(i)}}(jQuery,window,window.document);