<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/dateTimePicker.min.js"></script>
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

    if ($('#calendar').length > 0) {
        $('#calendar').calendar({
            adapter: 'inc/calendar.php?id=<?=$_GET['id']?>',
            day_first: 1,
            day_name: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            month_name: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            num_next_month: 2,
            onSelectDate: function(date, month, year){
                alert([year, month, date].join('-') + ' is: ' + this.isAvailable(date, month, year));
            },

        });
    }
});
</script>
</body>
</html>