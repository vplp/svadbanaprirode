'use strict';
import Swiper from 'swiper';
import 'slick-carousel';
import * as Lightbox from '../../../node_modules/lightbox2/dist/js/lightbox.js';

export default class Item{
	constructor($item){
		var self = this;
		
		$('[data-action="show_phone"]').on('click', function(){
			$('.object_book_hidden').addClass('_active');
		});

		var galleryThumbs = new Swiper('.item_thumb_slider', {
            spaceBetween: 5,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            breakpoints: {
                767: {
                  slidesPerView: 'auto',
                  spaceBetween: 5
                }
            }
        });
        var galleryTop = new Swiper('.item_top_slider', {
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: {
                swiper: galleryThumbs
            }
        });	
	}
}