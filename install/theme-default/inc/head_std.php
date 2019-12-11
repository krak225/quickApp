<?php require_once('inc/head.php');?>

			<div id="ecole">
				<table>
				<tr>
					<td>
						<div id="sousmenu">
							<div class="r-box" id="r-box-1">
								<div class="box-title"><div class="blue" style="text-transform:;">INFO ADMINISTRATEUR</div></div>
								<div class="box-content">
									<div class="newsbox">
										<ul>
										<?php
											if(isset($_SESSION['administrateur'])){
												print '<li style="border:none;">Login: <span style="color:black;font-weight:bold;">'.$_SESSION['administrateur'].'</span></li>';
												print '<li style="border:none;"><a href="deconnexion.php">Déconnexion</a></li>';
											} 
										?>
										</ul>
									</div>
								</div>
							</div>
							
							<?php if($menu->hasSousMenu(getPage())){ ?>
							<div class="r-box" id="r-box-2">
								<div class="box-title"><div class="orange" style="text-transform:;"><!--MENU--> <?php print $menu->getMenuLabel(getPage());?></div></div>
								<div class="box-content">
									<div class="newsbox">
										<ul id="">
											<?php 
											$menu->insertSousMenu(getPage());
											?>
										</ul>
									</div>
								</div>
							</div>
							
							<?php } ?>
							
							
							<div class="r-box" id="r-box-3">
								<div class="box-title"><div class="brown" style="text-transform:;">Publicité</div></div>
								<div class="box-content">
									<div class="newsbox">
										<ul>
										
										</ul>
									</div>
								</div>
							</div>
						</div>
					</td>
					
					<td>
					