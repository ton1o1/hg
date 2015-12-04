<script src="js/jquery.min.js"></script>
<script>
$(function(){
	$("#addPicture").on("mousedown", function(){
		var pictureCount = $("#pictureCount").val();
		if(pictureCount < 3){
			var next = parseInt(pictureCount) + 1;
			$("#picturesUpload").append('<br />'+ next +'ème photo : <input name="picture[]" type="file" />');
			pictureCount++;
			$("#pictureCount").val(pictureCount);
			if(pictureCount > 2){
				$("#addPicture").off();
				$("#addPicture").hide();
			}
		}
	});
});
</script>
</body>
</html>