/**
 * This script adds the accessibility-ready responsive menus Genesis Framework child themes.
 *
 * @author StudioPress
 * @link https://github.com/copyblogger/responsive-menus
 * @version 1.1.2
 * @license GPL-2.0+
 */
var genesisMenuParams="undefined"===typeof genesis_responsive_menu?"":genesis_responsive_menu,genesisMenusUnchecked=genesisMenuParams.menuClasses,genesisMenus={},menusToCombine=[];
(function(m,b,w){function n(){var a=b('button[id^="genesis-mobile-"]').attr("id");if("undefined"!==typeof a){"none"===k(a)&&(b(".menu-toggle, .genesis-responsive-menu .sub-menu-toggle").removeClass("activated").attr("aria-expanded",!1).attr("aria-pressed",!1),b(".genesis-responsive-menu, genesis-responsive-menu .sub-menu").attr("style",""));var d=b(".genesis-responsive-menu .js-superfish"),c="destroy";"function"===typeof d.superfish&&("none"===k(a)&&(c={delay:100,animation:{opacity:"show",height:"show"},
dropShadows:!1,speed:"fast"}),d.superfish(c));p(a);q(a)}}function r(){var a=b(this),d=a.next("nav");a.attr("id","genesis-mobile-"+b(d).attr("class").match(/nav-\w*\b/))}function q(a){if(null!=menusToCombine){var d=menusToCombine[0],c=b(menusToCombine).filter(function(a){if(0<a)return a});"none"!==k(a)?(b.each(c,function(a,c){b(c).find(".menu > li").addClass("moved-item-"+c.replace(".","")).appendTo(d+" ul.genesis-nav-menu")}),b(g(c)).hide()):(b(g(c)).show(),b.each(c,function(a,c){b(".moved-item-"+
c.replace(".","")).appendTo(c+" ul.genesis-nav-menu").removeClass("moved-item-"+c.replace(".",""))}))}}function t(){var a=b(this);h(a,"aria-pressed");h(a,"aria-expanded");a.toggleClass("activated");a.next("nav").slideToggle("fast")}function u(){var a=b(this),d=a.closest(".menu-item").siblings();h(a,"aria-pressed");h(a,"aria-expanded");a.toggleClass("activated");a.next(".sub-menu").slideToggle("fast");d.find(".sub-menu-toggle").removeClass("activated").attr("aria-pressed","false");d.find(".sub-menu").slideUp("fast")}
function p(a){var d=l();0< !b(d).length||b.each(d,function(c,d){var e=d.replace(".",""),f="genesis-"+e,g="genesis-mobile-"+e;"none"==k(a)&&(f="genesis-mobile-"+e,g="genesis-"+e);e=b('.genesis-skip-link a[href="#'+f+'"]');null!==menusToCombine&&d!==menusToCombine[0]&&e.toggleClass("skip-link-hidden");if(0<e.length){var h=e.attr("href"),h=h.replace(f,g);e.attr("href",h)}})}function k(a){a=m.getElementById(a);return window.getComputedStyle(a).getPropertyValue("display")}function h(a,b){a.attr(b,function(a,
b){return"false"===b})}function g(a){return b.map(a,function(a,b){return a}).join(",")}function l(){var a=[];null!==menusToCombine&&b.each(menusToCombine,function(b,c){a.push(c.valueOf())});b.each(genesisMenus.others,function(b,c){a.push(c.valueOf())});return 0<a.length?a:null}b.each(genesisMenusUnchecked,function(a){genesisMenus[a]=[];b.each(this,function(d,c){var f=b(c);1<f.length?b.each(f,function(d,f){var e=c+"-"+d;b(this).addClass(e.replace(".",""));genesisMenus[a].push(e);"combine"===a&&menusToCombine.push(e)}):
1==f.length&&(genesisMenus[a].push(c),"combine"===a&&menusToCombine.push(c))})});"undefined"==typeof genesisMenus.others&&(genesisMenus.others=[]);1==menusToCombine.length&&(genesisMenus.others.push(menusToCombine[0]),menusToCombine=genesisMenus.combine=null);var v={init:function(){if(0!=b(l()).length){var a="undefined"!==typeof genesisMenuParams.menuIconClass?genesisMenuParams.menuIconClass:"dashicons-before dashicons-menu",d="undefined"!==typeof genesisMenuParams.subMenuIconClass?genesisMenuParams.subMenuIconClass:
"dashicons-before dashicons-arrow-down-alt2",c=b("<button />",{"class":"menu-toggle","aria-expanded":!1,"aria-pressed":!1,role:"button"}).append(genesisMenuParams.mainMenu),f=b("<button />",{"class":"sub-menu-toggle","aria-expanded":!1,"aria-pressed":!1,role:"button"}).append(b("<span />",{"class":"screen-reader-text",text:genesisMenuParams.subMenu}));b(g(genesisMenus)).addClass("genesis-responsive-menu");b(g(genesisMenus)).find(".sub-menu").before(f);null!==menusToCombine?(f=genesisMenus.others.concat(menusToCombine[0]),
b(g(f)).before(c)):b(g(genesisMenus.others)).before(c);b(".menu-toggle").addClass(a);b(".sub-menu-toggle").addClass(d);b(".menu-toggle").on("click.genesisMenu-mainbutton",t).each(r);b(".sub-menu-toggle").on("click.genesisMenu-subbutton",u);b(window).on("resize.genesisMenu",n).triggerHandler("resize.genesisMenu")}}};b(m).ready(function(){null!==l()&&v.init()})})(document,jQuery);
;!function(a,b){function c(){function a(){"undefined"!=typeof _wpmejsSettings&&(c=b.extend(!0,{},_wpmejsSettings)),c.classPrefix="mejs-",c.success=c.success||function(a){var b,c;a.rendererName&&-1!==a.rendererName.indexOf("flash")&&(b=a.attributes.autoplay&&"false"!==a.attributes.autoplay,c=a.attributes.loop&&"false"!==a.attributes.loop,b&&a.addEventListener("canplay",function(){a.play()},!1),c&&a.addEventListener("ended",function(){a.play()},!1))},c.customError=function(a,b){if(-1!==a.rendererName.indexOf("flash")||-1!==a.rendererName.indexOf("flv"))return'<a href="'+b.src+'">'+mejsL10n.strings["mejs.download-video"]+"</a>"},b(".wp-audio-shortcode, .wp-video-shortcode").not(".mejs-container").filter(function(){return!b(this).parent().hasClass("mejs-mediaelement")}).mediaelementplayer(c)}var c={};return{initialize:a}}a.wp=a.wp||{},a.wp.mediaelement=new c,b(a.wp.mediaelement.initialize)}(window,jQuery);
;/*!
 * MediaElement.js
 * http://www.mediaelementjs.com/
 *
 * Wrapper that mimics native HTML5 MediaElement (audio and video)
 * using a variety of technologies (pure JavaScript, Flash, iframe)
 *
 * Copyright 2010-2017, John Dyer (http://j.hn/)
 * License: MIT
 *
 */
!function e(t,n,r){function i(o,s){if(!n[o]){if(!t[o]){var c="function"==typeof require&&require;if(!s&&c)return c(o,!0);if(a)return a(o,!0);var u=new Error("Cannot find module '"+o+"'");throw u.code="MODULE_NOT_FOUND",u}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return i(n||e)},l,l.exports,e,t,n,r)}return n[o].exports}for(var a="function"==typeof require&&require,o=0;o<r.length;o++)i(r[o]);return i}({1:[function(e,t,n){"use strict";var r={promise:null,load:function(e){"undefined"!=typeof Vimeo?r._createPlayer(e):(r.promise=r.promise||mejs.Utils.loadScript("https://player.vimeo.com/api/player.js"),r.promise.then(function(){r._createPlayer(e)}))},_createPlayer:function(e){var t=new Vimeo.Player(e.iframe);window["__ready__"+e.id](t)},getVimeoId:function(e){return void 0===e||null===e?null:(e=e.split("?")[0],parseInt(e.substring(e.lastIndexOf("/")+1),10))}},i={name:"vimeo_iframe",options:{prefix:"vimeo_iframe"},canPlayType:function(e){return~["video/vimeo","video/x-vimeo"].indexOf(e.toLowerCase())},create:function(e,t,n){var i=[],a={},o=!0,s=1,c=s,u=0,l=0,d=!1,p=0,m=null,f="";a.options=t,a.id=e.id+"_"+t.prefix,a.mediaElement=e;for(var v=function(t,n){var r=mejs.Utils.createEvent("error",n);r.message=t.name+": "+t.message,e.dispatchEvent(r)},h=mejs.html5media.properties,y=0,g=h.length;y<g;y++)!function(t){var n=""+t.substring(0,1).toUpperCase()+t.substring(1);a["get"+n]=function(){if(null!==m){switch(t){case"currentTime":return u;case"duration":return p;case"volume":return s;case"muted":return 0===s;case"paused":return o;case"ended":return d;case"src":return m.getVideoUrl().then(function(e){f=e}),f;case"buffered":return{start:function(){return 0},end:function(){return l*p},length:1};case"readyState":return 4}return null}return null},a["set"+n]=function(n){if(null!==m)switch(t){case"src":var o="string"==typeof n?n:n[0].src,l=r.getVimeoId(o);m.loadVideo(l).then(function(){e.originalNode.autoplay&&m.play()}).catch(function(e){v(e,a)});break;case"currentTime":m.setCurrentTime(n).then(function(){u=n,setTimeout(function(){var t=mejs.Utils.createEvent("timeupdate",a);e.dispatchEvent(t)},50)}).catch(function(e){v(e,a)});break;case"volume":m.setVolume(n).then(function(){c=s=n,setTimeout(function(){var t=mejs.Utils.createEvent("volumechange",a);e.dispatchEvent(t)},50)}).catch(function(e){v(e,a)});break;case"loop":m.setLoop(n).catch(function(e){v(e,a)});break;case"muted":n?m.setVolume(0).then(function(){s=0,setTimeout(function(){var t=mejs.Utils.createEvent("volumechange",a);e.dispatchEvent(t)},50)}).catch(function(e){v(e,a)}):m.setVolume(c).then(function(){s=c,setTimeout(function(){var t=mejs.Utils.createEvent("volumechange",a);e.dispatchEvent(t)},50)}).catch(function(e){v(e,a)});break;case"readyState":var d=mejs.Utils.createEvent("canplay",a);e.dispatchEvent(d)}else i.push({type:"set",propName:t,value:n})}}(h[y]);for(var E=mejs.html5media.methods,U=0,j=E.length;U<j;U++)!function(e){a[e]=function(){if(null!==m)switch(e){case"play":return o=!1,m.play();case"pause":return o=!0,m.pause();case"load":return null}else i.push({type:"call",methodName:e})}}(E[U]);window["__ready__"+a.id]=function(t){if(e.vimeoPlayer=m=t,i.length)for(var n=0,r=i.length;n<r;n++){var c=i[n];if("set"===c.type){var f=c.propName,h=""+f.substring(0,1).toUpperCase()+f.substring(1);a["set"+h](c.value)}else"call"===c.type&&a[c.methodName]()}e.originalNode.muted&&(m.setVolume(0),s=0);for(var y=document.getElementById(a.id),g=void 0,E=0,U=(g=["mouseover","mouseout"]).length;E<U;E++)y.addEventListener(g[E],function(t){var n=mejs.Utils.createEvent(t.type,a);e.dispatchEvent(n)},!1);m.on("loaded",function(){m.getDuration().then(function(t){if((p=t)>0&&(l=p*t,e.originalNode.autoplay)){o=!1,d=!1;var n=mejs.Utils.createEvent("play",a);e.dispatchEvent(n)}}).catch(function(e){v(e,a)})}),m.on("progress",function(){m.getDuration().then(function(t){if((p=t)>0&&(l=p*t,e.originalNode.autoplay)){var n=mejs.Utils.createEvent("play",a);e.dispatchEvent(n);var r=mejs.Utils.createEvent("playing",a);e.dispatchEvent(r)}var i=mejs.Utils.createEvent("progress",a);e.dispatchEvent(i)}).catch(function(e){v(e,a)})}),m.on("timeupdate",function(){m.getCurrentTime().then(function(t){u=t;var n=mejs.Utils.createEvent("timeupdate",a);e.dispatchEvent(n)}).catch(function(e){v(e,a)})}),m.on("play",function(){o=!1,d=!1;var t=mejs.Utils.createEvent("play",a);e.dispatchEvent(t);var n=mejs.Utils.createEvent("playing",a);e.dispatchEvent(n)}),m.on("pause",function(){o=!0,d=!1;var t=mejs.Utils.createEvent("pause",a);e.dispatchEvent(t)}),m.on("ended",function(){o=!1,d=!0;var t=mejs.Utils.createEvent("ended",a);e.dispatchEvent(t)});for(var j=0,b=(g=["rendererready","loadedmetadata","loadeddata","canplay"]).length;j<b;j++){var w=mejs.Utils.createEvent(g[j],a);e.dispatchEvent(w)}};var b=e.originalNode.height,w=e.originalNode.width,N=document.createElement("iframe"),_="https://player.vimeo.com/video/"+r.getVimeoId(n[0].src),x=~n[0].src.indexOf("?")?"?"+n[0].src.slice(n[0].src.indexOf("?")+1):"";return x&&e.originalNode.autoplay&&-1===x.indexOf("autoplay")&&(x+="&autoplay=1"),x&&e.originalNode.loop&&-1===x.indexOf("loop")&&(x+="&loop=1"),N.setAttribute("id",a.id),N.setAttribute("width",w),N.setAttribute("height",b),N.setAttribute("frameBorder","0"),N.setAttribute("src",""+_+x),N.setAttribute("webkitallowfullscreen",""),N.setAttribute("mozallowfullscreen",""),N.setAttribute("allowfullscreen",""),e.originalNode.parentNode.insertBefore(N,e.originalNode),e.originalNode.style.display="none",r.load({iframe:N,id:a.id}),a.hide=function(){a.pause(),m&&(N.style.display="none")},a.setSize=function(e,t){N.setAttribute("width",e),N.setAttribute("height",t)},a.show=function(){m&&(N.style.display="")},a.destroy=function(){},a}};mejs.Utils.typeChecks.push(function(e){return/(\/\/player\.vimeo|vimeo\.com)/i.test(e)?"video/x-vimeo":null}),mejs.Renderers.add(i)},{}]},{},[1]);