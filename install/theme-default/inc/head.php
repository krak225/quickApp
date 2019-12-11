<?php
session_start();
require_once('core/dataLoader.php');
if(!isset($_SESSION['administrateur'])){
	header('location:AccesForm.php');
}
?>
<html>
<head>
	<title>A QUICKAPP APPLICATION</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="generator" content="krak Technologies"/>
	<link href="css/kw-admin.css" rel="stylesheet" media="all">
	<link href="css/standard.css" rel="stylesheet" media="all">
	<link href="css/kw-form.css" rel="stylesheet" media="all">
	<link href="css/nav.css" rel="stylesheet" media="all">
	<link href="modules/wysiwyg/jquery.wysiwyg.css" rel="stylesheet"  media="all"/>
	<link href="css/pagination.css" rel="stylesheet"  media="all"/>
	
	<script type="text/javascript" src="js/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="modules/wysiwyg/jquery.wysiwyg.js"></script>	
	<script type="text/javascript" src="js/fn_perso.js"></script>
	<!-- noty -->
	<script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script type="text/javascript">

		function confirmSuppression(table,primarykey,value) {
			var n = noty({
				text        : 'Voulez-vous vraiment supprimer cet enregistrement?',
				type        : 'warning',
				dismissQueue: true,
				layout      : 'center',
				theme       : 'defaultTheme',
				buttons     : [
					{addClass: 'btn btn-primary', text: 'Supprimer', onClick: function ($noty) {
						$noty.close();
						//effectuer la suppression
						supprimer(table,primarykey,value);
					}
					},
					{addClass: 'btn btn-danger', text: 'Annuler', onClick: function ($noty) {
						//annuler la suppression
						$noty.close();
					}
					}
				]
			});
			//console.log('html: ' + n.options.id);
		}

		
		function supprimer(table,primarykey,value){
			$.ajax({
				type: "POST",
				data:{table:table,primarykey:primarykey,value:value,suppr:'krak'},
				url: "app/suppr.php",
				success: function(e){
					if(e==1){
						noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Enregistrement supprimé avec succès', type: 'success'});
						$("#tr-"+value).hide();
					}else{
						noty({dismissQueue: true, force: true, layout: 'center', theme: 'defaultTheme', text: 'Erreur lors de la suppession', type: 'error'});
					}
				},
				error:function(){
					alert("erreur ");
				}
			});
		}
		
		
		
		$(document).ready(function () {
			//événement click sur un bouton supprimer
			$(".btn_supprimer").click(function(){
				var table=$(this).attr("data-table");
				var primarykey=$(this).attr("data-primarykey");
				var value=$(this).attr("data-value");
				confirmSuppression(table,primarykey,value);
			});
			
			//Gestion de la zone de recherche
			$("#btn_open_search_box").click(function(){
				$("#form-search").toggle(1000);
			});
			
		});

	</script>
	<!-- noty -->
</head>
<body>
	<div class="krakWebBuilder" id="adminApp">
		<div class="includes">
		<?php
		require_once('fonctions/fonctions.php');//connexionDB();
		require_once('classes/kMenu.php');
		require_once('classes/classkrakNewPaginer1.1.php');
		require_once('classes/classkrakVerification.php');
		?>
		</div>
		<div class="row" id="head">
			<div id="banniere">
			<div class="container">
				<div id="customContainer"></div>
			</div>
			</div>
			
			<div class="row" id="NouveauMenu">
				<nav>
				<ul>
					<?php
					//le menu
					$menu=new kMenu();
					$menu->insertMenu();
					?>
					<br style="clear:both;"/>
				</ul>
				</nav>
			</div>
			
			
			<div class="clear"></div>
		

			<div style="height:15px;"></div>
			<div id="flash-info">
				<table>
					<tr>
						<td><div class="label">FLASH INFO</div></td>
						<td><div class="text"><marquee behavior="scroll" scrollamount="3">quickApp est une application developpé par Armand Kouassi</marquee></div></td>
					</tr>
				</table>
			</div>
		</div>
		
				
		<!-- -->
		<!--place du menu-->
		<!-- -->
		
		<div class="row" id="milieu">
		
