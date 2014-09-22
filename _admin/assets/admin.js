// On page load run jQuery scripts
$(function() {

	// Project page
	//////////////////////////////////////////////////////////////
	$("body.migrate-project .btn-danger").click( function() {
		
		var answer = confirm("Are you sure you want to delete this entire project and all of its content? Can't be undone!");
/*
		if (answer) {
			return true;
		} else {
			return false;
		}
*/
		return answer;

	});

});