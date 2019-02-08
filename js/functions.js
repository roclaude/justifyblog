function onSignIn( googleUser ) {
	var profile = googleUser.getBasicProfile();

//	console.log('Name: ' + profile.getName());
//	console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
//	console.log("Image URL: " + profile.getImageUrl());
//	console.log('Given Name: ' + profile.getGivenName());
//	console.log('Family Name: ' + profile.getFamilyName());
	
	sessionStorage.setItem( 'upgrduser', profile.getEmail() );	
	sessionStorage.setItem( 'upgrdname', profile.getName() );	
	document.getElementById( 'gsignin' ).style.display = 'none';
	
	var form = document.getElementById( 'justifyblog-publish' );
	if( form ) {
		document.getElementById( 'justifyblog_idea_author_name' ).value = profile.getName();
		document.getElementById( 'justifyblog_idea_image_url' ).value = profile.getImageUrl();
		document.getElementById( 'justifyblog_idea_author_email' ).value = profile.getEmail();
	}

	var commentform = document.getElementById( 'commentform' );
	if( commentform ) {
		document.getElementById( 'author' ).value = profile.getName();
		document.getElementById( 'url' ).value = profile.getImageUrl();
		document.getElementById( 'email' ).value = profile.getEmail();
	}
}


( function ($) {

	$( document ).ready( function() {
		sessionStorage.removeItem( 'upgrduser' );
	} );
	
	$( '#justifyblog-publish, #commentform' ).submit( function(e) {
		
		var email = sessionStorage.getItem( 'upgrduser' );
		if( email === null ) {
			e.preventDefault();
			$( this ).find( '.form-err' ).html( 'Not signed in!' );
			return false;
		}
	} );

	$( window ).load( function() {
		setTimeout( function() {
			var email = sessionStorage.getItem( 'upgrduser' );
//			console.log( email );
			if( email === null ) {
				$( '#gsignin' ).css( 'display', 'block' );
			}
		}, 2000 );
	} );
	
} )(jQuery);


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}



( function($) {

	$( document ).ready( function() {
		$( '#justifyblog-publish' ).qformvalidation( { required: 'all' } );
	} );
		
} )(jQuery);


( function( $ ) {
	
	var defaults = {
		required:	'all'
	};
	
	function qFormValidation( element, options ) {
		this.element = element;
		this.options = $.extend( {}, options, defaults );
		this.defaults = defaults;
		this.init();
	}
	
	qFormValidation.prototype.init = function() {
		var self = this;
		
		$( this.element ).find( '.required' ).on( 'click', function() {
			console.log( 'dasd' );
			var err = self.validate( this );
			$parent = $( this ).parent();
			if( $( this ).hasClass( 'required' ) ) {
				$parent.hasClass( 'error' ) && ! err ? $parent.removeClass( 'error' ) : false;
				console.log( 'required' );
			} else {
				( $parent.hasClass( 'error' ) && ! err ) || this.value.length == 0 ? $parent.removeClass( 'error' ) : false;
				console.log( 'not required' );
			}
		} );
		
		$( this.element ).submit( function(e) {
			var errors = self.formValidation();
			if( errors ) {
				e.preventDefault();
				return false;
			}
		} );
	}

	qFormValidation.prototype.formValidation = function() {
		var self 	= this,
			$form 	= $( this.element ),
			//$submit = $form.find( 'input[type="submit"]' ),
			$inputs	= $form.find( '.required' ),
			error 	= false;
						
		$form.find( '.error' ).removeClass( 'error' );
	
		$inputs.each( function() {
			var err;
			if( $( this ).hasClass( 'required' ) ) { //required
				err = self.validate( this );
				if( err ) {
					self.showErrorMessage( this );
					error = true;
				}
			} else { //not required
				// it should be validated if the field is not empty
				if( this.value.length > 0 ) {
					err = self.validate( this );
					if( err ) {
						self.showErrorMessage( this );
						error = true;
					}
				}
			}
		} );
		return error;
	}	
	
	qFormValidation.prototype.validate = function( item ) {
		var err = false;
		switch( item.type ) {
			case 'tel':
				regx = /^\+?[0-9-.() ]{6,}$/;
				if ( !regx.test( item.value ) ) {
					err = 'tel';
				}
			break;
			case 'email':
				regx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if ( !regx.test( item.value ) ) {
					err = 'email';
				}
			break;
			case 'checkbox':
				if( ! item.checked ) {
					err = 'check';
				}
			break;
			default:
				if( item.value.length < 3 ) {
					err = 'least_three_characters';
				}

			}
		return err;
	}
	
	qFormValidation.prototype.showErrorMessage = function( item ) {
		var parent = $( item ).parent();
		if( ! parent.hasClass( 'error' ) ) {
			parent.addClass( 'error' );
		}
	}
	
	$.fn.qformvalidation = function( options ) {
		return this.each( function() {
			var value = '';
			if( !$.data( this, value ) ){
				$.data( this, value, new qFormValidation( this, options ) );
			}
		} );
	}
	
} )( jQuery );

