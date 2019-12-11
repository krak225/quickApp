<?php
class krakNewPaginer
{	
	public $sql;
	public $req;
	var $total_ligne;	
	public $url;
	public $param;
	public $paramLetter;
	public $total; 
	public $epp; 
	public $current;
	public $page; 
	public $start; 
	public $countp;
	public $prev; 
	public $next; 
	public $n2l;
	
function __construct()
{
	
}
//________________________________________________________________________________________________________________________________________________________________________//



//________________________________________________________________________________________________________________________________________________________________________//
//                      Affiche les numéros de pages                                                                                                                      //
//________________________________________________________________________________________________________________________________________________________________________//
	
function AfficherNumeros($adj=3)
{

	// Déclaration des variables 
	$this->prev = $this->current - 1; // numéro de la page précédente
	$this->next = $this->current + 1; // numéro de la page suivante
	$this->n2l = $this->total - 1; // numéro de l'avant-dernière page (n2l = next to last)

	//Initialisation : s'il n'y a pas au moins deux pages, l'affichage reste vide 
	$pagination = '';

	// Sinon ... 
	if ($this->total > 1)
	{
		//Concaténation du <div> d'ouverture à $pagination 
		$pagination .= '<div class="pagination">'."\n";

		// ////////// Début affichage du bouton [précédent] ////////// 
		if ($this->current == 2) // la page courante est la 2, le bouton renvoit donc sur la page 1, remarquez qu'il est inutile de mettre ?p=1
			$pagination .= '<a href="'.$this->url .'">Précédent</a>';
		elseif ($this->current > 2) // la page courante est supérieure à 2, le bouton renvoit sur la page dont le numéro est immédiatement inférieur
			$pagination .= '<a href="'.$this->url .''.$this->param .''.$this->prev .'">Précédent</a>';
		else // dans tous les autres, cas la page est 1 : désactivation du bouton [précédent]
			$pagination .= '<span class="inactive">Précédent</span>';
		// Fin affichage du bouton [précédent] 

		// ///////////////
		//Début affichage des pages, l'exemple reprend le cas de 3 numéros de pages adjacents (par défaut) de chaque côté du numéro courant
		//- CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
		//- CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 numéros de pages au total
		///////////////// 
		
		// CAS 1 
		if ($this->total < 7 + ($adj * 2))
		{	
			// Ajout de la page 1 : on la traite en dehors de la boucle pour n'avoir que index.php au lieu de index.php?p=1 et ainsi éviter le duplicate content 
			$pagination .= ($this->current == 1) ? '<span class="active">1</span>' : '<a href="'.$this->url .'">1</a>'; // Opérateur ternaire : (condition) ? 'valeur si vrai' : 'valeur si fausse'

			// Pour les pages restantes on utilise une boucle for 
			for ($i = 2; $i<=$this->total; $i++)
			{
				if ($i == $this->current) // Le numéro de la page courante est mis en évidence (cf fichier CSS)
				$pagination .= '<span class="active">'.$i.'</span>';
				else // Les autres sont affichés normalement
				$pagination .= '<a href="'.$this->url .''.$this->param .''.$i .'">'.$i.'</a>';
			}
		}

		// CAS 2 : au moins 13 pages, troncature 
		else
		{
			//
			//Troncature 1 : on se situe dans la partie proche des premières pages, on tronque donc la fin de la pagination.
			//l'affichage sera de neuf numéros de pages à gauche ... deux à droite (cf figure 1)
			////
			if ($this->current < 2 + ($adj * 2))
			{
				//Affichage du numéro de page 1 /
				$pagination .= ($this->current == 1) ? '<span class="active">1</span>' : '<a href="'.$this->url.'">1</a>';

				//puis des huit autres suivants /
				for ($i = 2; $i < 4 + ($adj * 2); $i++)
				{
				if ($i == $this->current)
					$pagination .= '<span class="active">'.$i.'</span>';
					else
					$pagination .= '<a href="'.$this->url .''. $this->param .'' .$i .'">'.$i.'</a>';
				}

				// ... pour marquer la troncature 
				$pagination .= ' ... ';

				// et enfin les deux derniers numéros 
				$pagination .= '<a href="'.$this->url .''. $this->param .''. $this->n2l ."\">". $this->n2l ."</a>";
				$pagination .= '<a href="'.$this->url .''. $this->param .''. $this->total ."\">". $this->total ."</a>";
			}

			//
			//Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le début et la fin de la pagination.
			//l'affichage sera deux numéros de pages à gauche ... sept au centre ... deux à droite (cf figure 2)
			//
			elseif ( (($adj * 2) + 1 < $this->current) && ($this->current < $this->total - ($adj * 2)) )
			{
				// Affichage des numéros 1 et 2 
				$pagination .= '<a href="'.$this->url .'">1</a>';
				$pagination .= '<a href="'.$this->url .''. $this->param . '2">2</a>';

				$pagination .= ' ... ';

				// les septs du milieu : les trois précédents la page courante, la page courante, puis les trois lui succédant 
				for ($i = $this->current - $adj; $i <= $this->current + $adj; $i++)
				{
					if ($i == $this->current)
					$pagination .= '<span class="active">'.$i."</span>";
					else
					$pagination .= "<a href=\"". $this->url .''. $this->param  .''. $i .'">'. $i ."</a>";
				}

				$pagination .= ' ... ';

				// et les deux derniers numéros 
				$pagination .= "<a href=\"".$this->url .''. $this->param .''. $this->n2l ."\">".$this->n2l ."</a>";
				$pagination .= "<a href=\"".$this->url .''. $this->param .''. $this->total ."\">".$this->total ."</a>";
			}

			//
			//Troncature 3 : on se situe dans la partie de droite, on tronque donc le début de la pagination.
			//l'affichage sera deux numéros de pages à gauche ... neuf à droite (cf figure 3)
			////
			else
			{
				// Affichage des numéros 1 et 2 
				$pagination .= "<a href=\"".$this->url ."\">1</a>";
				$pagination .= "<a href=\"".$this->url .''. $this->param ."2\">2</a>";

				$pagination .= ' ... ';

				// puis des neufs dernières 
				for ($i = $this->total - (2 + ($adj * 2)); $i <= $this->total; $i++)
				{
					if ($i == $this->current)
						$pagination .= '<span class="active">'.$i.'</span>';
					else
						$pagination .= '<a href="'.$this->url .''.$this->param .''.$i.'">'.$i.'</a>';
				}
			}
		}
		// Fin affichage des pages

		// ////////// Début affichage du bouton [suivant] ////////// 
		if ($this->current == $this->total)
			$pagination .= "<span class=\"inactive\">Suivant</span>\n";
		else
			$pagination .= '<a href="'. $this->url .''. $this->param .''. $this->next .'">Suivant</a>'."\n";
		// Fin affichage du bouton [suivant] 

		// </div> de fermeture
		$pagination .= "</div>\n";
	}

	// affiche $pagination
	echo $pagination;
}
//________________________________________________________________________________________________________________________________________________________________________//



//________________________________________________________________________________________________________________________________________________________________________//
//                      Générateur de la requète sql                                                                                                                      //
//________________________________________________________________________________________________________________________________________________________________________//

function GenererSql($sql,$url,$epp,$paramLetter='p')
{	

	GLOBAL $db;

	$tab_url=null;
	$this->sql=$sql;
	$this->url=$url;	
	$this->epp = $epp;
	$this->paramLetter=$paramLetter;
	//générer le paramètre ($this->$paramLetter (p par défaut))
	$tab_url=str_split($this->url);
	if(in_array("?",$tab_url)){ $this->param='&'.$this->paramLetter .'='; }
	else{ $this->param='?'.$this->paramLetter .'='; }
	
	
	// $this->req = mysql_query($this->sql);
	// $this->total_ligne = mysql_num_rows($this->req);
	
	$stm = $db->pdo->prepare($this->sql);
	$stm->execute();
	$this->total_ligne = $stm->rowCount();
	// print_r($this->total_ligne);die();
	// Libération du résultat 
	
	// Déclaration des variables
	$this->countp = ceil($this->total_ligne/$this->epp); // calcul du nombre de pages $countp (on arrondit à l'entier supérieur avec la fonction ceil() )
	//je change la variable countp en total
	$this->total=$this->countp;
	// Récupération du numéro de la page courante depuis l'URL avec la méthode GET
	if(!isset($_GET[$this->paramLetter]) || !is_numeric($_GET[$this->paramLetter]) ) // si $_GET['p'] n'existe pas OU $_GET['p'] n'est pas un nombre (petite sécurité supplémentaire)
		$this->current = 1; // la page courante devient 1
	else
	{
		$this->page = intval($_GET[$this->paramLetter]); // stockage de la valeur entière uniquement
		if ($this->page < 1) $this->current=1; // cas où le numéro de page est inférieure 1 : on affecte 1 à la page courante
		elseif ($this->page > $this->countp) $this->current=$this->countp; //cas où le numéro de page est supérieur au nombre total de pages : on affecte le numéro de la dernière page à la page courante
		else $this->current=$this->page; // sinon la page courante est bien celle indiquée dans l'URL
	}

	// $start est la valeur de départ du LIMIT dans notre requête SQL (est fonction de la page courante) 
	$this->start = ($this->current * $this->epp - $this->epp);

	// Récupération des données à afficher pour la page courante 
	$this->sql .= " LIMIT ".$this->start .','.$this->epp;

}

//________________________________________________________________________________________________________________________________________________________________________//



//________________________________________________________________________________________________________________________________________________________________________//
//                      Renvoi la requète                                                                                                                                 //
//________________________________________________________________________________________________________________________________________________________________________//

function RenvoiSQL()
{	
	return $this->sql; 
}

}
?>
