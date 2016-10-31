+function($){"use strict";function startAutoPlay(){$("[data-vc-tta-autoplay]").each(function(){$(this).vcTtaAutoPlay()})}var Plugin,TtaAutoPlay,old;Plugin=function(action,options){var args;return args=Array.prototype.slice.call(arguments,1),this.each(function(){var $this,data;$this=$(this),data=$this.data("vc.tta.autoplay"),data||(data=new TtaAutoPlay($this,$.extend(!0,{},TtaAutoPlay.DEFAULTS,$this.data("vc-tta-autoplay"),options)),$this.data("vc.tta.autoplay",data)),"string"==typeof action?data[action].apply(data,args):data.start(args)})},TtaAutoPlay=function($element,options){this.$element=$element,this.options=options},TtaAutoPlay.DEFAULTS={delay:5e3,pauseOnHover:!0,stopOnClick:!0},TtaAutoPlay.prototype.show=function(){this.$element.find("[data-vc-accordion]:eq(0)").vcAccordion("showNext",{changeHash:!1})},TtaAutoPlay.prototype.hasTimer=function(){return void 0!==this.$element.data("vc.tta.autoplay.timer")},TtaAutoPlay.prototype.setTimer=function(windowInterval){this.$element.data("vc.tta.autoplay.timer",windowInterval)},TtaAutoPlay.prototype.getTimer=function(){return this.$element.data("vc.tta.autoplay.timer")},TtaAutoPlay.prototype.deleteTimer=function(){this.$element.removeData("vc.tta.autoplay.timer")},TtaAutoPlay.prototype.start=function(){function stopHandler(e){e.preventDefault&&e.preventDefault(),that.hasTimer()&&Plugin.call($this,"stop")}function hoverHandler(e){e.preventDefault&&e.preventDefault(),that.hasTimer()&&Plugin.call($this,"mouseleave"===e.type?"resume":"pause")}var $this,that;$this=this.$element,that=this,this.hasTimer()||(this.setTimer(window.setInterval(this.show.bind(this),this.options.delay)),this.options.stopOnClick&&$this.on("click.vc.tta.autoplay.data-api","[data-vc-accordion]",stopHandler),this.options.pauseOnHover&&$this.hover(hoverHandler))},TtaAutoPlay.prototype.resume=function(){this.hasTimer()&&this.setTimer(window.setInterval(this.show.bind(this),this.options.delay))},TtaAutoPlay.prototype.stop=function(){this.pause(),this.deleteTimer(),this.$element.off("click.vc.tta.autoplay.data-api mouseenter mouseleave")},TtaAutoPlay.prototype.pause=function(){var timer;timer=this.getTimer(),void 0!==timer&&window.clearInterval(timer)},old=$.fn.vcTtaAutoPlay,$.fn.vcTtaAutoPlay=Plugin,$.fn.vcTtaAutoPlay.Constructor=TtaAutoPlay,$.fn.vcTtaAutoPlay.noConflict=function(){return $.fn.vcTtaAutoPlay=old,this},$(document).ready(startAutoPlay)}(window.jQuery);;+function($){"use strict";function Plugin(action,options){var args;return args=Array.prototype.slice.call(arguments,1),this.each(function(){var $this,data;$this=$(this),data=$this.data("vc.tabs"),data||(data=new Tabs($this,$.extend(!0,{},options)),$this.data("vc.tabs",data)),"string"==typeof action&&data[action].apply(data,args)})}var Tabs,old,clickHandler,changeHandler;Tabs=function(element,options){this.$element=$(element),this.activeClass="vc_active",this.tabSelector="[data-vc-tab]",this.useCacheFlag=void 0,this.$target=void 0,this.selector=void 0,this.$targetTab=void 0,this.$relatedAccordion=void 0,this.$container=void 0},Tabs.prototype.isCacheUsed=function(){var useCache,that;return that=this,useCache=function(){return!1!==that.$element.data("vcUseCache")},"undefined"==typeof this.useCacheFlag&&(this.useCacheFlag=useCache()),this.useCacheFlag},Tabs.prototype.getContainer=function(){return this.isCacheUsed()?("undefined"==typeof this.$container&&(this.$container=this.findContainer()),this.$container):this.findContainer()},Tabs.prototype.findContainer=function(){var $container;return $container=this.$element.closest(this.$element.data("vcContainer")),$container.length||($container=$("body")),$container},Tabs.prototype.getContainerAccordion=function(){return this.getContainer().find("[data-vc-accordion]")},Tabs.prototype.getSelector=function(){var findSelector,$this;return $this=this.$element,findSelector=function(){var selector;return selector=$this.data("vcTarget"),selector||(selector=$this.attr("href")),selector},this.isCacheUsed()?("undefined"==typeof this.selector&&(this.selector=findSelector()),this.selector):findSelector()},Tabs.prototype.getTarget=function(){var selector;return selector=this.getSelector(),this.isCacheUsed()?("undefined"==typeof this.$target&&(this.$target=this.getContainer().find(selector)),this.$target):this.getContainer().find(selector)},Tabs.prototype.getRelatedAccordion=function(){var tab,filterElements;return tab=this,filterElements=function(){var $elements;return $elements=tab.getContainerAccordion().filter(function(){var $that,accordion;return $that=$(this),accordion=$that.data("vc.accordion"),"undefined"==typeof accordion&&($that.vcAccordion(),accordion=$that.data("vc.accordion")),tab.getSelector()===accordion.getSelector()}),$elements.length?$elements:void 0},this.isCacheUsed()?("undefined"==typeof this.$relatedAccordion&&(this.$relatedAccordion=filterElements()),this.$relatedAccordion):filterElements()},Tabs.prototype.triggerEvent=function(event){var $event;"string"==typeof event&&($event=$.Event(event),this.$element.trigger($event))},Tabs.prototype.getTargetTab=function(){var $this;return $this=this.$element,this.isCacheUsed()?("undefined"==typeof this.$targetTab&&(this.$targetTab=$this.closest(this.tabSelector)),this.$targetTab):$this.closest(this.tabSelector)},Tabs.prototype.tabClick=function(){this.getRelatedAccordion().trigger("click")},Tabs.prototype.show=function(){this.getTargetTab().hasClass(this.activeClass)||(this.triggerEvent("show.vc.tab"),this.getTargetTab().addClass(this.activeClass))},Tabs.prototype.hide=function(){this.getTargetTab().hasClass(this.activeClass)&&(this.triggerEvent("hide.vc.tab"),this.getTargetTab().removeClass(this.activeClass))},old=$.fn.vcTabs,$.fn.vcTabs=Plugin,$.fn.vcTabs.Constructor=Tabs,$.fn.vcTabs.noConflict=function(){return $.fn.vcTabs=old,this},clickHandler=function(e){var $this;$this=$(this),e.preventDefault(),Plugin.call($this,"tabClick")},changeHandler=function(e){var caller;caller=$(e.target).data("vc.accordion"),"undefined"==typeof caller.getRelatedTab&&(caller.getRelatedTab=function(){var findTargets;return findTargets=function(){var $targets;return $targets=caller.getContainer().find("[data-vc-tabs]").filter(function(){var $this,tab;return $this=$(this),tab=$this.data("vc.accordion"),"undefined"==typeof tab&&$this.vcAccordion(),tab=$this.data("vc.accordion"),tab.getSelector()===caller.getSelector()})},caller.isCacheUsed()?("undefined"==typeof caller.relatedTab&&(caller.relatedTab=findTargets()),caller.relatedTab):findTargets()}),Plugin.call(caller.getRelatedTab(),e.type)},$(document).on("click.vc.tabs.data-api","[data-vc-tabs]",clickHandler),$(document).on("show.vc.accordion hide.vc.accordion",changeHandler)}(window.jQuery);