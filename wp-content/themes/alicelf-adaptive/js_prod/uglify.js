var SITE_URL=aa_ajax_var.site_url,AJAXURL=aa_ajax_var.ajax_url,THEME_URI=aa_ajax_var.template_uri,IMG_DIR=THEME_URI+"/img/",LOAD_LINE="#global-load-line",_COLORS={purple:"#684fb6",red:"#d9534f"},Loaders={bouncingAbsolute:'<div id="loader-absolute" class="labsolut"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',bouncingStatic:'<div id="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',infiniteSpinner:'<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>',svgLoader:'<img id="svg-loader-process" src="'+THEME_URI+'/svg/loader_svg.svg" width="40" alt="loadersvg"/>'};jQuery(document).ready(function(e){e.fn.waitUntilExists=function(a,t,o){var s="found",n=e(this.selector),i=n.not(function(){return e(this).data(s)}).each(a).data(s,!0);return o?t&&i.length&&window.clearInterval(window.waitUntilExists_Intervals[this.selector]):(window.waitUntilExists_Intervals=window.waitUntilExists_Intervals||{})[this.selector]=window.setInterval(function(){n.waitUntilExists(a,t,!0)},500),n};var a=function(e,a){return"<div class='alert alert-"+e+"'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><h3 class='text-center'>"+a+"</h3></div>"},t=function(e,a){return e.className&&new RegExp("(\\s|^)"+a+"(\\s|$)").test(e.className)};e(".alert-backdrop").waitUntilExists(function(){var a=e(this);a.on("click",function(e){e.stopPropagation(),t(e.target,"alert-backdrop")&&(a.removeClass("show"),setTimeout(function(){a.remove()},300))})}),e(".modal-backdrop").waitUntilExists(function(){var a=e(this);a.on("click",function(e){e.stopPropagation(),t(e.target,"modal-backdrop")&&(a.removeClass("show"),setTimeout(function(){a.css({display:"none"})},400))})}),function(){var t="Your message has been successfully sended",o="Fill Correct all required fields!",s="Fill Correct Captha",n="Something Wrong, try again later",i=function(i){e("form.alice-ajax-contact-form").on("submit",function(){var r=e(this),l=r.attr("method"),d={action:i};return r.find("[name]").each(function(){d[e(this).attr("name")]=e(this).val()}),e.ajax({url:AJAXURL,type:l,data:d,error:function(){alert("Something wrong! Try again later.")},beforeSend:function(){e("body").prepend(Loaders.infiniteSpinner),e("body .loader-backdrop").animate({opacity:1},500)},success:function(i){var l=e("body"),d=e(".ghostly-wrap");switch(i){case"success":l.find(".loader-backdrop").remove(),d.find(".alert").remove(),r.before(a("success",t)),r.find("[name]").each(function(){e(this).val("")});break;case"error":l.find(".loader-backdrop").remove(),d.find(".alert").remove(),r.before(a("danger",o));break;case"error captcha":l.find(".loader-backdrop").remove(),d.find(".alert").remove(),r.before(a("warning",s));break;default:l.find(".loader-backdrop").remove(),d.find(".alert").remove(),r.before(a("info",n))}},complete:function(){}}),!1})};i("aa_contact_form");var r=function(){var a=1,t=!0,o=e(window),s=e("#ajax-posts-loop"),n=function(){e(LOAD_LINE).empty(),e(LOAD_LINE).fadeIn();var o=new ProgressBar.Line(LOAD_LINE,{color:_COLORS.purple});o.animate(.2),e.ajax({url:AJAXURL,type:"POST",data:{alice_ajax_posts:!0,pageNumber:a,action:"alice_ajax_posts"},dataType:"html",beforeSend:function(){1!==a&&s.append(Loaders.bouncingStatic),o.animate(.5)},success:function(a){var n=e(a);n.length?(n.hide(),s.append(n),n.fadeIn(),e("#loader-static").remove(),t=!1):e("#loader-static").remove(),o.animate(1),setTimeout(function(){e(LOAD_LINE).fadeOut()},500)},error:function(a,t,o){e("#loader-static").remove(),alert(a+" :: "+t+" :: "+o)}})};o.on("scroll",function(){window.innerHeight+window.scrollY>=document.body.offsetHeight&&t===!1&&(t=!0,a++,n())}),n()};null!==document.getElementById("ajax-posts-loop")&&r()}()}),window.onload=function(){"use strict";var e=function(e,a){return e.className&&new RegExp("(\\s|^)"+a+"(\\s|$)").test(e.className)},a=function(){var e,a,t,o,s;if(e=document.getElementsByClassName("wp-pagenavi"),void 0!==e){s=function(e){if(void 0!==e.childNodes){var s;for(o=e.childNodes.length,a=document.createElement("ul"),a.classList.add("pagination"),e.insertBefore(a,e.firstChild);o--;)s=e.childNodes[o],void 0!==s.tagName&&"UL"!==s.tagName&&(t=document.createElement("li"),t.appendChild(s),a.insertBefore(t,a.firstChild))}};for(var n=0;n<e.length;n++){var i=e[n];i.innerHtml=s(i)}}};a();var t=function(){var a=document.getElementById("alicelf-commentform");if(a){var t=a.elements.author,o=a.elements.email,s=a.elements.comment,n=document.getElementById("respond");a.onsubmit=function(i){var r,l,d,c=a.elements.length,u=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,m='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4>Fill correct all required fields!</h4><p>Note: name cannot be blank, email must be a valid mail, comment field also must be filled</p>';if(""===t.value||""===o.value||-1===o.value.search(u)||""===s.value){for(i.preventDefault(),e(n.firstChild,"alert-danger")||n.insertAdjacentHTML("afterbegin",m);c--;)r=a.elements[c],l=r.type,d=!("text"!==l&&"textarea"!==l),d&&(""===r.value?(r.parentNode.classList.remove("has-success"),r.parentNode.classList.add("has-error")):""!==r.value&&"email"===r.name&&-1===r.value.search(u)?(r.parentNode.classList.remove("has-success"),r.parentNode.classList.add("has-error")):(r.parentNode.classList.remove("has-error"),r.parentNode.classList.add("has-success")));return alert("Fill Correct All required Fields!"),!1}}}};t();var o=function(){var e=document.getElementById("footer-angle-arrow");window.document.addEventListener("scroll",function(){var a=document.documentElement.scrollTop||document.body.scrollTop;a>300?e.classList.add("visible-arrow"):e.classList.remove("visible-arrow")})};if(o(),"object"==typeof smoothScroll){var s=document.querySelector("#footer-angle-arrow"),n={speed:700,easing:"easeOutQuart"};s.onclick=function(e){e.preventDefault(),smoothScroll.animateScroll(s,"#scroll-trigger-top",n)}}},jQuery(document).ready(function(e){var a=function(){e(".slider-for").slick({slidesToShow:1,slidesToScroll:1,arrows:!1,fade:!0,asNavFor:".slider-nav"}),e(".slider-nav").slick({slidesToShow:3,slidesToScroll:1,asNavFor:".slider-for",dots:!1,centerMode:!0,focusOnSelect:!0})};"function"==typeof e.fn.slick&&a();var t=function(){e(window).on("scroll",function(){var a=document.documentElement.scrollTop||document.body.scrollTop,t=e(".stick-to-top").find(">.container > header"),o=e("#wpadminbar").height(),s=t.height();e(window).width()<600&&(o=0),e(window).on("resize",function(){e(window).width()<600&&(o=0),s=e(".stick-to-top").find(">.container > header").height()}),a>s?(t.css({position:"fixed",width:"100%",top:0+o+"px","z-index":"999"}),t.hasClass("header-touch-top")||(t.css({top:-s+"px",opacity:"0"}),t.animate({top:0+o+"px",opacity:1},500)),t.addClass("header-touch-top"),e("#shock-absorber").css({height:s+"px"})):(t.css({position:"static",width:"auto"}),t.removeClass("header-touch-top"),e("#shock-absorber").css({height:0}))})};t(),transformicons.add(".tcon"),function(){var a=function(a,t){a.hasClass("tcon-transform")?(e("body").addClass("disable-scroll"),t.css("display","block"),setTimeout(function(){t.addClass("open-menu")},10)):(e("body").removeClass("disable-scroll"),t.removeClass("open-menu"),setTimeout(function(){t.css("display","none")},300))},t=e("#mobile-menu-trigger").find("> button"),o=e("#main-alicelf-nav");a(t,o),t.on("click",function(){a(e(this),o)}),o.find(".caret").on("click",function(a){a.stopPropagation(),a.preventDefault();var t=e(this),o=t.parent();t.toggleClass("fa-minus"),o.siblings(".sub-menu").toggleClass("shown-submenu")}),o.on("click",function(a){a.stopPropagation(),e(a.target).hasClass("main-navigation")&&(o.removeClass("open-menu"),setTimeout(function(){o.css("display","none")},300),t.removeClass("tcon-transform"))})}(),e("[data-modal-trigger]").on("click",function(a){a.preventDefault();var t=e(this),o=t.attr("data-modal-trigger"),s=e("body"),n=t.attr("data-related-modal");s.find(n).css({display:"block"}),setTimeout(function(){s.find(n).addClass("show")},10),e(window).trigger("aaModalOpened",[o,n])});var o=e("[data-destroy-modal]");o.on("click",function(a){a.preventDefault();var t=e(this),o=e(t.attr("data-destroy-modal"));o.removeClass("show"),setTimeout(function(){o.css("display","none"),e(window).trigger("aaModalClosed")},300)})});
//# sourceMappingURL=uglify.js.map
