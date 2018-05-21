var com = {};
com.michouafroshop = {};
com.michouafroshop.Core = function() {// to the namespace com.michouafroshop
	// add a function Core() which return
	// that
	var isShowing = false;
	var that = {};
	that.flag = 'none';
	that.init = function() {

		$('#cat_insererprod').click(function(){
			$('#cat_recette').removeClass('active');
			$('#cat_insererprod').addClass('active');
		});
		$('#cat_recette').click(function(){
			$('#cat_insererprod').removeClass('active');
			$('#cat_recette').addClass('active');
		});
		
		$('.nav-link').click(function() {
			$(this).addClass('active').siblings().removeClass('active');
		});

		/*
		 * CONFIGURATION DE LA RECETTE ==================================
		 */
	
		/*
		 * CATEGORIE ==================================
		 */
		that.changeCssForAI("#collapseViandeHref", "#collapseViandeHrefI");
		that.changeCssForAI("#collapsePoissonsCrustacesHref",
				"#collapsePoissonsCrustacesHrefI");

		$('.carousel').carousel();
		$('.collapse').collapse({
			toggle : false
		})

		$(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
		$(function() {
			$('.js-datepicker').datepicker({
				format : 'yyyy-mm-dd'
			});
		});
		/*
		 * CREATE PRODUIT ==================================
		 */
		// $('#myModal').modal();
		/*
		 * SHOW PRODUIT ==================================
		 */

		
	};

	that.changeCssForAI = function(idA, idI) {
		$(idA).on(
				'click',
				function() {
					if (isShowing) {
						isShowing = false;
						that.changeCssForI(idI,
								"fa fa-minus-square pull-right",
								"fa fa-plus-square pull-right");
					} else {
						isShowing = true;
						that.changeCssForI(idI, "fa fa-plus-square pull-right",
								"fa fa-minus-square pull-right");
					}
				});
	};

	that.changeCssForI = function(id, from, to) {
		$(id).removeClass(from);
		$(id).addClass(to);
	};
	that.register = function() {
		$(document).ready(function() {
			that.init();
		});
	};
	return that;// dont forget
};