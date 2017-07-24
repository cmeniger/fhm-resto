!function($,window,document){function Plugin(t){this.settings=t,this.init()}Plugin.prototype={init:function(){this.initForm(),this.initAjax()},initForm:function(){var t=this;$("form").each(function(i){"undefined"!=typeof $(this).attr("data-required")&&1!=$(this).attr("data-required")||0!=$(this).find(".form-required").length||$(this).append("<div class='form-required'>"+t.settings.required+"</div>")}),t.settings.form.select.multi_click&&$("form select option").unbind("mousedown").mousedown(function(t){return t.preventDefault(),$(this).prop("selected",!$(this).prop("selected")),$(this).parent().change(),!1})},initAjax:function(){var t=this;$(t.settings.ajax.form).unbind("submit").submit(function(i){var e=$(this),a=e.find(":submit"),n="undefined"==typeof a.attr("data-load")?t.settings.ajax.load:a.attr("data-load"),r=e.attr(t.settings.ajax.content);a.attr("data-text",a.attr("value")).html(n).prop("disabled",!0),i.preventDefault(),$.ajax({type:"POST",url:e.attr("action"),data:e.serialize(),success:function(i){"undefined"!=typeof e.attr(t.settings.ajax.animation)&&e.attr(t.settings.ajax.animation)===!0?$(r).fadeToggle(400,"linear",function(){$(r).html(t.getHtml(i,r)).fadeToggle(400,"linear"),a.html(a.attr("data-text")).prop("disabled",!1),t.refreshScript(i),t.initAjax(),t.initAlert(e)}):($(r).html(t.getHtml(i,r)),a.html(a.attr("data-text")).prop("disabled",!1),t.refreshScript(i),t.initAjax(),t.initAlert(e))}})}),$(t.settings.ajax.form).each(function(){var i=$(this);"undefined"!=typeof i.attr(t.settings.ajax.live)&&i.find(":input").each(function(){$(this).unbind("change").change(function(){i.trigger("submit")})})}),$(t.settings.ajax.link).unbind("click").click(function(i){var e=$(this),a=e.attr(t.settings.ajax.content);i.preventDefault(),$.ajax({type:"POST",url:e.attr("href"),data:{},success:function(i){$(a).fadeToggle(400,"linear",function(){$(a).html(t.getHtml(i,a)).fadeToggle(400,"linear"),t.refreshScript(i),t.initAjax(),t.initAlert(e)})}})})},initAlert:function(t){var i=this;setTimeout(function(){t.find(i.settings.ajax.alert).fadeOut()},5e3)},refreshScript:function(data){var parent=this;$("<div>"+data+"</div>").find("script").each(function(){eval(this.innerHTML)}),$(".g-recaptcha").each(function(){"undefined"!=typeof grecaptcha&&grecaptcha.render(this,{sitekey:$(this).attr("data-sitekey")})})},getHtml:function(t,i){var e=$($.parseHTML(t));return e.filter(i).add(e.find(i)).html()}},$.fn.form=function(t){var i=$.extend({required:"",form:{tag:"data-required",select:{multi_click:!0}},ajax:{form:"form[data-type=ajax]",link:"a[data-type=ajax]",content:"data-content",live:"data-live",animation:"data-animation",alert:".alert-box",load:'<i class="fa fa-circle-o-notch fa-spin"></i>'}},t);new Plugin(i)}}(jQuery,window,window.document);