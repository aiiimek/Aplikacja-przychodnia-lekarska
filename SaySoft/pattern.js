pattern

Save: function(id, close)
    {
      $('#p-form .is-invalid').removeClass('is-invalid');

      var unindexed_array = $('#p-form :input').serializeArray();

      var saveData = {};

      $.map(unindexed_array, function(n, i){
          saveData[n['name']] = n['value'];
      });

      var ok = true;
      if (!Publisher.IsValidField(saveData, 'email')) ok = false;
        
      if (!ok) return;

      //Zapisz dane
      $('.preloader').show();
      $.post( "sites/publishers/save.php",{
        id:id, 
        data: JSON.stringify(saveData)
      })
      .done(function(data) {
        $('.preloader').hide();
        var json = JSON.parse(data);
        if (json.code != '1')
            Core.ShowAlert(json.txt,'error');
         else
           Core.ShowAlert(json.txt,'success',(close ? 'back' : function() {
            Publisher.Edit(json.id);
           }));
      })
      .fail(function(err) {
        $('.preloader').hide();
        Core.ShowAlert('Wewnętrzny błąd serwera. Nie można wyświetlić strony.','error');        
      });

    },