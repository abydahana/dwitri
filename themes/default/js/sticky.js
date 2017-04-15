(function()
{
	var t, i;
	t = this.jQuery || window.jQuery, i = t(window), t.fn.stick_in_parent = function(o)
	{
		var s, n, e, c, r, l, a, u, d, f, p;
		for (null == o && (o = {}), p = o.sticky_class, r = o.inner_scrolling, f = o.recalc_every, d = o.parent, u = o.offset_top, a = o.spacer, n = o.bottoming, null == u && (u = 0), null == d && (d = void 0), null == r && (r = !0), null == p && (p = "is_stuck"), s = t(document), null == n && (n = !0), e = function(o, e, c, l, h, k, g, y)
			{
				var m, v, _, w, b, x, $, j, z, C, I, Q;
				if (!o.data("sticky_kit"))
				{
					if (o.data("sticky_kit", !0), b = s.height(), $ = o.parent(), null != d && ($ = $.closest(d)), !$.length) throw "failed to find stick parent";
					if (m = _ = !1, (I = null != a ? a && o.closest(a) : t("<div />")) && I.css("position", o.css("position")), j = function()
						{
							var t, i, n;
							return !y && (b = s.height(), t = parseInt($.css("border-top-width"), 10), i = parseInt($.css("padding-top"), 10), e = parseInt($.css("padding-bottom"), 10), c = $.offset().top + t + i, l = $.height(), _ && (m = _ = !1, null == a && (o.insertAfter(I), I.detach()), o.css(
							{
								position: "",
								top: "",
								width: "",
								bottom: ""
							}).removeClass(p), n = !0), h = o.offset().top - (parseInt(o.css("margin-top"), 10) || 0) - u, k = o.outerHeight(!0), g = o.css("float"), I && I.css(
							{
								width: o.outerWidth(!0) - 1,
								height: k,
								display: o.css("display"),
								"vertical-align": o.css("vertical-align"),
								"float": g
							}), n) ? Q() : void 0
						}, j(), k !== l) return w = void 0, x = u, C = f, Q = function()
					{
						var t, d, v, z;
						return !y && (v = !1, null != C && (--C, 0 >= C && (C = f, j(), v = !0)), v || s.height() === b || j(), v = i.scrollTop(), null != w && (d = v - w), w = v, _ ? (n && (z = v + k + x > l + c, m && !z && (m = !1, o.css(
						{
							position: "fixed",
							bottom: "",
							top: x
						}).trigger("sticky_kit:unbottom"))), h > v && (_ = !1, x = u, null == a && ("left" !== g && "right" !== g || o.insertAfter(I), I.detach()), t = {
							position: "",
							width: "",
							top: ""
						}, o.css(t).removeClass(p).trigger("sticky_kit:unstick")), r && (t = i.height(), k + u > t && !m && (x -= d, x = Math.max(t - k, x), x = Math.min(u, x), _ && o.css(
						{
							top: x + "px"
						})))) : v > h && (_ = !0, t = {
							position: "fixed",
							top: x
						}, t.width = "border-box" === o.css("box-sizing") ? o.outerWidth() + "px" : o.width() + "px", o.css(t).addClass(p), null == a && (o.after(I), "left" !== g && "right" !== g || I.append(o)), o.trigger("sticky_kit:stick")), _ && n && (null == z && (z = v + k + x > l + c), !m && z)) ? (m = !0, "static" === $.css("position") && $.css(
						{
							position: "relative"
						}), o.css(
						{
							position: "absolute",
							bottom: e,
							top: "auto"
						}).trigger("sticky_kit:bottom")) : void 0
					}, z = function()
					{
						return j(), Q()
					}, v = function()
					{
						return y = !0, i.off("touchmove", Q), i.off("scroll", Q), i.off("resize", z), t(document.body).off("sticky_kit:recalc", z), o.off("sticky_kit:detach", v), o.removeData("sticky_kit"), o.css(
						{
							position: "",
							bottom: "",
							top: "",
							width: ""
						}), $.position("position", ""), _ ? (null == a && ("left" !== g && "right" !== g || o.insertAfter(I), I.remove()), o.removeClass(p)) : void 0
					}, i.on("touchmove", Q), i.on("scroll", Q), i.on("resize", z), t(document.body).on("sticky_kit:recalc", z), o.on("sticky_kit:detach", v), setTimeout(Q, 0)
				}
			}, c = 0, l = this.length; l > c; c++) o = this[c], e(t(o));
		return this
	}
}).call(this), (function()
{
	var t, i;
	t = this.jQuery || window.jQuery, i = t(window), t.fn.sticky = function(t)
	{
		$(window).width() > 1024 && ($(".sticky").stick_in_parent(
		{
			offset_top: 60
		}).on("sticky_kit:bottom", function(t)
		{
			$(this).parent().css("position", "static")
		}).on("sticky_kit:unbottom", function(t)
		{
			$(this).parent().css("position", "relative")
		}), $(window).on("resize", function(t)
		{
			return function(t)
			{
				return $(document.body).trigger("sticky_kit:recalc")
			}
		}(this)))
	}
}).call(this);

$(window).width() > 1024 && ($(".sticky").stick_in_parent(
{
	offset_top: 60
}).on("sticky_kit:bottom", function(t)
{
	$(this).parent().css("position", "static")
}).on("sticky_kit:unbottom", function(t)
{
	$(this).parent().css("position", "relative")
}), $(window).on("resize", function(t)
{
	return function(t)
	{
		return $(document.body).trigger("sticky_kit:recalc")
	}
}(this)))