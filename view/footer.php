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
            num_next_month: 1,
            onSelectDate: function(date, month, year){
                if(this.isAvailable(date, month, year)){
                    var currentDate = new Date();
                    var selectedDate = new Date(year, month, date);
                    var $checkin = $("#checkin");
                    var $checkout = $("#checkout");
                    if($checkin.val() == ""){
                        if(selectedDate >= currentDate){
                            $checkin.val(year + '-' + month + '-' + date);
                        }
                        else{
                            alert("Erreur : La date de début de séjour doit être supérieur ou égale à la date d'aujourdhui !");
                        }
                    }
                    else{
                        var previousInput = $checkin.val().split("-");
                        var previousDate = new Date(previousInput[0], previousInput[1], previousInput[2]);
                        if(selectedDate >= previousDate){
                            $checkout.val(year + '-' + month + '-' + date);
                        }
                        else{
                            alert("Erreur : La date de fin de séjour doit être supérieur ou égale à la date de début !");
                        }
                    }
                }
            },
        });
    }
});
</script>
</body>
</html>