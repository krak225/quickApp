$('document').ready(function(){
	$('#pays_id').change(function(){
		var pays_id = $(this).val();
		$('#villes_id').load('modules/listeVille.php?pays_id='+pays_id);
	});	
});

function cocherTout(nbre)
	{
		$('#checkallbox').html('<span onClick="decocherTout(100)"><img src="images/checkbox-checked.png" style="height:30px;"/></span>');	
		$('#checkallbox-h').html('<span onClick="decocherTout(100)"><img src="images/checkbox-checked.png" style="height:30px;"/></span>');	
		for (i=1;i <=nbre;i++)
		{
			document.getElementById('chk_'+i).checked=true;
		}
	}
	//Cette fonction permet de décocher tout les messages		
	function decocherTout(nbre)
	{
		$('#checkallbox').html('<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>');
		$('#checkallbox-h').html('<span onClick="cocherTout(100)"><img src="images/checkbox-unchecked.png" style="height:30px;"/></span>');
		for (i=1;i <=nbre;i++)
		{
			document.getElementById('chk_'+i).checked=false;
		}			
	}
