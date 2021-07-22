jQuery( function ( $ ) {
	'use strict';

	var Test_field = {
		init: function(){

		},
		reload: function(){

		}
	}

	var CSSFRAMEWORK = {
		test: {
			init: function(){
				Test_field.init();
			},
			reload: function(){
				Test_field.reload();
			}
		}
	}

	$.CSSFRAMEWORK.PLUGINS = $.extend(true, $.CSSFRAMEWORK.PLUGINS, CSSFRAMEWORK );
});
