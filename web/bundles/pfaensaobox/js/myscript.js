$( "#filiere" ).change(function()
{
    if($( "#filiere option:selected").text() == 'STPI')
    {
        $('#niveau option[value="3"]').hide();
        $('#niveau option[value="1"]').attr("selected","selected");
    }
    else
    {
        $('#niveau option[value="3"]').show();
    }
});

