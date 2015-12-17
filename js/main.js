// AJAX pour la recherche sur la page lodging_search.php

$("#flats-form").on("keyup", function()
{
	var research = $("#flats-form input[name=flatsForm]").val();

	$.ajax({
		"url" : "lodging_search_app.php",
		"type" : "GET",
		"data" : $("#flats-form").serialize()
	})
	.done( function(response) {
		$("#results").html(response);
	})
	.fail( function() {
		alert("mauvaise saisie");
	});
});