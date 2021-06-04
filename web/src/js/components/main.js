'use strict';

export default class Main{
	constructor(){
		$('body').on('click', '[data-seo-control]', function(){
			$(this).closest('[data-seo-text]').addClass('_active');
		});

		$('body').on('click', '[data-open-popup-form]', function(){
			$('.popup_wrap').addClass('_active');
			ym(64598434,'reachGoal','header_button');
			gtag('event', 'header_button');
		});

		$('body').on('click', '[data-close-popup]', function(){
			$('.popup_wrap').removeClass('_active');
		});

		$('.header_burger').on('click', function(){
			$('.header_menu').toggleClass('_active');
			$('.header_burger').toggleClass('_active');
			$('header').toggleClass('_active');
		});

	}
}