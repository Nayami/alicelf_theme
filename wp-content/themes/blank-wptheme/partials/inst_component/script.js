jQuery(document).ready(function($) {


	/**
	 * ==================== waitUntilExists ======================
	 * 2/11/2016
	 */
	$.fn.waitUntilExists = function(handler, shouldRunHandlerOnce, isChild) {
		var found = 'found';
		var $this = $(this.selector);
		var $elements = $this.not(function() {
			return $(this).data(found);
		}).each(handler).data(found, true);

		if (!isChild) {
			(window.waitUntilExists_Intervals = window.waitUntilExists_Intervals || {})[this.selector] =
				window.setInterval(function() {
					$this.waitUntilExists(handler, shouldRunHandlerOnce, true);
				}, 500)
			;
		}
		else
			if (shouldRunHandlerOnce && $elements.length) {
				window.clearInterval(window.waitUntilExists_Intervals[this.selector]);
			}

		return $this;
	};


	/**
	 * ==================== Instagram Footer ======================
	 * 27.06.2016
	 */

	var instagramOverlap = function() {
		var container = $('#instagram-footer-widget'),
			instaWrap = $('#footer-instawrapper'),
			instaReel = $('#insta-reel');

		container.find('.sbi_item').waitUntilExists(function() {
			var that = $(this),
				numberItems = container.find('.sbi_item').length,
				elementWidth = that.width(),
				elementOuterWidth = that.outerWidth(),
				totalWidth = numberItems * elementOuterWidth + 10,
				windowWidth = $(window).width(),
				leftOffset = (totalWidth - windowWidth) / 2;

			instaReel.width(totalWidth);
			instaReel.css('left',-Math.abs(leftOffset));

			//instaWrap.mCustomScrollbar({
			//		axis    : "x",
			//		theme   : "inset-dark",
			//		setWidth: '100%'
			//	})
			//	.mCustomScrollbar("scrollTo", "50%", {scrollInertia: 0});

			instaReel.draggable(
				{ axis: "x",
					drag: function(event, obj) {
						var that = $(this), winWidth = $(window).width(),
							objW = that.width(),
							diff = objW - winWidth;

						if(obj.position.left < -Math.abs(diff)) {
							obj.position.left = -Math.abs(diff);
						}
						if(obj.position.left > 30) {
							obj.position.left = 0;
						}
					},});

			setTimeout(function() {
				instaReel.css('opacity', 1);
			}, 200);


		});
	};

	instagramOverlap();
	$(window).on('resize', function() {
		instagramOverlap();
	});

});