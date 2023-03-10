'use strict';

export default class YaMap {
	constructor(filter) {
		let self = this;
		var fired = false;
		this.filter = filter;
		this.myMap = false;
		this.objectManager = false;
		this.myBalloonLayout = false;
		this.myBalloonContentLayout = false;
		console.log(this.filter);

		window.addEventListener('click', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, {passive: true});

		window.addEventListener('scroll', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, {passive: true});

		window.addEventListener('mousemove', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, {passive: true});

		window.addEventListener('touchmove', () => {
			if (fired === false) {
				fired = true;
				load_other();
			}
		}, {passive: true});

		function load_other() {
			setTimeout(function() {
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

	refresh(filter) {
		let self = this;
		let data = {
			subdomain_id: $('[data-map-api-subid]').data('map-api-subid'),
			filter: JSON.stringify(filter.state)
		};

		$.ajax({
			type: "POST",
			url: "/"+self.filter.subdomen.data('alias')+"api/map_all/",
			data: data,
			success: function (response) {
				let serverData = response;

				self.objectManager = new ymaps.ObjectManager(
					{
						geoObjectBalloonLayout: self.myBalloonLayout,
						geoObjectBalloonContentLayout: self.myBalloonContentLayout,
						geoObjectHideIconOnBalloonOpen: false,
						geoObjectBalloonOffset: [-360, 17],
						clusterize: true,
						clusterDisableClickZoom: false,
						clusterBalloonItemContentLayout: self.myBalloonContentLayout,
						clusterIconColor: "green",
						geoObjectIconColor: "green"
					}
				);
				self.objectManager.add(serverData);
				if ($('.map_container').hasClass('_active')) {
					self.myMap.geoObjects.removeAll();
					$('.map_container').addClass('_loaded').removeClass('_active');
				}
				$('.map_container').removeClass('_lazy');
				$('[data-show-map]').on('click', function () {
					console.log('data-show-map');
					ym(64598434, 'reachGoal', 'map_open');
					$(this).closest('.map_container').removeClass('_loaded').addClass('_active');
					self.myMap.geoObjects.removeAll();
					self.myMap.geoObjects.add(self.objectManager);
					self.myMap.setBounds(self.objectManager.getBounds());
				});
			},
			error: function (response) {

			}
		});
	}

	init() {
		let self = this;
		this.script('//api-maps.yandex.ru/2.1/?lang=ru_RU').then(() => {
			const ymaps = global.ymaps;

			ymaps.ready(function () {
				let map = document.querySelector(".map");
				self.myMap = new ymaps.Map(map, { center: [55.749362, 37.627214], zoom: 14 });
				self.myMap.behaviors.disable('scrollZoom');

				self.myBalloonLayout = ymaps.templateLayoutFactory.createClass(
					`<div class="balloon_layout">
                        <a class="close" href="#"></a>
                        <div class="arrow"></div>
                        <div class="balloon_inner">
                            $[[options.contentLayout]]
                        </div>
                    </div>`, {
						build: function () {
							this.constructor.superclass.build.call(this);

							this._$element = $('.balloon_layout', this.getParentElement());

							this._$element.find('.close')
								.on('click', $.proxy(this.onCloseClick, this));

						},

						clear: function () {
							this._$element.find('.close')
								.off('click');

							this.constructor.superclass.clear.call(this);
						},

						onCloseClick: function (e) {
							e.preventDefault();

							this.events.fire('userclose');
						},

						getShape: function () {
							if (!this._isElement(this._$element)) {
								return self.myBalloonLayout.superclass.getShape.call(this);
							}

							var position = this._$element.position();

							return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle([
								[position.left, position.top], [
									position.left + this._$element[0].offsetWidth,
									position.top + this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight
								]
							]));
						},

						_isElement: function (element) {
							return element && element[0] && element.find('.arrow')[0];
						}
					}
				);

				self.myBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
					`<div class="balloon_wrapper">

                        <div class="balloon_content">

                            <img src={{properties.img}}>

                            <div class="balloon_text">

                                <div class="balloon_header">
                                    {{properties.organization}}
                                </div>

                                <div class="balloon_address">
                                    {{properties.address}}
                                </div>

                            </div>

                        </div>

                        <div class="balloon_link">
                            <button class="balloon_link_button _button"><a href="{{properties.link_unique}}">Посмотреть зал</a></button> 
                        </div>
                        
                    </div>`
				);

				self.objectManager = new ymaps.ObjectManager(
					{
						geoObjectBalloonLayout: self.myBalloonLayout,
						geoObjectBalloonContentLayout: self.myBalloonContentLayout,
						geoObjectHideIconOnBalloonOpen: false,
						geoObjectBalloonOffset: [-360, 17],
						clusterize: true,
						clusterDisableClickZoom: false,
						clusterBalloonItemContentLayout: self.myBalloonContentLayout,
						clusterIconColor: "green",
						geoObjectIconColor: "green"
					}
				);

				let serverData = null;
				let data = {
					subdomain_id: $('[data-map-api-subid]').data('map-api-subid'),
					filter: JSON.stringify(self.filter.state)
				};

				$.ajax({
					type: "POST",
					url: "/"+self.filter.subdomen.data('alias')+"api/map_all/",
					data: data,
					success: function (response) {
						serverData = response;
						self.objectManager.add(serverData);
						self.myMap.geoObjects.add(self.objectManager);
						self.myMap.setBounds(self.objectManager.getBounds());
						$('.map_container').removeClass('_lazy');
						$('[data-show-map]').on('click', function () {
							ym(64598434, 'reachGoal', 'map_open');
							$(this).closest('.map_container').addClass('_active');
						});
					},
					error: function (response) {

					}
				});


			});
		});
	}
}