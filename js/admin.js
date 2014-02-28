jQuery(document).ready( function() {
	// print FB.js Loader
	jQuery('body').prepend(
		"<div id='fb-root'></div><script>window.fbAsyncInit = function(){FB.init({appId:'566267770131392',status:true,xfbml:true});};(function(d,s,id){var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = '//connect.facebook.net/en_US/all.js';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>"
	);
	
	// jQuery('#eventlist a.logout').click(function(){
	// 	FB.logout(function(){
	// 		// location.reload();
	// 	});
	// });
} );