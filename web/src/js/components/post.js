'use strict';
import Swiper from 'swiper';

export default class Main{
	constructor(){
		var self = this;
		this.swipers_gal = new Array();
		this.swipers_rest = new Array();

	    $('.object_gallery').each(function(iter,object){
			let postGalleryThumbs = new Swiper($(this).find('.item_thumb_slider'), {
		        spaceBetween: 5,
		        slidesPerView: 7,
		        slidesPerColumn: 1,
		        freeMode: true,
		        watchSlidesVisibility: true,
		        watchSlidesProgress: true,

		        breakpoints: {
		            1440: {
		              	slidesPerView: 5,
		            },

		            767: {
		              	slidesPerView: 4,
		            }
		        }
		     });
			let postGalleryTop = new Swiper($(this).find('.item_top_slider'), {
				spaceBetween: 0,
				thumbs: {
					swiper: postGalleryThumbs
				}
			});

			self.swipers_gal.push({
				postGalleryThumbs,
				postGalleryTop
			});
		});

		$('[data-adv-gallery-wrapper]').each(function(iter,object){
			console.log('hi');
			let postAdv = new Swiper($(this).find('.listing_slider'), {
				spaceBetween: 0,
				slidesPerView: 1,
				navigation: {
					nextEl: '._listing_next',
					prevEl: '._listing_prev',
				},
			});

			self.swipers_rest.push({
				postAdv
			});
		});
	}
}