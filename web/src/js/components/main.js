'use strict';

export default class Main{
	constructor(){


		$('body').on('click', '[data-seo-control]', function(){
			$(this).closest('[data-seo-text]').addClass('_active');
		});
	}
}