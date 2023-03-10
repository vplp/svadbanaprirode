'use strict';
import Cookies from 'js-cookie';

export default class Main {
	constructor() {
		let self = this;

		// === записываем в куки данные для отправки Calltracking в БД горько START ===
		//запись в куки только внешнего реферера
		let pageReferrer = '';
		//проверяем что это внешний реферер, а не переход внутри страниц сайта
		// if (document.referrer.indexOf(window.location.origin) != -1) { //в этом случае поддомены (например samara.arendazala.net) тоже считаются внешним реферером
		if (document.referrer.indexOf('svadbanaprirode.com') == -1) { // отсекаем так же поддомена, как внешний реферер
			if (document.referrer) {
				pageReferrer = document.referrer;
			}
			if (Cookies.get('a_ref_0')) {
				Cookies.set('a_ref_1', pageReferrer, { expires: 365 });
			} else {
				Cookies.set('a_ref_0', pageReferrer, { expires: 365 });
			}
		}

		//запись в куки utm_details
		let currentUrl = '';
		if (window.location.href) {
			currentUrl = window.location.href;
		}
		let patternUtm = RegExp('utm_details=([^\&]*)', 'g');
		let utmExist = patternUtm.exec(currentUrl);
		let utm = {};
		if (utmExist) {
			let rows = utmExist[1].split('|');

			for (let i = 0; i < rows.length; i++) {
				let a = rows[i].split(':');
				utm[a[0]] = a[1];
			}
		}

		if (Object.keys(utm).length != 0) {
			let utmJson = JSON.stringify(utm);
			if (Cookies.get('a_utm_0') && Cookies.get('a_utm_0') != '{}') {
				Cookies.set('a_utm_1', utmJson, { expires: 365 });
			} else {
				Cookies.set('a_utm_0', utmJson, { expires: 365 });
			}
		}
		// === записываем в куки данные для отправки Calltracking в БД горько END ===

		$('body').on('click', '[data-seo-control]', function () {
			$(this).closest('[data-seo-text]').addClass('_active');
		});

		$('body').on('click', '[data-open-popup-form]', function () {
			$('.popup_wrap').addClass('_active');
			ym(64598434, 'reachGoal', 'header_button');
			gtag('event', 'header_button');
		});

		$('body').on('click', '[data-close-popup]', function () {
			$('.popup_wrap').removeClass('_active');
		});

		$('.header_burger').on('click', function () {
			$('.header_menu').toggleClass('_active');
			$('.header_burger').toggleClass('_active');
			$('header').toggleClass('_active');
			$('.header_menu_mobile_under').addClass('hidden');
			$('body').removeClass('overflow_hidden');
		});

		var fired = false;

		window.addEventListener('click', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, { passive: true });

		window.addEventListener('scroll', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, { passive: true });

		window.addEventListener('mousemove', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, { passive: true });

		window.addEventListener('touchmove', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, { passive: true });

		function load_other() {
			setTimeout(function () {
				self.init();
			}, 100);
		}
	}

	script(url) {
		if (Array.isArray(url)) {
			let self = this;
			let prom = [];
			url.forEach(function (item) {
				prom.push(self.script(item));
			});
			return Promise.all(prom);
		}

		return new Promise(function (resolve, reject) {
			let r = false;
			let t = document.getElementsByTagName('script')[0];
			let s = document.createElement('script');

			s.type = 'text/javascript';
			s.src = url;
			s.async = true;
			s.onload = s.onreadystatechange = function () {
				if (!r && (!this.readyState || this.readyState === 'complete')) {
					r = true;
					resolve(this);
				}
			};
			s.onerror = s.onabort = reject;
			t.parentNode.insertBefore(s, t);
		});
	}

	init() {
		setTimeout(function () {
			(function (m, e, t, r, i, k, a) {
				m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments) };
				m[i].l = 1 * new Date(); k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
			})
				(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

			ym(64598434, "init", {
				clickmap: true,
				trackLinks: true,
				accurateTrackBounce: true,
				webvisor: true
			});
		}, 100);

		this.script('https://www.googletagmanager.com/gtag/js?id=UA-175581738-1').then(() => {
			window.dataLayer = window.dataLayer || [];
			function gtag() { dataLayer.push(arguments); }
			global.gtag = gtag;
			gtag('js', new Date());

			gtag('config', 'UA-175581738-1');
		});
	}
}