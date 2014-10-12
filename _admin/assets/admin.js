// On page load run jQuery scripts
$(function() {

	// Project page
	//////////////////////////////////////////////////////////////
	$("body.migrate-project .btn-danger").click( function() {
		
		var answer = confirm("Are you sure you want to delete this entire project and all of its content? Can't be undone!");

		return answer;

	});

	$("body.migrate-project .btn-warning").click( function() {
		
		var answer = confirm("Are you sure you want to remove the contents (only crawled and washed data) of this project? Can't be undone!");

		return answer;

	});

	// "Manage"
	//////////////////////////////////////////////////////////////
	$("body.migrate-step3 .btn-danger").click( function() {
		
		var answer = confirm("Are you sure you want to delete this entire page and all of its content? Can't be undone!");

		return answer;

	});

});