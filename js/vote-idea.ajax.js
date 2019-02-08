// Ajax for vote post



( function($) {
	$( document ).ready( function($) {

		$( '.vote-idea' ).on( 'click', function(e) {
			var self = $( this );
			e.preventDefault();
			
			var postId = $( this ).attr( 'data-post' );
			var email = sessionStorage.getItem( 'upgrduser' );
			
			// console.log( email );
			if( email === null ) {
				$( '#vote-idea-' + postId ).html( 'Not signed in!' );
				return;
			}
			
			var info = { 0: postId, 1: email };

			$.ajax({
				type: 'POST',
				url: voteideaAjax.ajaxurl,
				data: {
					'action': 'justifyblog_vote_idea',
					'data' : info,
				},
				success: function(data) {				
					$( '#vote-idea-' + postId ).html( data );
					// console.log( data );
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});			
		});
		
	});
} )( jQuery );