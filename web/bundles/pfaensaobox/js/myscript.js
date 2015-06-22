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

$( "#filiereu" ).change(function()
{
    if($( "#filiereu option:selected").text() == 'STPI')
    {
        $('#niveauu option[value="3"]').hide();
        $('#niveauu option[value="1"]').attr("selected","selected");
    }
    else
    {
        $('#niveauu option[value="3"]').show();
    }
});

$( "#form_filieres" ).change(function()
{
    if($( "#form_filieres option:selected").text() == 'STPI')
    {
        $('#form_classes option[value="3"]').hide();
        $('#form_classes option[value="1"]').attr("selected","selected");
    }
    else
    {
        $('#form_classes option[value="3"]').show();
    }
});

$( "#addd" ).click(function()
{
    $('#matiere').hide();
    $('#new').show();
    $('#addd').hide();
    $('#hide').show();
});

$('#hide').click(function()
{
    $('#matiere').show();
    $('#new').hide();
    $('#hide').hide();
    $('#addd').show();
});
