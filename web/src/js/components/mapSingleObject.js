"use strict";

export default class YaMapSingleObject{
	constructor(){
		let self = this;
    var fired = false;

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

	init() {
    var hint_content = $('#map').data('hint'),
        baloon_content = $('#map').data('balloon');
    this.script('//api-maps.yandex.ru/2.1/?lang=ru_RU').then(() => {
        const ymaps = global.ymaps;
    		ymaps.ready(function(){
    			var myMap = new ymaps.Map('map', {
                    center: [
                        $('.map #map').data('mapdotx'),
                        $('.map #map').data('mapdoty'),
                    ],
                    zoom: 15,
                    controls: []
                });

        myMap.behaviors.disable('scrollZoom');

        let zoomControl = new ymaps.control.ZoomControl({
          options: {
              size: "small",
              position: {
                top: 10,
                right: 10
              }

          }
        });

        let geolocationControl = new ymaps.control.GeolocationControl({
          options: {
            noPlacemark: true,
            position: {
              top: 10,
              left: 10
            }
          }
        });

        myMap.controls.add(zoomControl);
        myMap.controls.add(geolocationControl);

        let MyBalloonLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="popover top">' +
                '<a class="close" href="#">&times;</a>' +
                '<div class="arrow"></div>' +
                '<div class="popover-inner">' +
                '$[[options.contentLayout observeSize minWidth=300 maxWidth=300]]' +
                '</div>' +
                '</div>', {

                build: function () {
                    this.constructor.superclass.build.call(this);

                    this._$element = $('.popover', this.getParentElement());

                    this.applyElementOffset();

                    this._$element.find('.close')
                        .on('click', $.proxy(this.onCloseClick, this));
                },

                clear: function () {
                    this._$element.find('.close')
                        .off('click');

                    this.constructor.superclass.clear.call(this);
                },

                onSublayoutSizeChange: function () {
                    MyBalloonLayout.superclass.onSublayoutSizeChange.apply(this, arguments);

                    if(!this._isElement(this._$element)) {
                        return;
                    }

                    this.applyElementOffset();

                    this.events.fire('shapechange');
                },

                applyElementOffset: function () {
                    this._$element.css({
                        left: -(this._$element[0].offsetWidth / 2),
                        top: -(this._$element[0].offsetHeight + this._$element.find('.arrow')[0].offsetHeight)
                    });
                },

                onCloseClick: function (e) {
                    e.preventDefault();

                    this.events.fire('userclose');
                },

                getShape: function () {
                    if(!this._isElement(this._$element)) {
                        return MyBalloonLayout.superclass.getShape.call(this);
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
            });

        let MyBalloonContentLayout = ymaps.templateLayoutFactory.createClass(
            '<h3 class="popover-title">$[properties.balloonHeader]</h3>' +
                '<div class="popover-content">$[properties.balloonContent]</div>'
        );

        let myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
            balloonHeader: hint_content,
            balloonContent: baloon_content,
            hintContent: hint_content
        },
        {
            balloonShadow: false,
            balloonLayout: MyBalloonLayout,
            balloonContentLayout: MyBalloonContentLayout,
            balloonPanelMaxMapArea: 0,
            hideIconOnBalloonOpen: false,
            balloonOffset: [-150, 12],
            preset: 'islands#darkGreenDotIcon'
        },
        {
            iconLayout: 'default#image',
        });

        myMap.geoObjects
                .add(myPlacemark); 
                 });
      });
	}
}