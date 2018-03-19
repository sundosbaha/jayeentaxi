/*
Syn - a Standalone Synthetic Event Library.

Syn is used to simulate user actions such as typing, clicking, dragging
the mouse.  It creates synthetic events and performs default event behavior.

http://jupiterjs.com/news/syn-a-standalone-synthetic-event-library

*/(function(){var e=function(k,a){for(var b in a)k[b]=a[b];return k},j={msie:!(!window.attachEvent||window.opera),opera:!!window.opera,webkit:-1<navigator.userAgent.indexOf("AppleWebKit/"),safari:-1<navigator.userAgent.indexOf("AppleWebKit/")&&-1===navigator.userAgent.indexOf("Chrome/"),gecko:-1<navigator.userAgent.indexOf("Gecko"),mobilesafari:!!navigator.userAgent.match(/Apple.*Mobile.*Safari/),rhino:navigator.userAgent.match(/Rhino/)&&!0},m=function(k,a,b){k=b.ownerDocument.createEventObject();return e(k,
a)},h={},i=1,a="_synthetic"+(new Date).getTime(),b,d,g=/keypress|keyup|keydown/,c=/load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll/,l,f=function(k,a,b,g){return new f.init(k,a,b,g)};b=function(k,a,b){return k.addEventListener?k.addEventListener(a,b,!1):k.attachEvent("on"+a,b)};d=function(k,a,b){return k.addEventListener?k.removeEventListener(a,b,!1):k.detachEvent("on"+a,b)};e(f,{init:function(k,a,b,g){var c=f.args(a,b,g),d=this;this.queue=[];this.element=c.element;if("function"===
typeof this[k])this[k](c.options,c.element,function(k,a){c.callback&&c.callback.apply(d,arguments);d.done.apply(d,arguments)});else this.result=f.trigger(k,c.options,c.element),c.callback&&c.callback.call(this,c.element,this.result)},jquery:function(k){return window.FuncUnit&&window.FuncUnit.jquery?window.FuncUnit.jquery:k?f.helpers.getWindow(k).jQuery||window.jQuery:window.jQuery},args:function(){for(var k={},a=0;a<arguments.length;a++)if("function"===typeof arguments[a])k.callback=arguments[a];
else if(arguments[a]&&arguments[a].jquery)k.element=arguments[a][0];else if(arguments[a]&&arguments[a].nodeName)k.element=arguments[a];else if(k.options&&"string"===typeof arguments[a])k.element=document.getElementById(arguments[a]);else if(arguments[a])k.options=arguments[a];return k},click:function(a,b,c){f("click!",a,b,c)},defaults:{focus:function(){if(!f.support.focusChanges){var a=this,c=a.nodeName.toLowerCase();f.data(a,"syntheticvalue",a.value);("input"===c||"textarea"===c)&&b(a,"blur",function(){f.data(a,
"syntheticvalue")!=a.value&&f.trigger("change",{},a);d(a,"blur",arguments.callee)})}},submit:function(){f.onParents(this,function(a){if("form"===a.nodeName.toLowerCase())return a.submit(),!1})}},changeOnBlur:function(a,c,g){b(a,"blur",function(){g!==a[c]&&f.trigger("change",{},a);d(a,"blur",arguments.callee)})},closest:function(a,b){for(;a&&a.nodeName.toLowerCase()!==b.toLowerCase();)a=a.parentNode;return a},data:function(b,c,g){b[a]||(b[a]=i++);h[b[a]]||(h[b[a]]={});if(g)h[b[a]][c]=g;else return h[b[a]][c]},
onParents:function(a,b){for(var c;a&&!1!==c;)c=b(a),a=a.parentNode;return a},focusable:/^(a|area|frame|iframe|label|input|select|textarea|button|html|object)$/i,isFocusable:function(a){var b;return(this.focusable.test(a.nodeName)||(b=a.getAttributeNode("tabIndex"))&&b.specified)&&f.isVisible(a)},isVisible:function(a){return a.offsetWidth&&a.offsetHeight||a.clientWidth&&a.clientHeight},tabIndex:function(a){var b=a.getAttributeNode("tabIndex");return b&&b.specified&&(parseInt(a.getAttribute("tabIndex"))||
0)},bind:b,unbind:d,browser:j,helpers:{createEventObject:m,createBasicStandardEvent:function(a,b,c){var g;try{g=c.createEvent("Events")}catch(f){g=c.createEvent("UIEvents")}finally{g.initEvent(a,!0,!0),e(g,b)}return g},inArray:function(a,b){for(var c=0;c<b.length;c++)if(b[c]===a)return c;return-1},getWindow:function(a){return a.ownerDocument.defaultView||a.ownerDocument.parentWindow},extend:e,scrollOffset:function(a,b){var c=a.document.documentElement,g=a.document.body;if(b)window.scrollTo(b.left,
b.top);else return{left:(c&&c.scrollLeft||g&&g.scrollLeft||0)+(c.clientLeft||0),top:(c&&c.scrollTop||g&&g.scrollTop||0)+(c.clientTop||0)}},scrollDimensions:function(a){var b=a.document.documentElement,c=a.document.body,g=b.clientWidth,b=b.clientHeight,a="CSS1Compat"===a.document.compatMode;return{height:a&&b||c.clientHeight||b,width:a&&g||c.clientWidth||g}},addOffset:function(a,b){var c=f.jquery(b);if("object"===typeof a&&void 0===a.clientX&&void 0===a.clientY&&void 0===a.pageX&&void 0===a.pageY&&
c)b=c(b),c=b.offset(),a.pageX=c.left+b.width()/2,a.pageY=c.top+b.height()/2}},key:{ctrlKey:null,altKey:null,shiftKey:null,metaKey:null},dispatch:function(a,c,g,f){if(c.dispatchEvent&&a){var l=a.preventDefault,h=f?-1:0;f&&b(c,g,function(a){a.preventDefault();d(this,g,arguments.callee)});a.preventDefault=function(){h++;0<++h&&l.apply(this,[])};c.dispatchEvent(a);return 0>=h}try{window.event=a}catch(e){}return 0>=c.sourceIndex||c.fireEvent&&c.fireEvent("on"+g,a)},create:{page:{event:function(a,b,c){var g=
f.helpers.getWindow(c).document||document,d;if(g.createEvent)d=g.createEvent("Events"),d.initEvent(a,!0,!0);else try{d=m(a,b,c)}catch(l){}return d}},focus:{event:function(a,b,c){f.onParents(c,function(a){if(f.isFocusable(a)){if("html"!==a.nodeName.toLowerCase())a.focus(),l=a;else if(l)a=f.helpers.getWindow(c).document,a===window.document&&(a.activeElement?a.activeElement.blur():l.blur(),l=null);return!1}});return!0}}},support:{clickChanges:!1,clickSubmits:!1,keypressSubmits:!1,mouseupSubmits:!1,radioClickChanges:!1,
focusChanges:!1,linkHrefJS:!1,keyCharacters:!1,backspaceWorks:!1,mouseDownUpClicks:!1,tabKeyTabs:!1,keypressOnAnchorClicks:!1,optionClickBubbles:!1,ready:0},trigger:function(a,b,d){b||(b={});var l=f.create,h=l[a]&&l[a].setup,e=g.test(a)?"key":c.test(a)?"page":"mouse",i=l[a]||{},e=l[e],l=d;2===f.support.ready&&h&&h(a,b,d);h=b._autoPrevent;delete b._autoPrevent;if(i.event)i=i.event(a,b,d);else{b=e.options?e.options(a,b,d):b;if(!f.support.changeBubbles&&/option/i.test(d.nodeName))l=d.parentNode;i=e.event(a,
b,l);i=f.dispatch(i,l,a,h)}i&&2===f.support.ready&&f.defaults[a]&&f.defaults[a].call(d,b,h);return i},eventSupported:function(a){var b=document.createElement("div"),a="on"+a,c=a in b;c||(b.setAttribute(a,"return;"),c="function"===typeof b[a]);return c}});e(f.init.prototype,{then:function(a,b,c,g){f.autoDelay&&this.delay();var d=f.args(b,c,g),l=this;this.queue.unshift(function(b){if("function"===typeof this[a])this.element=d.element||b,this[a](d.options,this.element,function(a,b){d.callback&&d.callback.apply(l,
arguments);l.done.apply(l,arguments)});else return this.result=f.trigger(a,d.options,d.element),d.callback&&d.callback.call(this,d.element,this.result),this});return this},delay:function(a,b){"function"===typeof a&&(b=a,a=null);var a=a||600,c=this;this.queue.unshift(function(){setTimeout(function(){b&&b.apply(c,[]);c.done.apply(c,arguments)},a)});return this},done:function(a,b){b&&(this.element=b);this.queue.length&&this.queue.pop().call(this,this.element,a)},_click:function(a,b,c,g){f.helpers.addOffset(a,
b);f.trigger("mousedown",a,b);setTimeout(function(){f.trigger("mouseup",a,b);!f.support.mouseDownUpClicks||g?(f.trigger("click",a,b),c(!0)):(f.create.click.setup("click",a,b),f.defaults.click.call(b),setTimeout(function(){c(!0)},1))},1)},_rightClick:function(a,b,c){f.helpers.addOffset(a,b);var g=e(e({},f.mouse.browser.right.mouseup),a);f.trigger("mousedown",g,b);setTimeout(function(){f.trigger("mouseup",g,b);f.mouse.browser.right.contextmenu&&f.trigger("contextmenu",e(e({},f.mouse.browser.right.contextmenu),
a),b);c(!0)},1)},_dblclick:function(a,b,c){f.helpers.addOffset(a,b);var g=this;this._click(a,b,function(){setTimeout(function(){g._click(a,b,function(){f.trigger("dblclick",a,b);c(!0)},!0)},2)})}});for(var j="click,dblclick,move,drag,key,type,rightClick".split(","),v=function(a){f[a]=function(b,c,g){return f("_"+a,b,c,g)};f.init.prototype[a]=function(b,c,g){return this.then("_"+a,b,c,g)}},r=0;r<j.length;r++)v(j[r]);if(window.FuncUnit&&window.FuncUnit.jQuery||window.jQuery)(window.FuncUnit&&window.FuncUnit.jQuery||
window.jQuery).fn.triggerSyn=function(a,b,c){f(a,b,this[0],c);return this};window.Syn=f})(!0);
(function(){var e=Syn.helpers,j=e.getWindow;Syn.mouse={};e.extend(Syn.defaults,{mousedown:function(){Syn.trigger("focus",{},this)},click:function(){var e=Syn.data(this,"radioChanged"),h=j(this),i=this.nodeName.toLowerCase();if(!Syn.support.linkHrefJS&&/^\s*javascript:/.test(this.href)){var a=this.href.replace(/^\s*javascript:/,"");"//"!=a&&-1==a.indexOf("void(0)")&&(window.selenium?eval("with(selenium.browserbot.getCurrentWindow()){"+a+"}"):eval("with(scope){"+a+"}"))}if(!Syn.support.clickSubmits&&
"input"==i&&"submit"==this.type||"button"==i)(a=Syn.closest(this,"form"))&&Syn.trigger("submit",{},a);if("a"==i&&this.href&&!/^\s*javascript:/.test(this.href))h.location.href=this.href;"input"==i&&"checkbox"==this.type&&(Syn.support.clickChanges||Syn.trigger("change",{},this));"input"==i&&"radio"==this.type&&e&&!Syn.support.radioClickChanges&&Syn.trigger("change",{},this);"option"==i&&Syn.data(this,"createChange")&&(Syn.trigger("change",{},this.parentNode),Syn.data(this,"createChange",!1))}});e.extend(Syn.create,
{mouse:{options:function(j,h){var i=document.documentElement,a=document.body,b=[h.pageX||0,h.pageY||0],d=Syn.mouse.browser&&Syn.mouse.browser.left[j],g=Syn.mouse.browser&&Syn.mouse.browser.right[j];return e.extend({bubbles:!0,cancelable:!0,view:window,detail:1,screenX:1,screenY:1,clientX:h.clientX||b[0]-(i&&i.scrollLeft||a&&a.scrollLeft||0)-(i.clientLeft||0),clientY:h.clientY||b[1]-(i&&i.scrollTop||a&&a.scrollTop||0)-(i.clientTop||0),ctrlKey:!!Syn.key.ctrlKey,altKey:!!Syn.key.altKey,shiftKey:!!Syn.key.shiftKey,
metaKey:!!Syn.key.metaKey,button:d&&null!=d.button?d.button:g&&g.button||("contextmenu"==j?2:0),relatedTarget:document.documentElement},h)},event:function(m,h,i){var a=j(i).document||document;if(a.createEvent){var b;try{b=a.createEvent("MouseEvents"),b.initMouseEvent(m,h.bubbles,h.cancelable,h.view,h.detail,h.screenX,h.screenY,h.clientX,h.clientY,h.ctrlKey,h.altKey,h.shiftKey,h.metaKey,h.button,h.relatedTarget)}catch(d){b=e.createBasicStandardEvent(m,h,a)}b.synthetic=!0}else try{b=e.createEventObject(m,
h,i)}catch(g){}return b}},click:{setup:function(e,h,i){h=i.nodeName.toLowerCase();if(!Syn.support.clickChecks&&!Syn.support.changeChecks&&"input"===h){e=i.type.toLowerCase();if("checkbox"===e)i.checked=!i.checked;if("radio"===e&&!i.checked){try{Syn.data(i,"radioChanged",!0)}catch(a){}i.checked=!0}}"a"==h&&i.href&&!/^\s*javascript:/.test(i.href)&&Syn.data(i,"href",i.href);if(/option/i.test(i.nodeName)){e=i.parentNode.firstChild;for(h=-1;e&&!(1==e.nodeType&&(h++,e==i));)e=e.nextSibling;if(h!==i.parentNode.selectedIndex)i.parentNode.selectedIndex=
h,Syn.data(i,"createChange",!0)}}},mousedown:{setup:function(e,h,i){e=i.nodeName.toLowerCase();if(Syn.browser.safari&&("select"==e||"option"==e))h._autoPrevent=!0}}});(function(){if(document.body){var e=window.__synthTest;window.__synthTest=function(){Syn.support.linkHrefJS=!0};var h=document.createElement("div"),i,a,b,d;h.innerHTML="<form id='outer'><input name='checkbox' type='checkbox'/><input name='radio' type='radio' /><input type='submit' name='submitter'/><input type='input' name='inputter'/><input name='one'><input name='two'/><a href='javascript:__synthTest()' id='synlink'></a><select><option></option></select></form>";
document.documentElement.appendChild(h);b=h.firstChild;i=b.childNodes[0];a=b.childNodes[2];d=b.getElementsByTagName("select")[0];i.checked=!1;i.onchange=function(){Syn.support.clickChanges=!0};Syn.trigger("click",{},i);Syn.support.clickChecks=i.checked;i.checked=!1;Syn.trigger("change",{},i);Syn.support.changeChecks=i.checked;b.onsubmit=function(a){a.preventDefault&&a.preventDefault();Syn.support.clickSubmits=!0;return!1};Syn.trigger("click",{},a);b.childNodes[1].onchange=function(){Syn.support.radioClickChanges=
!0};Syn.trigger("click",{},b.childNodes[1]);Syn.bind(h,"click",function(){Syn.support.optionClickBubbles=!0;Syn.unbind(h,"click",arguments.callee)});Syn.trigger("click",{},d.firstChild);Syn.support.changeBubbles=Syn.eventSupported("change");h.onclick=function(){Syn.support.mouseDownUpClicks=!0};Syn.trigger("mousedown",{},h);Syn.trigger("mouseup",{},h);document.documentElement.removeChild(h);window.__synthTest=e;Syn.support.ready++}else setTimeout(arguments.callee,1)})()})(!0);
(function(){Syn.key.browsers={webkit:{prevent:{keyup:[],keydown:["char","keypress"],keypress:["char"]},character:{keydown:[0,"key"],keypress:["char","char"],keyup:[0,"key"]},specialChars:{keydown:[0,"char"],keyup:[0,"char"]},navigation:{keydown:[0,"key"],keyup:[0,"key"]},special:{keydown:[0,"key"],keyup:[0,"key"]},tab:{keydown:[0,"char"],keyup:[0,"char"]},"pause-break":{keydown:[0,"key"],keyup:[0,"key"]},caps:{keydown:[0,"key"],keyup:[0,"key"]},escape:{keydown:[0,"key"],keyup:[0,"key"]},"num-lock":{keydown:[0,
"key"],keyup:[0,"key"]},"scroll-lock":{keydown:[0,"key"],keyup:[0,"key"]},print:{keyup:[0,"key"]},"function":{keydown:[0,"key"],keyup:[0,"key"]},"\r":{keydown:[0,"key"],keypress:["char","key"],keyup:[0,"key"]}},gecko:{prevent:{keyup:[],keydown:["char"],keypress:["char"]},character:{keydown:[0,"key"],keypress:["char",0],keyup:[0,"key"]},specialChars:{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]},navigation:{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]},special:{keydown:[0,"key"],keyup:[0,
"key"]},"\t":{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]},"pause-break":{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]},caps:{keydown:[0,"key"],keyup:[0,"key"]},escape:{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]},"num-lock":{keydown:[0,"key"],keyup:[0,"key"]},"scroll-lock":{keydown:[0,"key"],keyup:[0,"key"]},print:{keyup:[0,"key"]},"function":{keydown:[0,"key"],keyup:[0,"key"]},"\r":{keydown:[0,"key"],keypress:[0,"key"],keyup:[0,"key"]}},msie:{prevent:{keyup:[],keydown:["char",
"keypress"],keypress:["char"]},character:{keydown:[null,"key"],keypress:[null,"char"],keyup:[null,"key"]},specialChars:{keydown:[null,"char"],keyup:[null,"char"]},navigation:{keydown:[null,"key"],keyup:[null,"key"]},special:{keydown:[null,"key"],keyup:[null,"key"]},tab:{keydown:[null,"char"],keyup:[null,"char"]},"pause-break":{keydown:[null,"key"],keyup:[null,"key"]},caps:{keydown:[null,"key"],keyup:[null,"key"]},escape:{keydown:[null,"key"],keypress:[null,"key"],keyup:[null,"key"]},"num-lock":{keydown:[null,
"key"],keyup:[null,"key"]},"scroll-lock":{keydown:[null,"key"],keyup:[null,"key"]},print:{keyup:[null,"key"]},"function":{keydown:[null,"key"],keyup:[null,"key"]},"\r":{keydown:[null,"key"],keypress:[null,"key"],keyup:[null,"key"]}},opera:{prevent:{keyup:[],keydown:[],keypress:["char"]},character:{keydown:[null,"key"],keypress:[null,"char"],keyup:[null,"key"]},specialChars:{keydown:[null,"char"],keypress:[null,"char"],keyup:[null,"char"]},navigation:{keydown:[null,"key"],keypress:[null,"key"]},special:{keydown:[null,
"key"],keypress:[null,"key"],keyup:[null,"key"]},tab:{keydown:[null,"char"],keypress:[null,"char"],keyup:[null,"char"]},"pause-break":{keydown:[null,"key"],keypress:[null,"key"],keyup:[null,"key"]},caps:{keydown:[null,"key"],keyup:[null,"key"]},escape:{keydown:[null,"key"],keypress:[null,"key"]},"num-lock":{keyup:[null,"key"],keydown:[null,"key"],keypress:[null,"key"]},"scroll-lock":{keydown:[null,"key"],keypress:[null,"key"],keyup:[null,"key"]},print:{},"function":{keydown:[null,"key"],keypress:[null,
"key"],keyup:[null,"key"]},"\r":{keydown:[null,"key"],keypress:[null,"key"],keyup:[null,"key"]}}};Syn.mouse.browsers={webkit:{right:{mousedown:{button:2,which:3},mouseup:{button:2,which:3},contextmenu:{button:2,which:3}},left:{mousedown:{button:0,which:1},mouseup:{button:0,which:1},click:{button:0,which:1}}},opera:{right:{mousedown:{button:2,which:3},mouseup:{button:2,which:3}},left:{mousedown:{button:0,which:1},mouseup:{button:0,which:1},click:{button:0,which:1}}},msie:{right:{mousedown:{button:2},
mouseup:{button:2},contextmenu:{button:0}},left:{mousedown:{button:1},mouseup:{button:1},click:{button:0}}},chrome:{right:{mousedown:{button:2,which:3},mouseup:{button:2,which:3},contextmenu:{button:2,which:3}},left:{mousedown:{button:0,which:1},mouseup:{button:0,which:1},click:{button:0,which:1}}},gecko:{left:{mousedown:{button:0,which:1},mouseup:{button:0,which:1},click:{button:0,which:1}},right:{mousedown:{button:2,which:3},mouseup:{button:2,which:3},contextmenu:{button:2,which:3}}}};Syn.key.browser=
function(){if(Syn.key.browsers[window.navigator.userAgent])return Syn.key.browsers[window.navigator.userAgent];for(var e in Syn.browser)if(Syn.browser[e]&&Syn.key.browsers[e])return Syn.key.browsers[e];return Syn.key.browsers.gecko}();Syn.mouse.browser=function(){if(Syn.mouse.browsers[window.navigator.userAgent])return Syn.mouse.browsers[window.navigator.userAgent];for(var e in Syn.browser)if(Syn.browser[e]&&Syn.mouse.browsers[e])return Syn.mouse.browsers[e];return Syn.mouse.browsers.gecko}()})(!0);
(function(){var e=Syn.helpers,j=Syn,m=function(a){if(void 0!==a.selectionStart)return document.activeElement&&document.activeElement!=a&&a.selectionStart==a.selectionEnd&&0==a.selectionStart?{start:a.value.length,end:a.value.length}:{start:a.selectionStart,end:a.selectionEnd};try{if("input"==a.nodeName.toLowerCase()){var b=e.getWindow(a).document.selection.createRange(),d=a.createTextRange();d.setEndPoint("EndToStart",b);var g=d.text.length;return{start:g,end:g+b.text.length}}var b=e.getWindow(a).document.selection.createRange(),
d=b.duplicate(),c=b.duplicate(),l=b.duplicate();c.collapse();l.collapse(!1);c.moveStart("character",-1);l.moveStart("character",-1);d.moveToElementText(a);d.setEndPoint("EndToEnd",b);var g=d.text.length-b.text.length,f=d.text.length;0!=g&&""==c.text&&(g+=2);0!=f&&""==l.text&&(f+=2);return{start:g,end:f}}catch(h){return{start:a.value.length,end:a.value.length}}},h=function(a){for(var a=e.getWindow(a).document,b=[],d=a.getElementsByTagName("*"),g=d.length,c=0;c<g;c++)Syn.isFocusable(d[c])&&d[c]!=a.documentElement&&
b.push(d[c]);return b};e.extend(Syn,{keycodes:{"\u0008":"8","\t":"9","\r":"13",shift:"16",ctrl:"17",alt:"18","pause-break":"19",caps:"20",escape:"27","num-lock":"144","scroll-lock":"145",print:"44","page-up":"33","page-down":"34",end:"35",home:"36",left:"37",up:"38",right:"39",down:"40",insert:"45","delete":"46"," ":"32","0":"48",1:"49",2:"50",3:"51",4:"52",5:"53",6:"54",7:"55",8:"56",9:"57",a:"65",b:"66",c:"67",d:"68",e:"69",f:"70",g:"71",h:"72",i:"73",j:"74",k:"75",l:"76",m:"77",n:"78",o:"79",p:"80",
q:"81",r:"82",s:"83",t:"84",u:"85",v:"86",w:"87",x:"88",y:"89",z:"90",num0:"96",num1:"97",num2:"98",num3:"99",num4:"100",num5:"101",num6:"102",num7:"103",num8:"104",num9:"105","*":"106","+":"107","-":"109",".":"110","/":"111",";":"186","=":"187",",":"188","-":"189",".":"190","/":"191","`":"192","[":"219","\\":"220","]":"221","'":"222","left window key":"91","right window key":"92","select key":"93",f1:"112",f2:"113",f3:"114",f4:"115",f5:"116",f6:"117",f7:"118",f8:"119",f9:"120",f10:"121",f11:"122",
f12:"123"},typeable:/input|textarea/i,selectText:function(a,b,d){if(a.setSelectionRange)d?(a.selectionStart=b,a.selectionEnd=d):(a.focus(),a.setSelectionRange(b,b));else if(a.createTextRange){var g=a.createTextRange();g.moveStart("character",b);g.moveEnd("character",(d||b)-a.value.length);g.select()}},getText:function(a){if(Syn.typeable.test(a.nodeName)){var b=m(a);return a.value.substring(b.start,b.end)}a=Syn.helpers.getWindow(a);return a.getSelection?a.getSelection().toString():a.document.getSelection?
a.document.getSelection().toString():a.document.selection.createRange().text},getSelection:m});e.extend(Syn.key,{data:function(a){if(j.key.browser[a])return j.key.browser[a];for(var b in j.key.kinds)if(-1<e.inArray(a,j.key.kinds[b]))return j.key.browser[b];return j.key.browser.character},isSpecial:function(a){for(var b=j.key.kinds.special,d=0;d<b.length;d++)if(Syn.keycodes[b[d]]==a)return b[d]},options:function(a,b){var d=Syn.key.data(a);if(!d[b])return null;var g=d[b][0],d=d[b][1],c={};c.keyCode=
"key"==d?Syn.keycodes[a]:"char"==d?a.charCodeAt(0):d;if("char"==g)c.charCode=a.charCodeAt(0);else if(null!==g)c.charCode=g;return c},kinds:{special:["shift","ctrl","alt","caps"],specialChars:["\u0008"],navigation:"page-up,page-down,end,home,left,up,right,down,insert,delete".split(","),"function":"f1,f2,f3,f4,f5,f6,f7,f8,f9,f10,f11,f12".split(",")},getDefault:function(a){if(Syn.key.defaults[a])return Syn.key.defaults[a];for(var b in Syn.key.kinds)if(-1<e.inArray(a,Syn.key.kinds[b])&&Syn.key.defaults[b])return Syn.key.defaults[b];
return Syn.key.defaults.character},defaults:{character:function(a,b,d,g,c){/num\d+/.test(d)&&(d=d.match(/\d+/)[0]);if(g||!j.support.keyCharacters&&Syn.typeable.test(this.nodeName))b=this.value,a=b.substr(0,c.start),c=b.substr(c.end),this.value=a+d+c,Syn.selectText(this,a.length+("\n"==d&&j.support.textareaCarriage?2:d.length))},c:function(a,b,d,g,c){Syn.key.ctrlKey?Syn.key.clipboard=Syn.getText(this):Syn.key.defaults.character.apply(this,arguments)},v:function(a,b,d,g,c){Syn.key.ctrlKey?Syn.key.defaults.character.call(this,
a,b,Syn.key.clipboard,!0,c):Syn.key.defaults.character.apply(this,arguments)},a:function(a,b,d,g,c){Syn.key.ctrlKey?Syn.selectText(this,0,this.value.length):Syn.key.defaults.character.apply(this,arguments)},home:function(){Syn.onParents(this,function(a){if(a.scrollHeight!=a.clientHeight)return a.scrollTop=0,!1})},end:function(){Syn.onParents(this,function(a){if(a.scrollHeight!=a.clientHeight)return a.scrollTop=a.scrollHeight,!1})},"page-down":function(){Syn.onParents(this,function(a){if(a.scrollHeight!=
a.clientHeight)return a.scrollTop+=a.clientHeight,!1})},"page-up":function(){Syn.onParents(this,function(a){if(a.scrollHeight!=a.clientHeight)return a.scrollTop-=a.clientHeight,!1})},"\u0008":function(a,b,d,g,c){if(!j.support.backspaceWorks&&Syn.typeable.test(this.nodeName))b=this.value,a=b.substr(0,c.start),b=b.substr(c.end),c.start==c.end&&0<c.start?(this.value=a.substring(0,a.length-1)+b,Syn.selectText(this,c.start-1)):(this.value=a+b,Syn.selectText(this,c.start))},"delete":function(a,b,d,g,c){if(!j.support.backspaceWorks&&
Syn.typeable.test(this.nodeName))b=this.value,a=b.substr(0,c.start),b=b.substr(c.end),this.value=c.start==c.end&&c.start<=this.value.length-1?a+b.substring(1):a+b,Syn.selectText(this,c.start)},"\r":function(a,b,d,g,c){d=this.nodeName.toLowerCase();!j.support.keypressSubmits&&"input"==d&&(g=Syn.closest(this,"form"))&&Syn.trigger("submit",{},g);!j.support.keyCharacters&&"textarea"==d&&Syn.key.defaults.character.call(this,a,b,"\n",void 0,c);!j.support.keypressOnAnchorClicks&&"a"==d&&Syn.trigger("click",
{},this)},"\t":function(){var a=h(this);Syn.tabIndex(this);var b=null,d=0,g;for(orders=[];d<a.length;d++)orders.push([a[d],d]);orders.sort(function(a,b){var g=b[0],d=Syn.tabIndex(a[0])||0,g=Syn.tabIndex(g)||0;return d==g?a[1]-b[1]:0==d?1:0==g?-1:d-g});for(d=0;d<orders.length;d++)g=orders[d][0],this==g&&(Syn.key.shiftKey?(b=orders[d-1][0])||(b=orders[a.length-1][0]):(b=orders[d+1][0])||(b=orders[0][0]));b||(b=void 0);b&&b.focus();return b},left:function(a,b,d,g,c){Syn.typeable.test(this.nodeName)&&
(Syn.key.shiftKey?Syn.selectText(this,0==c.start?0:c.start-1,c.end):Syn.selectText(this,0==c.start?0:c.start-1))},right:function(a,b,d,g,c){Syn.typeable.test(this.nodeName)&&(Syn.key.shiftKey?Syn.selectText(this,c.start,c.end+1>this.value.length?this.value.length:c.end+1):Syn.selectText(this,c.end+1>this.value.length?this.value.length:c.end+1))},up:function(){if(/select/i.test(this.nodeName))this.selectedIndex=this.selectedIndex?this.selectedIndex-1:0},down:function(){/select/i.test(this.nodeName)&&
(Syn.changeOnBlur(this,"selectedIndex",this.selectedIndex),this.selectedIndex+=1)},shift:function(){return null}}});e.extend(Syn.create,{keydown:{setup:function(a,b,d){-1!=e.inArray(b,Syn.key.kinds.special)&&(Syn.key[b+"Key"]=d)}},keypress:{setup:function(a,b,d){j.support.keyCharacters&&!j.support.keysOnNotFocused&&d.focus()}},keyup:{setup:function(a,b){-1!=e.inArray(b,Syn.key.kinds.special)&&(Syn.key[b+"Key"]=null)}},key:{options:function(a,b){b="object"!=typeof b?{character:b}:b;b=e.extend({},b);
b.character&&(e.extend(b,j.key.options(b.character,a)),delete b.character);return b=e.extend({ctrlKey:!!Syn.key.ctrlKey,altKey:!!Syn.key.altKey,shiftKey:!!Syn.key.shiftKey,metaKey:!!Syn.key.metaKey},b)},event:function(a,b,d){var g=e.getWindow(d).document||document;if(g.createEvent){var c;try{c=g.createEvent("KeyEvents"),c.initKeyEvent(a,!0,!0,window,b.ctrlKey,b.altKey,b.shiftKey,b.metaKey,b.keyCode,b.charCode)}catch(l){c=e.createBasicStandardEvent(a,b,g)}c.synthetic=!0}else try{c=e.createEventObject.apply(this,
arguments),e.extend(c,b)}catch(f){}return c}}});var i={enter:"\r",backspace:"\u0008",tab:"\t",space:" "};e.extend(Syn.init.prototype,{_key:function(a,b,d){if(/-up$/.test(a)&&-1!=e.inArray(a.replace("-up",""),Syn.key.kinds.special))Syn.trigger("keyup",a.replace("-up",""),b),d(!0,b);else{var g=Syn.typeable.test(b.nodeName)&&m(b),c=i[a]||a,l=Syn.trigger("keydown",c,b),a=Syn.key.getDefault,f=Syn.key.browser.prevent,h,j=Syn.key.options(c,"keypress");l?j?(l=Syn.trigger("keypress",j,b))&&(h=a(c).call(b,
j,e.getWindow(b),c,void 0,g)):h=a(c).call(b,j,e.getWindow(b),c,void 0,g):j&&-1==e.inArray("keypress",f.keydown)&&Syn.trigger("keypress",j,b);h&&h.nodeName&&(b=h);null!==h?setTimeout(function(){Syn.trigger("keyup",Syn.key.options(c,"keyup"),b);d(l,b)},1):d(l,b);return b}},_type:function(a,b,d){var g=a.match(/(\[[^\]]+\])|([^\[])/g),c=this,e=function(a,h){var i=g.shift();i?(h=h||b,1<i.length&&(i=i.substr(1,i.length-2)),c._key(i,h,e)):d(a,h)};e()}});(function(){if(document.body){var a=document.createElement("div"),
b,d,g,c;a.innerHTML="<form id='outer'><input name='checkbox' type='checkbox'/><input name='radio' type='radio' /><input type='submit' name='submitter'/><input type='input' name='inputter'/><input name='one'><input name='two'/><a href='#abc'></a><textarea>1\n2</textarea></form>";document.documentElement.appendChild(a);b=a.firstChild;d=b.getElementsByTagName("a")[0];g=b.getElementsByTagName("textarea")[0];c=b.childNodes[3];b.onsubmit=function(a){a.preventDefault&&a.preventDefault();j.support.keypressSubmits=
!0;return a.returnValue=!1};c.focus();Syn.trigger("keypress","\r",c);Syn.trigger("keypress","a",c);j.support.keyCharacters="a"==c.value;c.value="a";Syn.trigger("keypress","\u0008",c);j.support.backspaceWorks=""==c.value;c.onchange=function(){j.support.focusChanges=!0};c.focus();Syn.trigger("keypress","a",c);b.childNodes[5].focus();Syn.trigger("keypress","b",c);j.support.keysOnNotFocused="ab"==c.value;j.bind(d,"click",function(a){a.preventDefault&&a.preventDefault();j.support.keypressOnAnchorClicks=
!0;return a.returnValue=!1});Syn.trigger("keypress","\r",d);j.support.textareaCarriage=4==g.value.length;document.documentElement.removeChild(a);j.support.ready++}else setTimeout(arguments.callee,1)})()})(!0);
(function(){(function(){if(document.body){var a=document.createElement("div");document.body.appendChild(a);Syn.helpers.extend(a.style,{width:"100px",height:"10000px",backgroundColor:"blue",position:"absolute",top:"10px",left:"0px",zIndex:19999});document.body.scrollTop=11;if(document.elementFromPoint)document.elementFromPoint(3,1)==a?Syn.support.elementFromClient=!0:Syn.support.elementFromPage=!0,document.body.removeChild(a),document.body.scrollTop=0}else setTimeout(arguments.callee,1)})();var e=
function(a,b){var d=a.clientX,f=a.clientY,e=Syn.helpers.getWindow(b);if(Syn.support.elementFromPage)var h=Syn.helpers.scrollOffset(e),d=d+h.left,f=f+h.top;d=e.document.elementFromPoint?e.document.elementFromPoint(d,f):b;return d===e.document.documentElement&&(0>a.clientY||0>a.clientX)?b:d},j=function(a,b,d){var f=e(b,d);Syn.trigger(a,b,f||d);return f},m=function(a,b,d){var f=e(a,b);if(d!=f&&f&&d){var h=Syn.helpers.extend({},a);h.relatedTarget=f;Syn.trigger("mouseout",h,d);h.relatedTarget=d;Syn.trigger("mouseover",
h,f)}Syn.trigger("mousemove",a,f||b);return f},h=function(a,b,d,f,h){var i=new Date,j=b.clientX-a.clientX,u=b.clientY-a.clientY,n=Syn.helpers.getWindow(f),o=e(a,f),p=n.document.createElement("div"),s=0;move=function(){var e=new Date,t=Syn.helpers.scrollOffset(n),e=(0==s?0:e-i)/d,q={clientX:j*e+a.clientX,clientY:u*e+a.clientY};s++;1>e?(Syn.helpers.extend(p.style,{left:q.clientX+t.left+2+"px",top:q.clientY+t.top+2+"px"}),o=m(q,f,o),setTimeout(arguments.callee,15)):(o=m(b,f,o),n.document.body.removeChild(p),
h())};Syn.helpers.extend(p.style,{height:"5px",width:"5px",backgroundColor:"red",position:"absolute",zIndex:19999,fontSize:"1px"});n.document.body.appendChild(p);move()},i=function(a,b,d,f,e){j("mousedown",a,f);h(a,b,d,f,function(){j("mouseup",b,f);e()})},a=function(a){var a=Syn.jquery()(a),b=a.offset();return{pageX:b.left+a.width()/2,pageY:b.top+a.height()/2}},b=function(b,c,d){var f=/(\d+)[x ](\d+)/,e=/(\d+)X(\d+)/,h=/([+-]\d+)[xX ]([+-]\d+)/;"string"==typeof b&&h.test(b)&&d&&(d=a(d),b=b.match(h),
b={pageX:d.pageX+parseInt(b[1]),pageY:d.pageY+parseInt(b[2])});"string"==typeof b&&f.test(b)&&(b=b.match(f),b={pageX:parseInt(b[1]),pageY:parseInt(b[2])});"string"==typeof b&&e.test(b)&&(b=b.match(e),b={clientX:parseInt(b[1]),clientY:parseInt(b[2])});"string"==typeof b&&(b=Syn.jquery()(b,c.document)[0]);b.nodeName&&(b=a(b));b.pageX&&(c=Syn.helpers.scrollOffset(c),b={clientX:b.pageX-c.left,clientY:b.pageY-c.top});return b},d=function(a,b,d){if(0>a.clientY){var f=Syn.helpers.scrollOffset(d);Syn.helpers.scrollDimensions(d);
var e=f.top+a.clientY-100,h=e-f.top;0<e||(e=0,h=-f.top);a.clientY-=h;b.clientY-=h;Syn.helpers.scrollOffset(d,{top:e,left:f.left})}};Syn.helpers.extend(Syn.init.prototype,{_move:function(a,c,e){var f=Syn.helpers.getWindow(c),i=b(a.from||c,f,c),j=b(a.to||a,f,c);!1!==a.adjust&&d(i,j,f);h(i,j,a.duration||500,c,e)},_drag:function(a,c,e){var f=Syn.helpers.getWindow(c),h=b(a.from||c,f,c),j=b(a.to||a,f,c);!1!==a.adjust&&d(h,j,f);i(h,j,a.duration||500,c,e)}})})();