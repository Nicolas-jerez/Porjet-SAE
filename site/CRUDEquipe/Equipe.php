<?php
/**
 * Classe manipulant une equipe
 *
 */
class Equipe
{
	private $libelle;
	private $entraineur;
	private $creneaux;
	private $url_photo;
	private $url_result_calendrier;
	private $commentaire;

	/**
	 * fonction pour ajouter l'equipe à la BD
	 */
	public function add()
	{
		if ($this->libelle == null || $this->entraineur == null || $this->creneaux == null || $this->url_photo == null || $this->url_result_calendrier == null || $this->commentaire == null)
		{
			echo "<p>libelle : " . $this->libelle;
			echo "entraineur : " . $this->entraineur;
			echo "creneaux : " . $this->creneaux;
			echo "url_photo : " . $this->url_photo;
			echo "url_result_calendrier : " . $this->url_result_calendrier;
			echo "commentaire : " . $this->commentaire . "<br/></p>";
			
			echo "<p>Erreur, entrez toutes les valeurs d'une equipe<br/></p>";
		}
		else
		{
			$bd = MaBD::getInstance ();
			$stmt = $bd->prepare ( "insert into Equipes Values (:libelle, :entraineur, :creneaux, 
						:url_photo, :url_res, :commentaire);" );
			
			$assoc = array (
					':libelle' => $this->libelle,
					':entraineur' => $this->entraineur,
					':creneaux' => $this->creneaux,
					':url_photo' => $this->url_photo,
					':url_res' => $this->url_result_calendrier,
					':commentaire' => $this->commentaire 
			);
			
			$stmt->execute ( $assoc );
			
			echo "<p>L'equipe " . $this->libelle . " a bien ete inseree<br/></p>";
		}
	}

	/**
	 * fonction pour afficher l'equipe
	 */
	public function afficher()
	{
		echo "<table>
				<tr><th>libelle</th><th>entraineur</th><th>creneaux</th><th>url_photo</th><th>url_result_calendrier</th><th>commentaire</th></tr>
				<tr><td>" . $this->libelle . "</td><td>" . $this->entraineur . "</td><td>" . $this->creneaux . "</td>
					<td>" . $this->url_photo . "</td><td>" . $this->url_result_calendrier . "</td><td>" . $this->commentaire . "</td></tr>
			</table>";
	}

	/**
	 * fonction pour mettre à jour un parametre de l'equipe de la BD
	 *
	 * @param unknown $libelle        	
	 * @param unknown $param        	
	 * @param unknown $val        	
	 */
	static function update($old_libelle, $new_libelle, $entraineur, $creneaux, $url_photo, $url_res, $commentaire)
	{
		$bd = MaBD::getInstance ();
		if ($old_libelle != $new_libelle)
		{
			// libelle
			$stmt = $bd->prepare ( "update Equipes set libelle = :newLibelle where libelle = :oldLibelle;" );
			
			$stmt->execute ( array (
					':newLibelle' => $new_libelle,
					':oldLibelle' => $old_libelle 
			) );
			echo "<p class=\"resEquipe\">L'equipe a maintenant pour libelle : " . $new_libelle . ".<br/>";
		}
		else
		{
			echo "<p class=\"resEquipe\">Le libelle de l'equipe ne change pas.<br/>";
		}
		
		// entraineur
		$stmt = $bd->prepare ( "update Equipes set entraineur = :entraineur where libelle = :oldLibelle;" );
		
		$stmt->execute ( array (
				':entraineur' => $entraineur,
				':oldLibelle' => $old_libelle 
		) );
		
		echo "L'equipe a maintenant pour entraineur : " . $entraineur . ".<br/>";
		
		// creneaux
		$stmt = $bd->prepare ( "update Equipes set creneaux = :creneaux where libelle = :oldLibelle;" );
		
		$stmt->execute ( array (
				':creneaux' => $creneaux,
				':oldLibelle' => $old_libelle 
		) );
		
		echo "L'equipe a maintenant pour creneaux : " . $creneaux . ".<br/>";
		
		// url_photo
		$stmt = $bd->prepare ( "update Equipes set url_photo = :url_photo where libelle = :oldLibelle;" );
		
		$stmt->execute ( array (
				':url_photo' => $url_photo,
				':oldLibelle' => $old_libelle 
		) );
		
		echo "L'equipe a maintenant pour photo : " . $url_photo . ".<br/>";
		
		// resultat calendrier
		$stmt = $bd->prepare ( "update Equipes set url_result_calendrier = :url_res where libelle = :oldLibelle;" );
		
		$stmt->execute ( array (
				':url_res' => $url_res,
				':oldLibelle' => $old_libelle 
		) );
		
		echo "L'equipe a maintenant pour url_result_calendrier : " . $url_res . ".<br/>";
		
		// commentaire
		$stmt = $bd->prepare ( "update Equipes set commentaire = :commentaire where libelle = :oldLibelle;" );
		
		$stmt->execute ( array (
				':commentaire' => $commentaire,
				':oldLibelle' => $old_libelle 
		) );
		
		echo "L'equipe a maintenant pour commentaire : " . $commentaire . ".<br/></p>";
	}

	/**
	 * fonction pour supprimer une equipe de la BD
	 *
	 * @param unknown $monLibelle        	
	 */
	static function delete($monLibelle)
	{
		$bd = MaBD::getInstance ();
		$stmt = $bd->prepare ( "delete from Equipes where libelle = :monLibelle;" );
		
		$stmt->execute ( array (
				':monLibelle' => $monLibelle 
		) );
		
		echo "<p>L'equipe " . $monLibelle . " a bien ete supprimee.<br/></p>";
	}

	/**
	 * constructeur de la classe
	 *
	 * @param unknown $libelle        	
	 * @param unknown $entraineur        	
	 * @param unknown $creneaux        	
	 * @param unknown $url_photo        	
	 * @param unknown $url_result_calendrier        	
	 * @param unknown $commentaire        	
	 */
	public function __construct($libelle, $entraineur, $creneaux, $url_photo, $url_result_calendrier, $commentaire)
	{
		$this->libelle = $libelle;
		$this->entraineur = $entraineur;
		$this->creneaux = $creneaux;
		$this->url_photo = $url_photo;
		$this->url_result_calendrier = $url_result_calendrier;
		$this->commentaire = $commentaire;
	}
}
?>
