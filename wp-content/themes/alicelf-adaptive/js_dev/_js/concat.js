/* ========================================================================
 * Bootstrap: alert.js v3.2.0
 * http://getbootstrap.com/javascript/#alerts
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // ALERT CLASS DEFINITION
  // ======================

  var dismiss = '[data-dismiss="alert"]'
  var Alert   = function (el) {
    $(el).on('click', dismiss, this.close)
  }

  Alert.VERSION = '3.2.0'

  Alert.prototype.close = function (e) {
    var $this    = $(this)
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = $(selector)

    if (e) e.preventDefault()

    if (!$parent.length) {
      $parent = $this.hasClass('alert') ? $this : $this.parent()
    }

    $parent.trigger(e = $.Event('close.bs.alert'))

    if (e.isDefaultPrevented()) return

    $parent.removeClass('in')

    function removeElement() {
      // detach from parent, fire event then clean up data
      $parent.detach().trigger('closed.bs.alert').remove()
    }

    $.support.transition && $parent.hasClass('fade') ?
      $parent
        .one('bsTransitionEnd', removeElement)
        .emulateTransitionEnd(150) :
      removeElement()
  }


  // ALERT PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.alert')

      if (!data) $this.data('bs.alert', (data = new Alert(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.alert

  $.fn.alert             = Plugin
  $.fn.alert.Constructor = Alert


  // ALERT NO CONFLICT
  // =================

  $.fn.alert.noConflict = function () {
    $.fn.alert = old
    return this
  }


  // ALERT DATA-API
  // ==============

  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

}(jQuery);

/* ========================================================================
 * Bootstrap: carousel.js v3.2.0
 * http://getbootstrap.com/javascript/#carousel
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CAROUSEL CLASS DEFINITION
  // =========================

  var Carousel = function (element, options) {
    this.$element    = $(element).on('keydown.bs.carousel', $.proxy(this.keydown, this))
    this.$indicators = this.$element.find('.carousel-indicators')
    this.options     = options
    this.paused      =
    this.sliding     =
    this.interval    =
    this.$active     =
    this.$items      = null

    this.options.pause == 'hover' && this.$element
      .on('mouseenter.bs.carousel', $.proxy(this.pause, this))
      .on('mouseleave.bs.carousel', $.proxy(this.cycle, this))
  }

  Carousel.VERSION  = '3.2.0'

  Carousel.DEFAULTS = {
    interval: 5000,
    pause: 'hover',
    wrap: true
  }

  Carousel.prototype.keydown = function (e) {
    switch (e.which) {
      case 37: this.prev(); break
      case 39: this.next(); break
      default: return
    }

    e.preventDefault()
  }

  Carousel.prototype.cycle = function (e) {
    e || (this.paused = false)

    this.interval && clearInterval(this.interval)

    this.options.interval
      && !this.paused
      && (this.interval = setInterval($.proxy(this.next, this), this.options.interval))

    return this
  }

  Carousel.prototype.getItemIndex = function (item) {
    this.$items = item.parent().children('.item')
    return this.$items.index(item || this.$active)
  }

  Carousel.prototype.to = function (pos) {
    var that        = this
    var activeIndex = this.getItemIndex(this.$active = this.$element.find('.item.active'))

    if (pos > (this.$items.length - 1) || pos < 0) return

    if (this.sliding)       return this.$element.one('slid.bs.carousel', function () { that.to(pos) }) // yes, "slid"
    if (activeIndex == pos) return this.pause().cycle()

    return this.slide(pos > activeIndex ? 'next' : 'prev', $(this.$items[pos]))
  }

  Carousel.prototype.pause = function (e) {
    e || (this.paused = true)

    if (this.$element.find('.next, .prev').length && $.support.transition) {
      this.$element.trigger($.support.transition.end)
      this.cycle(true)
    }

    this.interval = clearInterval(this.interval)

    return this
  }

  Carousel.prototype.next = function () {
    if (this.sliding) return
    return this.slide('next')
  }

  Carousel.prototype.prev = function () {
    if (this.sliding) return
    return this.slide('prev')
  }

  Carousel.prototype.slide = function (type, next) {
    var $active   = this.$element.find('.item.active')
    var $next     = next || $active[type]()
    var isCycling = this.interval
    var direction = type == 'next' ? 'left' : 'right'
    var fallback  = type == 'next' ? 'first' : 'last'
    var that      = this

    if (!$next.length) {
      if (!this.options.wrap) return
      $next = this.$element.find('.item')[fallback]()
    }

    if ($next.hasClass('active')) return (this.sliding = false)

    var relatedTarget = $next[0]
    var slideEvent = $.Event('slide.bs.carousel', {
      relatedTarget: relatedTarget,
      direction: direction
    })
    this.$element.trigger(slideEvent)
    if (slideEvent.isDefaultPrevented()) return

    this.sliding = true

    isCycling && this.pause()

    if (this.$indicators.length) {
      this.$indicators.find('.active').removeClass('active')
      var $nextIndicator = $(this.$indicators.children()[this.getItemIndex($next)])
      $nextIndicator && $nextIndicator.addClass('active')
    }

    var slidEvent = $.Event('slid.bs.carousel', { relatedTarget: relatedTarget, direction: direction }) // yes, "slid"
    if ($.support.transition && this.$element.hasClass('slide')) {
      $next.addClass(type)
      $next[0].offsetWidth // force reflow
      $active.addClass(direction)
      $next.addClass(direction)
      $active
        .one('bsTransitionEnd', function () {
          $next.removeClass([type, direction].join(' ')).addClass('active')
          $active.removeClass(['active', direction].join(' '))
          that.sliding = false
          setTimeout(function () {
            that.$element.trigger(slidEvent)
          }, 0)
        })
        .emulateTransitionEnd($active.css('transition-duration').slice(0, -1) * 1000)
    } else {
      $active.removeClass('active')
      $next.addClass('active')
      this.sliding = false
      this.$element.trigger(slidEvent)
    }

    isCycling && this.cycle()

    return this
  }


  // CAROUSEL PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.carousel')
      var options = $.extend({}, Carousel.DEFAULTS, $this.data(), typeof option == 'object' && option)
      var action  = typeof option == 'string' ? option : options.slide

      if (!data) $this.data('bs.carousel', (data = new Carousel(this, options)))
      if (typeof option == 'number') data.to(option)
      else if (action) data[action]()
      else if (options.interval) data.pause().cycle()
    })
  }

  var old = $.fn.carousel

  $.fn.carousel             = Plugin
  $.fn.carousel.Constructor = Carousel


  // CAROUSEL NO CONFLICT
  // ====================

  $.fn.carousel.noConflict = function () {
    $.fn.carousel = old
    return this
  }


  // CAROUSEL DATA-API
  // =================

  $(document).on('click.bs.carousel.data-api', '[data-slide], [data-slide-to]', function (e) {
    var href
    var $this   = $(this)
    var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) // strip for ie7
    if (!$target.hasClass('carousel')) return
    var options = $.extend({}, $target.data(), $this.data())
    var slideIndex = $this.attr('data-slide-to')
    if (slideIndex) options.interval = false

    Plugin.call($target, options)

    if (slideIndex) {
      $target.data('bs.carousel').to(slideIndex)
    }

    e.preventDefault()
  })

  $(window).on('load', function () {
    $('[data-ride="carousel"]').each(function () {
      var $carousel = $(this)
      Plugin.call($carousel, $carousel.data())
    })
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: collapse.js v3.2.0
 * http://getbootstrap.com/javascript/#collapse
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // COLLAPSE PUBLIC CLASS DEFINITION
  // ================================

  var Collapse = function (element, options) {
    this.$element      = $(element)
    this.options       = $.extend({}, Collapse.DEFAULTS, options)
    this.transitioning = null

    if (this.options.parent) this.$parent = $(this.options.parent)
    if (this.options.toggle) this.toggle()
  }

  Collapse.VERSION  = '3.2.0'

  Collapse.DEFAULTS = {
    toggle: true
  }

  Collapse.prototype.dimension = function () {
    var hasWidth = this.$element.hasClass('width')
    return hasWidth ? 'width' : 'height'
  }

  Collapse.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('in')) return

    var startEvent = $.Event('show.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var actives = this.$parent && this.$parent.find('> .panel > .in')

    if (actives && actives.length) {
      var hasData = actives.data('bs.collapse')
      if (hasData && hasData.transitioning) return
      Plugin.call(actives, 'hide')
      hasData || actives.data('bs.collapse', null)
    }

    var dimension = this.dimension()

    this.$element
      .removeClass('collapse')
      .addClass('collapsing')[dimension](0)

    this.transitioning = 1

    var complete = function () {
      this.$element
        .removeClass('collapsing')
        .addClass('collapse in')[dimension]('')
      this.transitioning = 0
      this.$element
        .trigger('shown.bs.collapse')
    }

    if (!$.support.transition) return complete.call(this)

    var scrollSize = $.camelCase(['scroll', dimension].join('-'))

    this.$element
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(350)[dimension](this.$element[0][scrollSize])
  }

  Collapse.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('in')) return

    var startEvent = $.Event('hide.bs.collapse')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented()) return

    var dimension = this.dimension()

    this.$element[dimension](this.$element[dimension]())[0].offsetHeight

    this.$element
      .addClass('collapsing')
      .removeClass('collapse')
      .removeClass('in')

    this.transitioning = 1

    var complete = function () {
      this.transitioning = 0
      this.$element
        .trigger('hidden.bs.collapse')
        .removeClass('collapsing')
        .addClass('collapse')
    }

    if (!$.support.transition) return complete.call(this)

    this.$element
      [dimension](0)
      .one('bsTransitionEnd', $.proxy(complete, this))
      .emulateTransitionEnd(350)
  }

  Collapse.prototype.toggle = function () {
    this[this.$element.hasClass('in') ? 'hide' : 'show']()
  }


  // COLLAPSE PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.collapse')
      var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

      if (!data && options.toggle && option == 'show') option = !option
      if (!data) $this.data('bs.collapse', (data = new Collapse(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.collapse

  $.fn.collapse             = Plugin
  $.fn.collapse.Constructor = Collapse


  // COLLAPSE NO CONFLICT
  // ====================

  $.fn.collapse.noConflict = function () {
    $.fn.collapse = old
    return this
  }


  // COLLAPSE DATA-API
  // =================

  $(document).on('click.bs.collapse.data-api', '[data-toggle="collapse"]', function (e) {
    var href
    var $this   = $(this)
    var target  = $this.attr('data-target')
        || e.preventDefault()
        || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') // strip for ie7
    var $target = $(target)
    var data    = $target.data('bs.collapse')
    var option  = data ? 'toggle' : $this.data()
    var parent  = $this.attr('data-parent')
    var $parent = parent && $(parent)

    if (!data || !data.transitioning) {
      if ($parent) $parent.find('[data-toggle="collapse"][data-parent="' + parent + '"]').not($this).addClass('collapsed')
      $this[$target.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')
    }

    Plugin.call($target, option)
  })

}(jQuery);

/* ========================================================================
 * Bootstrap: dropdown.js v3.2.0
 * http://getbootstrap.com/javascript/#dropdowns
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // DROPDOWN CLASS DEFINITION
  // =========================

  var backdrop = '.dropdown-backdrop'
  var toggle   = '[data-toggle="dropdown"]'
  var Dropdown = function (element) {
    $(element).on('click.bs.dropdown', this.toggle)
  }

  Dropdown.VERSION = '3.2.0'

  Dropdown.prototype.toggle = function (e) {
    var $this = $(this)

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    clearMenus()

    if (!isActive) {
      if ('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
        // if mobile we use a backdrop because click events don't delegate
        $('<div class="dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
      }

      var relatedTarget = { relatedTarget: this }
      $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget))

      if (e.isDefaultPrevented()) return

      $this.trigger('focus')

      $parent
        .toggleClass('open')
        .trigger('shown.bs.dropdown', relatedTarget)
    }

    return false
  }

  Dropdown.prototype.keydown = function (e) {
    if (!/(38|40|27)/.test(e.keyCode)) return

    var $this = $(this)

    e.preventDefault()
    e.stopPropagation()

    if ($this.is('.disabled, :disabled')) return

    var $parent  = getParent($this)
    var isActive = $parent.hasClass('open')

    if (!isActive || (isActive && e.keyCode == 27)) {
      if (e.which == 27) $parent.find(toggle).trigger('focus')
      return $this.trigger('click')
    }

    var desc = ' li:not(.divider):visible a'
    var $items = $parent.find('[role="menu"]' + desc + ', [role="listbox"]' + desc)

    if (!$items.length) return

    var index = $items.index($items.filter(':focus'))

    if (e.keyCode == 38 && index > 0)                 index--                        // up
    if (e.keyCode == 40 && index < $items.length - 1) index++                        // down
    if (!~index)                                      index = 0

    $items.eq(index).trigger('focus')
  }

  function clearMenus(e) {
    if (e && e.which === 3) return
    $(backdrop).remove()
    $(toggle).each(function () {
      var $parent = getParent($(this))
      var relatedTarget = { relatedTarget: this }
      if (!$parent.hasClass('open')) return
      $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget))
      if (e.isDefaultPrevented()) return
      $parent.removeClass('open').trigger('hidden.bs.dropdown', relatedTarget)
    })
  }

  function getParent($this) {
    var selector = $this.attr('data-target')

    if (!selector) {
      selector = $this.attr('href')
      selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
    }

    var $parent = selector && $(selector)

    return $parent && $parent.length ? $parent : $this.parent()
  }


  // DROPDOWN PLUGIN DEFINITION
  // ==========================

  function Plugin(option) {
    return this.each(function () {
      var $this = $(this)
      var data  = $this.data('bs.dropdown')

      if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)))
      if (typeof option == 'string') data[option].call($this)
    })
  }

  var old = $.fn.dropdown

  $.fn.dropdown             = Plugin
  $.fn.dropdown.Constructor = Dropdown


  // DROPDOWN NO CONFLICT
  // ====================

  $.fn.dropdown.noConflict = function () {
    $.fn.dropdown = old
    return this
  }


  // APPLY TO STANDARD DROPDOWN ELEMENTS
  // ===================================

  $(document)
    .on('click.bs.dropdown.data-api', clearMenus)
    .on('click.bs.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
    .on('click.bs.dropdown.data-api', toggle, Dropdown.prototype.toggle)
    .on('keydown.bs.dropdown.data-api', toggle + ', [role="menu"], [role="listbox"]', Dropdown.prototype.keydown)

}(jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.2.0
 * http://getbootstrap.com/javascript/#transitions
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
  // ============================================================

  function transitionEnd() {
    var el = document.createElement('bootstrap')

    var transEndEventNames = {
      WebkitTransition : 'webkitTransitionEnd',
      MozTransition    : 'transitionend',
      OTransition      : 'oTransitionEnd otransitionend',
      transition       : 'transitionend'
    }

    for (var name in transEndEventNames) {
      if (el.style[name] !== undefined) {
        return { end: transEndEventNames[name] }
      }
    }

    return false // explicit for ie8 (  ._.)
  }

  // http://blog.alexmaccaw.com/css-transitions
  $.fn.emulateTransitionEnd = function (duration) {
    var called = false
    var $el = this
    $(this).one('bsTransitionEnd', function () { called = true })
    var callback = function () { if (!called) $($el).trigger($.support.transition.end) }
    setTimeout(callback, duration)
    return this
  }

  $(function () {
    $.support.transition = transitionEnd()

    if (!$.support.transition) return

    $.event.special.bsTransitionEnd = {
      bindType: $.support.transition.end,
      delegateType: $.support.transition.end,
      handle: function (e) {
        if ($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
      }
    }
  })

}(jQuery);

/** smooth-scroll v5.3.6, by Chris Ferdinandi | http://github.com/cferdinandi/smooth-scroll | Licensed under MIT: http://gomakethings.com/mit/ */
!function(e,t){"function"==typeof define&&define.amd?define([],t(e)):"object"==typeof exports?module.exports=t(e):e.smoothScroll=t(e)}(this,function(e){"use strict";var t,n,o,r,a={},u=!!document.querySelector&&!!e.addEventListener,c={speed:500,easing:"easeInOutCubic",offset:0,updateURL:!0,callbackBefore:function(){},callbackAfter:function(){}},i=function(e,t,n){if("[object Object]"===Object.prototype.toString.call(e))for(var o in e)Object.prototype.hasOwnProperty.call(e,o)&&t.call(n,e[o],o,e);else for(var r=0,a=e.length;a>r;r++)t.call(n,e[r],r,e)},l=function(e,t){var n={};return i(e,function(t,o){n[o]=e[o]}),i(t,function(e,o){n[o]=t[o]}),n},s=function(e,t){for(var n=t.charAt(0);e&&e!==document;e=e.parentNode)if("."===n){if(e.classList.contains(t.substr(1)))return e}else if("#"===n){if(e.id===t.substr(1))return e}else if("["===n&&e.hasAttribute(t.substr(1,t.length-2)))return e;return!1},f=function(e){return Math.max(e.scrollHeight,e.offsetHeight,e.clientHeight)},d=function(e){for(var t,n=String(e),o=n.length,r=-1,a="",u=n.charCodeAt(0);++r<o;){if(t=n.charCodeAt(r),0===t)throw new InvalidCharacterError("Invalid character: the input contains U+0000.");a+=t>=1&&31>=t||127==t||0===r&&t>=48&&57>=t||1===r&&t>=48&&57>=t&&45===u?"\\"+t.toString(16)+" ":t>=128||45===t||95===t||t>=48&&57>=t||t>=65&&90>=t||t>=97&&122>=t?n.charAt(r):"\\"+n.charAt(r)}return a},h=function(e,t){var n;return"easeInQuad"===e&&(n=t*t),"easeOutQuad"===e&&(n=t*(2-t)),"easeInOutQuad"===e&&(n=.5>t?2*t*t:-1+(4-2*t)*t),"easeInCubic"===e&&(n=t*t*t),"easeOutCubic"===e&&(n=--t*t*t+1),"easeInOutCubic"===e&&(n=.5>t?4*t*t*t:(t-1)*(2*t-2)*(2*t-2)+1),"easeInQuart"===e&&(n=t*t*t*t),"easeOutQuart"===e&&(n=1- --t*t*t*t),"easeInOutQuart"===e&&(n=.5>t?8*t*t*t*t:1-8*--t*t*t*t),"easeInQuint"===e&&(n=t*t*t*t*t),"easeOutQuint"===e&&(n=1+--t*t*t*t*t),"easeInOutQuint"===e&&(n=.5>t?16*t*t*t*t*t:1+16*--t*t*t*t*t),n||t},m=function(e,t,n){var o=0;if(e.offsetParent)do o+=e.offsetTop,e=e.offsetParent;while(e);return o=o-t-n,o>=0?o:0},p=function(){return Math.max(document.body.scrollHeight,document.documentElement.scrollHeight,document.body.offsetHeight,document.documentElement.offsetHeight,document.body.clientHeight,document.documentElement.clientHeight)},v=function(e){return e&&"object"==typeof JSON&&"function"==typeof JSON.parse?JSON.parse(e):{}},g=function(t,n){history.pushState&&(n||"true"===n)&&history.pushState(null,null,[e.location.protocol,"//",e.location.host,e.location.pathname,e.location.search,t].join(""))},b=function(e){return null===e?0:f(e)+e.offsetTop};a.animateScroll=function(t,n,a){var u=l(u||c,a||{}),i=v(t?t.getAttribute("data-options"):null);u=l(u,i),n="#"+d(n.substr(1));var s="#"===n?document.documentElement:document.querySelector(n),f=e.pageYOffset;o||(o=document.querySelector("[data-scroll-header]")),r||(r=b(o));var O,y,I,S=m(s,r,parseInt(u.offset,10)),E=S-f,H=p(),A=0;g(n,u.updateURL);var L=function(o,r,a){var c=e.pageYOffset;(o==r||c==r||e.innerHeight+c>=H)&&(clearInterval(a),s.focus(),u.callbackAfter(t,n))},Q=function(){A+=16,y=A/parseInt(u.speed,10),y=y>1?1:y,I=f+E*h(u.easing,y),e.scrollTo(0,Math.floor(I)),L(I,S,O)},C=function(){u.callbackBefore(t,n),O=setInterval(Q,16)};0===e.pageYOffset&&e.scrollTo(0,0),C()};var O=function(e){var n=s(e.target,"[data-scroll]");n&&"a"===n.tagName.toLowerCase()&&(e.preventDefault(),a.animateScroll(n,n.hash,t))},y=function(){n||(n=setTimeout(function(){n=null,r=b(o)},66))};return a.destroy=function(){t&&(document.removeEventListener("click",O,!1),e.removeEventListener("resize",y,!1),t=null,n=null,o=null,r=null)},a.init=function(n){u&&(a.destroy(),t=l(c,n||{}),o=document.querySelector("[data-scroll-header]"),r=b(o),document.addEventListener("click",O,!1),o&&e.addEventListener("resize",y,!1))},a});
(function(l,e){"object"===typeof exports?e(exports):"function"===typeof define&&define.amd?define(["exports"],e):e(l)})(this,function(l){function e(a){this._targetElement="undefined"!=typeof a.length?a:[a];"undefined"===typeof window._progressjsId&&(window._progressjsId=1);"undefined"===typeof window._progressjsIntervals&&(window._progressjsIntervals={});this._options={theme:"blue",overlayMode:!1,considerTransition:!0}}function m(a,c){var d=this;100<=c&&(c=100);a.hasAttribute("data-progressjs")&&
setTimeout(function(){"undefined"!=typeof d._onProgressCallback&&d._onProgressCallback.call(d,a,c);var b=h(a);b.style.width=parseInt(c)+"%";var b=b.querySelector(".progressjs-percent"),g=parseInt(b.innerHTML.replace("%","")),e=parseInt(c),j=function(a,b,c){var d=Math.abs(b-c);3>d?k=30:20>d?k=20:intervanIn=1;0!=b-c&&(a.innerHTML=(f?++b:--b)+"%",setTimeout(function(){j(a,b,c)},k))},f=!0;g>e&&(f=!1);var k=10;j(b,g,e)},50)}function h(a){a=parseInt(a.getAttribute("data-progressjs"));return document.querySelector('.progressjs-container > .progressjs-progress[data-progressjs="'+
a+'"] > .progressjs-inner')}function p(a){for(var c=0,d=this._targetElement.length;c<d;c++){var b=this._targetElement[c];if(b.hasAttribute("data-progressjs")){var g=h(b);(g=parseInt(g.style.width.replace("%","")))&&m.call(this,b,g+(a||1))}}}function q(){var a,c=document.createElement("fakeelement"),d={transition:"transitionend",OTransition:"oTransitionEnd",MozTransition:"transitionend",WebkitTransition:"webkitTransitionEnd"};for(a in d)if(void 0!==c.style[a])return d[a]}var n=function(a){if("object"===
typeof a)return new e(a);if("string"===typeof a){if(a=document.querySelectorAll(a))return new e(a);throw Error("There is no element with given selector.");}return new e(document.body)};n.version="0.1.0";n.fn=e.prototype={clone:function(){return new e(this)},setOption:function(a,c){this._options[a]=c;return this},setOptions:function(a){var c=this._options,d={},b;for(b in c)d[b]=c[b];for(b in a)d[b]=a[b];this._options=d;return this},start:function(){"undefined"!=typeof this._onBeforeStartCallback&&
this._onBeforeStartCallback.call(this);if(!document.querySelector(".progressjs-container")){var a=document.createElement("div");a.className="progressjs-container";document.body.appendChild(a)}for(var a=0,c=this._targetElement.length;a<c;a++){var d=this._targetElement[a];if(!d.hasAttribute("data-progressjs")){var b=d,g,e,j;"body"===b.tagName.toLowerCase()?(g=b.clientWidth,e=b.clientHeight):(g=b.offsetWidth,e=b.offsetHeight);for(var f=j=0;b&&!isNaN(b.offsetLeft)&&!isNaN(b.offsetTop);)j+=b.offsetLeft,
f+=b.offsetTop,b=b.offsetParent;b=f;d.setAttribute("data-progressjs",window._progressjsId);f=document.createElement("div");f.className="progressjs-progress progressjs-theme-"+this._options.theme;f.style.position="body"===d.tagName.toLowerCase()?"fixed":"absolute";f.setAttribute("data-progressjs",window._progressjsId);var k=document.createElement("div");k.className="progressjs-inner";var h=document.createElement("div");h.className="progressjs-percent";h.innerHTML="1%";k.appendChild(h);this._options.overlayMode&&
"body"===d.tagName.toLowerCase()?(f.style.left=0,f.style.right=0,f.style.top=0,f.style.bottom=0):(f.style.left=j+"px",f.style.top=b+"px",f.style.width=g+"px",this._options.overlayMode&&(f.style.height=e+"px"));f.appendChild(k);document.querySelector(".progressjs-container").appendChild(f);m(d,1);++window._progressjsId}}return this},set:function(a){for(var c=0,d=this._targetElement.length;c<d;c++)m.call(this,this._targetElement[c],a);return this},increase:function(a){p.call(this,a);return this},autoIncrease:function(a,
c){var d=this,b=parseInt(this._targetElement[0].getAttribute("data-progressjs"));"undefined"!=typeof window._progressjsIntervals[b]&&clearInterval(window._progressjsIntervals[b]);window._progressjsIntervals[b]=setInterval(function(){p.call(d,a)},c);return this},end:function(){a:{"undefined"!=typeof this._onBeforeEndCallback&&(!0===this._options.considerTransition?h(this._targetElement[0]).addEventListener(q(),this._onBeforeEndCallback,!1):this._onBeforeEndCallback.call(this));for(var a=parseInt(this._targetElement[0].getAttribute("data-progressjs")),
c=0,d=this._targetElement.length;c<d;c++){var b=this._targetElement[c],e=h(b);if(!e)break a;var l=1;100>parseInt(e.style.width.replace("%",""))&&(m.call(this,b,100),l=500);(function(a,b){setTimeout(function(){a.parentNode.className+=" progressjs-end";setTimeout(function(){a.parentNode.parentNode.removeChild(a.parentNode);b.removeAttribute("data-progressjs")},1E3)},l)})(e,b)}if(window._progressjsIntervals[a])try{clearInterval(window._progressjsIntervals[a]),window._progressjsIntervals[a]=null,delete window._progressjsIntervals[a]}catch(j){}}return this},
onbeforeend:function(a){if("function"===typeof a)this._onBeforeEndCallback=a;else throw Error("Provided callback for onbeforeend was not a function");return this},onbeforestart:function(a){if("function"===typeof a)this._onBeforeStartCallback=a;else throw Error("Provided callback for onbeforestart was not a function");return this},onprogress:function(a){if("function"===typeof a)this._onProgressCallback=a;else throw Error("Provided callback for onprogress was not a function");return this}};return l.progressJs=
n});

/**
 * Ajax dynamic theme script
 */

var SITE_URL = aa_ajax_var.site_url,
		AJAXURL = aa_ajax_var.ajax_url,
		THEME_URI = aa_ajax_var.template_uri,
		IMG_DIR = THEME_URI + '/img/';

jQuery(document).ready(function($) {

	var Loaders = {
		bouncingAbsolute : '<div id="loader-absolute" class="loader-absolute"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		bouncingStatic : '<div id="loader-static" class="loader-static"><div class="preview-area"><div class="spinner-jx"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>',
		infiniteSpinner : '<div class="loader-backdrop"><div class="loader-infinite-spinner"><div class="lt"></div><div class="rt"></div><div class="lb"></div><div class="rb"></div></div></div>',
		svgLoader : '<img id="svg-loader-process" src="' + THEME_URI + '/svg/loader_svg.svg" width="40" alt="loadersvg"/>'

	};

	(function mainThemeAjaxScope() {

		var successMessage ="Your message has been successfully sended",
				errorMessage = "Fill Correct all required fields!",
				wrongCaptcha = "Fill Correct Captha",
				unknownError ="Something Wrong, try again later",
				alertHolder = function(itemClass, sendedMessage) {
					return "<div class='alert alert-" + itemClass + "'>" +
						"<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
						"<span aria-hidden='true'>&times;</span>" +
						"</button><h3 class='text-center'>" + sendedMessage + "</h3></div>"
				};


		var formContactProcess = function(act) {

			$('form.alice-ajax-contact-form').on('submit', function() {
				var that = $(this),
					method = that.attr('method'),
					formData = {
						action : act
					};

				that.find('[name]').each(function() {
					formData[$(this).attr('name')] = $(this).val();
				});

				//FormProcessStart
				$.ajax({
					url       : AJAXURL,
					type      : method,
					data      : formData,
					error     : function() {
						alert("Something wrong! Try again later.");
					},
					beforeSend: function() {
						//show loader or u can show loader before ajax and then hide him
						$('body').prepend(Loaders.infiniteSpinner);
						$('body .loader-backdrop').animate({
							opacity: 1
						}, 500);
					},
					success   : function(response) {
						//remove/hide loader
						//do something with response
						var body = $('body'),
							wrapper = $('.ghostly-wrap');
						switch (response) {
							case 'success':
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('success', successMessage));
								// Clear inputs when success
								that.find('[name]').each(function() {
									$(this).val("");
								});
								break;
							case 'error' :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('danger', errorMessage));
								break;
							case 'error captcha' :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('warning', wrongCaptcha));
								break;
							default :
								body.find('.loader-backdrop').remove();
								wrapper.find('.alert').remove();
								that.before(alertHolder('info', unknownError));
						}
					},
					complete  : function() {
					}
				});

				return false;
			});

		};
		formContactProcess('aa_contact_form');

		var ajaxLoadPosts = function() {
			var page = 1,
				loading = true,
				$window = $(window),
				$content = $('#ajax-posts-loop');

			page === 1 && progressJs().start();

			var loadPosts = function() {
				$.ajax({
					url       : AJAXURL,
					type      : "POST",
					data      : {
						alice_ajax_posts: true,
						pageNumber      : page,
						action: 'alice_ajax_posts'
					},
					dataType  : "html",
					beforeSend: function() {
						if (page !== 1)
							$content.append(Loaders.bouncingStatic);

						page === 1 && progressJs().set(50);
					},
					success   : function(data) {
						var $data = $(data);
						if ($data.length) {
							$data.hide();
							$content.append($data);
							$data.fadeIn();
							$('#loader-static').remove();
							loading = false;
						} else {
							$('#loader-static').remove();
						}
						page === 1 && progressJs().end();
					},
					error     : function(jqXHR, textStatus, errorThrown) {
						$('#loader-static').remove();
						alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
					}
				});

			};
			$window.on('scroll', function() {
				if (((window.innerHeight + window.scrollY) >= document.body.offsetHeight) && loading === false) {
					loading = true;
					page++;
					loadPosts();
				}
			});
			loadPosts();
		};
		if (document.getElementById('ajax-posts-loop') !== null)
			ajaxLoadPosts();

	})();
});
/**
 * Base Theme Script ver 0.1.0
 */

window.onload = function() {

	"use strict";

	//Element, check existing class
	var elemHasClass = function(el, cls) {
		return el.className && new RegExp("(\\s|^)" + cls + "(\\s|$)").test(el.className);
	};
	// Wrapper for wp-pagenavi
	var paginator = function() {
		var pagenavi, ul, li, elemlist, htmlProcess;
		pagenavi = document.getElementsByClassName('wp-pagenavi')[0];

		if (pagenavi !== undefined) {
			htmlProcess = function() {
				var nodeTypeElem;
				elemlist = pagenavi.childNodes.length;
				ul = document.createElement('ul');
				ul.classList.add('pagination');
				pagenavi.insertBefore(ul, pagenavi.firstChild);

				while (elemlist--) {
					nodeTypeElem = pagenavi.childNodes[elemlist];
					if (nodeTypeElem.tagName === undefined || nodeTypeElem.tagName === 'UL')
						continue;
					li = document.createElement('li');
					li.appendChild(nodeTypeElem);
					ul.insertBefore(li, ul.firstChild);
				}
			};

			return pagenavi.innerHtml = htmlProcess();
		}

	};
	paginator();

	//Validation form without jq
	var formValidator = function() {
		var formHolder = document.getElementById('alicelf-commentform');

		if (formHolder) {
			var author = formHolder.elements['author'],
				email = formHolder.elements['email'],
				comment = formHolder.elements['comment'],
				respond = document.getElementById('respond');

			formHolder.onsubmit = function(e) {
				var counterEl = formHolder.elements.length,
					pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
					alertMsg = '<div class="alert alert-danger alert-dismissible" role="alert">' +
						'<button type="button" class="close" data-dismiss="alert">' +
						'<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
						'<h4>Fill correct all required fields!</h4>' +
						'<p>Note: name cannot be blank, email must be a valid mail, comment field also must be filled</p>',
					currElem, typeItem, matchedElem;

				if (author.value === '' || email.value === '' || email.value.search(pattern) === -1 || comment.value === '') {
					e.preventDefault();

					//beforebegin afterbegin beforeend afterend
					if (!elemHasClass(respond.firstChild, 'alert-danger'))
						respond.insertAdjacentHTML('afterbegin', alertMsg);

					while (counterEl--) {

						currElem = formHolder.elements[counterEl];
						typeItem = currElem.type;

						matchedElem = !!(typeItem === 'text' || typeItem === 'textarea');
						if (matchedElem) {

							if (currElem.value === '') {
								currElem.parentNode.classList.remove('has-success');
								currElem.parentNode.classList.add('has-error');

							} else
								if (currElem.value !== '' && currElem.name === 'email') {
									if (currElem.value.search(pattern) === -1) {
										currElem.parentNode.classList.remove('has-success');
										currElem.parentNode.classList.add('has-error');
									} else {
										currElem.parentNode.classList.remove('has-error');
										currElem.parentNode.classList.add('has-success');
									}
								} else {
									currElem.parentNode.classList.remove('has-error');
									currElem.parentNode.classList.add('has-success');
								}
						}

					}

					alert('Fill Correct All required Fields!');
					return false;
				}
			};
		}
	};
	formValidator();

	var arrowScroll = function() {
		//document.documentElement.scrollTop || document.body.scrollTop;
		//document.documentElement.scrollLeft || document.body.scrollLeft;
		var arrow = document.getElementById('footer-angle-arrow');
		window.document.addEventListener('scroll', function() {
			var topOffset = document.documentElement.scrollTop || document.body.scrollTop;
			topOffset > 300 ? arrow.classList.add('visible-arrow') : arrow.classList.remove('visible-arrow');
		});
	};
	arrowScroll();
	/**
	 * Smooth Scrolling
	 * Easing: https://github.com/cferdinandi/smooth-scroll#user-content-easing-options
	 */

	if (typeof(smoothScroll) === 'object') {
		var triggerClick = document.querySelector('#footer-angle-arrow'),
			scrolledOptions = {
				speed : 700,
				easing: 'easeOutQuart'
			};
		triggerClick.onclick = function(e) {
			e.preventDefault();
			smoothScroll.animateScroll(triggerClick, '#scroll-trigger-top', scrolledOptions);
		};
	}

	/* Parallax things */
	var parallaxFn = function(elem, speed, param) {
		var handlerElement = document.querySelector(elem);
		if (handlerElement) {
			handlerElement.style.backgroundPositionY = Math.round(-(window.pageYOffset / speed + param)) + 'px';
			window.document.addEventListener('scroll', function() {
				handlerElement.style.backgroundPositionY = Math.round(-(window.pageYOffset / speed + param)) + 'px';
			});
		}
	};
};

jQuery(document).ready(function($) {

	var slickSliderOpt = function() {
		$('.slider-for').slick({
			slidesToShow  : 1,
			slidesToScroll: 1,
			arrows        : false,
			fade          : true,
			asNavFor      : '.slider-nav'
		});
		$('.slider-nav').slick({
			slidesToShow  : 3,
			slidesToScroll: 1,
			asNavFor      : '.slider-for',
			dots          : false,
			centerMode    : true,
			focusOnSelect : true
		});
	};
	if (typeof $.fn.slick === 'function')
		slickSliderOpt();

	var stickNavbar = function() {

		$(window).on('scroll', function() {
			var topOffset = document.documentElement.scrollTop || document.body.scrollTop,
				selection = $('.stick-to-top').find('>.container > header'),
				wpAdminBarH = $('#wpadminbar').height(),
				selectionHeight = selection.height();

			if ($(window).width() < 600)
				wpAdminBarH = 0;

			$(window).on('resize', function() {
				if ($(window).width() < 600)
					wpAdminBarH = 0;
				selectionHeight = $('.stick-to-top').find('>.container > header').height();
			});

			if (topOffset > selectionHeight) {
				selection.css({
					position : 'fixed',
					width    : '100%',
					top      : (0 + wpAdminBarH) + 'px',
					'z-index': '999'
				});
				if (!selection.hasClass('header-touch-top')) {
					selection.css({
						top    : -selectionHeight + 'px',
						opacity: '0'
					});
					selection.animate({
						top    : (0 + wpAdminBarH) + 'px',
						opacity: 1
					}, 500)
				}
				selection.addClass('header-touch-top');
				$('#shock-absorber').css({height: selectionHeight + "px"});
			} else {
				selection.css({
					position: 'static',
					width   : 'auto'
				});
				selection.removeClass('header-touch-top');
				$('#shock-absorber').css({height: 0});
			}
		});

	};
	stickNavbar();

});