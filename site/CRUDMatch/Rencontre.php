<?php

/**
 * Classe manipulant un match (baptisé Rencontre car match est un mot-clé PHP)
 *
 */
class Rencontre
{
	private $equipe_locale;
	private $domicile_exterieur;
	private $equipe_adverse;
	private $date_heure;
	private $numJournee;
	private $num_semaine;
	private $gymnase;

	/**
	 * fonction pour afficher un match sous forme de ligne d'un tableau
	 */
	public function afficherPourTableau()
	{
			echo "
							<tr>
									<td>" . $this->equipe_locale . "</td>
									<td>" . $this->domicile_exterieur . "</td>
									<td>" . $this->equipe_adverse . "</td>
									<td>" . $this->date_heure . "</td>
									<td>" . $this->num_semaine . "</td>
									<td>" . $this->numJournee . "</td>

							</tr>
					";
	}
	/**
	 * fonction pour ajouter un match à la BD
	 */
	public function add()
	{
		if ($this->equipe_locale == null || $this->domicile_exterieur == null || $this->equipe_adverse == null )
		{
			echo "<p>equipe_locale : " . $this->equipe_locale;
			echo "<br/>domicile_exterieur : " . $this->domicile_exterieur;
			echo "<br/>equipe_adverse : " . $this->equipe_adverse;
			echo "<br/>date_heure : " . $this->date_heure;
			echo "<br/>num_semaine : " . $this->num_semaine;
			echo "<br/>gymnase : " . $this->gymnase . "<br/></p>";
			
			echo "<p>Erreur, entrez toutes les valeurs d'un match<br/></p>";
		}
		else
		{
			$bd = MaBD::getInstance ();
            // On regarde si le match existe déjà
			$stmt = $bd->prepare ( "select * from Matches where equipe_locale = :equipe_locale and  equipe_adverse = :equipe_adverse and num_journee = :num_journee");
			$assoc = array (
					':equipe_locale' => $this->equipe_locale,
					':equipe_adverse' => $this->equipe_adverse,
					':num_journee' => $this->numJournee
			);
			$stmt->execute ( $assoc );
            $leMatch = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($leMatch) {

                $stmt = $bd->prepare ( "update Matches set domicile_exterieur = :domicile_exterieur, date_heure = :date_heure, num_semaine = :num_semaine, gymnase = :gymnase where id_match = " . $leMatch['id_match']);

                $assoc = array (
                    ':domicile_exterieur' => $this->domicile_exterieur,
                    ':date_heure' => $this->date_heure,
                    ':num_semaine' => $this->num_semaine,
                    ':gymnase' => $this->gymnase 
                );

                if ($stmt->execute ( $assoc ))
                    echo "<p>Semaine $this->num_semaine, le match " . $this->equipe_locale . " contre " . $this->equipe_adverse . " a bien été mis à jour.<br/></p>";
                else 
                    echo "<p>Erreur à la mise à jour du match (Semaine $this->num_semaine) $this->equipe_locale contre $this->equipe_adverse.<br/></p>";
            } else {
                
                $stmt = $bd->prepare ( "insert into Matches (equipe_locale, domicile_exterieur, equipe_adverse, date_heure, num_semaine, num_journee, gymnase)
                    Values (:equipe_locale, :domicile_exterieur, :equipe_adverse, :date_heure, :num_semaine, :num_journee, :gymnase);" );
                
                $assoc = array (
                        ':equipe_locale' => $this->equipe_locale,
                        ':domicile_exterieur' => $this->domicile_exterieur,
                        ':equipe_adverse' => $this->equipe_adverse,
                        ':date_heure' => $this->date_heure,
                        ':num_semaine' => $this->num_semaine,
                        ':num_journee' => $this->numJournee,
                        ':gymnase' => $this->gymnase 
                );
                
                if ($stmt->execute ( $assoc ))
                    echo "<p>Semaine $this->num_semaine, le match " . $this->equipe_locale . " contre " . $this->equipe_adverse . " a bien été ajouté.<br/></p>";
                else 
                    echo "<p>Erreur à l'ajout de du match (Semaine $this->num_semaine) $this->equipe_locale contre $this->equipe_adverse.<br/></p>";
            }
		}
	}

	/**
	 * fonction pour afficher le match
	 */
	public function afficher()
	{
		echo "<table>
				<tr><th>equipe_locale</th><th>domicile_exterieur</th><th>equipe_adverse</th><th>date_heure</th><th>num_semaine</th><th>gymnase</th></tr>
				<tr><td>" . $this->equipe_locale . "</td><td>" . $this->domicile_exterieur . "</td><td>" . $this->equipe_adverse . "</td>
					<td>" . $this->date_heure . "</td><td>" . $this->num_semaine . "</td><td>" . $this->gymnase . "</td></tr>
			</table>";
	}

	/**
	 * fonction pour mettre à jour un parametre du match de la BD
	 *
	 * @param unknown $id        	
	 * @param unknown $param        	
	 * @param unknown $val        	
	 */
	static function update($id_match, $equipe_locale, $domicile_exterieur, $equipe_adverse, $date_heure, $num_semaine, $gymnase)
	{
		echo '<br/>';
		
		$bd = MaBD::getInstance ();
		// equipe locale
		if ($equipe_locale != "Equipes ...")
		{
			$stmt = $bd->prepare ( "update Matches set equipe_locale = :equipe_locale where id_match = :id_match;" );
			
			$stmt->execute ( array (
					':equipe_locale' => $equipe_locale,
					':id_match' => $id_match 
			) );
			
			echo "<p class=\"resMatch\">Le match a maintenant pour equipe_locale: " . $equipe_locale . ".<br/>";
		}
		
		// domicile ou exterieur
		if ($domicile_exterieur != "notModified")
		{
			$stmt = $bd->prepare ( "update Matches set domicile_exterieur = :domicile_exterieur where id_match = :id_match;" );
			
			$stmt->execute ( array (
					':domicile_exterieur' => $domicile_exterieur,
					':id_match' => $id_match 
			) );
			
			// on considere à domicile lorsque $domicile_exterieur==true (1)
			if ($domicile_exterieur)
			{
				echo "Le match est maintenant à domicile.<br/>";
			}
			else
			{
				echo "Le match est maintenant à l'exterieur.<br/>";
			}
		}
		
		// equipe adverse
		$stmt = $bd->prepare ( "update Matches set equipe_adverse = :equipe_adverse where id_match = :id_match;" );
		
		$stmt->execute ( array (
				':equipe_adverse' => $equipe_adverse,
				':id_match' => $id_match 
		) );
		
		echo "Le match a maintenant pour equipe_adverse : " . $equipe_adverse . ".<br/>";
		
		// date et heure
		$stmt = $bd->prepare ( "update Matches set date_heure = :date_heure where id_match = :id_match;" );
		
		$stmt->execute ( array (
				':date_heure' => $date_heure,
				':id_match' => $id_match 
		) );
		
		echo "Le match a maintenant pour date_heure : " . $date_heure . ".<br/>";
		
		// numero de la semaine
		$stmt = $bd->prepare ( "update Matches set num_semaine = :num_semaine where id_match = :id_match;" );
		
		$stmt->execute ( array (
				':num_semaine' => $num_semaine,
				':id_match' => $id_match 
		) );
		
		echo "Le match a maintenant pour num_semaine : " . $num_semaine . ".<br/>";
		
		// gymnase
        $stmt = $bd->prepare ( "update Matches set gymnase = :gymnase where id_match = :id_match;" );

        $stmt->execute ( array (
            ':gymnase' => $gymnase,
            ':id_match' => $id_match 
        ) );

        echo "Le match a maintenant pour gymnase : " . $gymnase . ".<br/></p>";
	}

	/**
	 * fonction pour supprimer un match de la BD
	 *
	 * @param unknown $monId        	
	 */
	static function delete($monId)
	{
		$bd = MaBD::getInstance ();
		$stmt = $bd->prepare ( "delete from Matches where id_match = :monId" );
		
		$stmt->execute ( array (
				':monId' => $monId 
		) );
		
		echo "<p>le match numero " . $monId . " a bien ete supprime.<br/></p>";
	}

	/**
	 * contructeur de la classe
	 *
	 * @param unknown $equipe_locale        	
	 * @param unknown $domicile_exterieur        	
	 * @param unknown $equipe_adverse        	
	 * @param unknown $date_heure        	
	 * @param unknown $num_semaine        	
	 * @param unknown $gymnase        	
	 */
	public function __construct($equipe_locale, $domicile_exterieur, $equipe_adverse, $date_heure, $num_semaine, $numJournee, $gymnase)
	{
		$this->equipe_locale = $equipe_locale;
		$this->domicile_exterieur = $domicile_exterieur;
		$this->equipe_adverse = $equipe_adverse;
		$this->date_heure = $date_heure;
		$this->num_semaine = $num_semaine;
		$this->numJournee = $numJournee;
		$this->gymnase = $gymnase;
	}
}
?>
