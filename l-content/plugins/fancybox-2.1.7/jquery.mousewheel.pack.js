/*! Copyright (c) 2013 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.1.3
 *
 * Requires: 1.2.2+
 */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(e){function t(t){var l=t||window.event,i=[].slice.call(arguments,1),h=0,s=0,a=0,u=0;u=0;(t=e.event.fix(l)).type="mousewheel",l.wheelDelta&&(h=l.wheelDelta),l.detail&&(h=-1*l.detail),l.deltaY&&(h=a=-1*l.deltaY),l.deltaX&&(h=-1*(s=l.deltaX)),void 0!==l.wheelDeltaY&&(a=l.wheelDeltaY),void 0!==l.wheelDeltaX&&(s=-1*l.wheelDeltaX),u=Math.abs(h),(!o||u<o)&&(o=u),u=Math.max(Math.abs(a),Math.abs(s)),(!n||u<n)&&(n=u),l=0<h?"floor":"ceil",h=Math[l](h/o),s=Math[l](s/n),a=Math[l](a/n);try{t.originalEvent.hasOwnProperty("wheelDelta")}catch(e){a=h}return i.unshift(t,h,s,a),(e.event.dispatch||e.event.handle).apply(this,i)}var o,n,l=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],i="onwheel"in document||9<=document.documentMode?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"];if(e.event.fixHooks)for(var h=l.length;h;)e.event.fixHooks[l[--h]]=e.event.mouseHooks;e.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var e=i.length;e;)this.addEventListener(i[--e],t,!1);else this.onmousewheel=t},teardown:function(){if(this.removeEventListener)for(var e=i.length;e;)this.removeEventListener(i[--e],t,!1);else this.onmousewheel=null}},e.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})});