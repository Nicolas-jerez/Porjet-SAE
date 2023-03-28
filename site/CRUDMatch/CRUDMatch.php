<?php
/**
 * Classe permettant de créer des listes déroulantes
 *
 * @author chauvetc
 */
class CRUDMatch
{

	static function listeDomicileOuExterieur($value = 1)
	{
        echo '<select name="listeDomicileOuExterieur">';
        printf('<option value="1"%s>Domicile</option>', ($value == 1)?' selected="selected"':'');
        printf('<option value="0"%s>Extérieur</option>', ($value == 0)?' selected="selected"':'');
	    echo "</select>\n";
	}

	static function listeMatches($monForm)
	{
		$bd = MaBD::getInstance ();
		$stmt = $bd->prepare ( "select * from Matches;" );
		$stmt->execute ();
		
		echo "<select name=\"listeMatches\" onchange=\"document.getElementById('" . $monForm . "').submit();\">";
		echo "<option>Matches ...</option>";
		
		while ( $row = $stmt->fetch ( PDO::FETCH_ASSOC ) )
		{
			echo "<option value = " . $row ['id_match'] . ">" . $row ['equipe_locale'] . " contre " . $row ['equipe_adverse'] . " le " . $row ['date_heure'] . "</option>";
		}
		
		echo "</select>";
	}

	static function ajoutMatch($monForm)
	{		
		if (! isset ( $_POST ['equipe_locale'] ) && ! isset ( $_POST ['domicile_exterieur'] ) && ! isset ( $_POST ['equipe_adverse'] ) && ! isset ( $_POST ['date_heure'] ) && ! isset ( $_POST ['num_semaine'] ) && ! isset ( $_POST ['gymnase'] ))
		{
			echo '
		<table class="match"><tr><td>
				Equipe locale : ';
			$bd = MaBD::getInstance ();
			$stmt = $bd->prepare ( "select libelle from Equipes;" );
			$stmt->execute ();
			
			echo '<select name="listeEquipes" >';
			echo "<option>Equipes ...</option>";
			
			while ( $row = $stmt->fetch () )
			{
				echo "<option value='" . $row [0] . "'>" . $row [0] . "</option>";
			}
			
			echo "</select>";
			
			echo '</td>
		<td>Domicile : ';
			CRUDMatch::listeDomicileOuExterieur ();
			echo '</td></tr>
		<tr><td>Equipe adverse : <input type="text" name="equipe_adverse" value=""></td>
        <td>Numéro de la semaine : <input type="text" name="num_semaine" value=""></td>
        <td>Numéro de journée : <input type="text" name="num_journee" value=""></td>
        </tr>
		<tr><td>Date : <input type="text" id="date" name="date" value="" placeholder="aaaa-mm-jj"></td>
		<td>Heure : <input type="text" id="heure" name="heure" value="" placeholder="hh:mm"></td>
		<td>Gymnase : ';
            echo '<input type="text" name="listeGymnases" placeholder="gymnase" value=""/>';
			echo ' </td></tr>
		</table>
		<input type="submit" value="Valider" name="valider"/>';
		}
		else
		{
			$equipe_locale = $_POST ['listeEquipes'];
			$domicile_exterieur = $_POST ['listeDomicileOuExterieur'];
			$equipe_adverse = $_POST ['equipe_adverse'];
			$date = $_POST['date'];
			$heure = $_POST ['heure'];
			$date_heure = $date . " " . $heure;
			$num_semaine = $_POST ['num_semaine'];
			$num_journee = $_POST ['num_journee'];
			$gymnase = $_POST ['listeGymnases'];
			$monMatch = new Rencontre( $equipe_locale, $domicile_exterieur, $equipe_adverse, $date_heure, $num_semaine, $num_journee, $gymnase );
			$monMatch->add ();
		}
	}

	static function modifMatch($monForm)
	{		
		if (isset ( $_POST ['listeMatches'] ) || isset ( $_POST ['listeEquipes'] ))
		{
			if (! isset ( $_POST ['listeEquipes'] ) && ! isset ( $_POST ['domicile_exterieur'] ) && ! isset ( $_POST ['equipe_adverse'] ) && ! isset ( $_POST ['date_heure'] ) && ! isset ( $_POST ['num_semaine'] ) && ! isset ( $_POST ['gymnase'] ))
			{
				$id_match = $_POST ['listeMatches'];
				
				$bd = MaBD::getInstance ();
				$stmt = $bd->prepare ( "select * from Matches where id_match = '" . $id_match . "';" );
				$stmt->execute ();
				$match = $stmt->fetch (PDO::FETCH_ASSOC);
				
				$tmpDateHeure = explode ( " ", $match ['date_heure'] );
				
				$date = $tmpDateHeure [0];
				$heure = $tmpDateHeure [1];
				
				echo '
		<table class="match"><tr><td>
				Equipe locale : ';
				$bd = MaBD::getInstance ();
				$stmt = $bd->prepare ( "select libelle from Equipes;" );
				$stmt->execute ();
				
				echo '<select name="listeEquipes" >';
				
				while ( $row = $stmt->fetch (PDO::FETCH_NUM) )
				{
                    echo '<option value="' . $row [0] . '"';
                    if ($row[0] == $match['equipe_locale']) echo ' selected="selected"';
                    echo '>' . $row [0] . "</option>";
				}
				
				echo "</select>";
				
				echo '</td>
		<td>Domicile ou extérieur : ';
				CRUDMatch::listeDomicileOuExterieur ($match['domicile_exterieur']);
				echo '</td></tr>
		<tr><td>Equipe adverse : <input type="text" name="equipe_adverse" value="' . $match ['equipe_adverse'] . '"></td>
        <td>Numéro de la semaine : <input type="text" name="num_semaine" value="' . $match ['num_semaine'] . '"></td>
        </tr>
		<tr><td>Date : <input type="text" id="date" name="date" value="' . $date . '" placeholder="aaaa-mm-jj"></td>
		<td>Heure : <input type="text" id="heure" name="heure" value="' . $heure . '"></td>			
		<td>Gymnase : ';
                echo '<input type="text" name="listeGymnases" placeholder="gymnase" value="' . $match['gymnase'] . '"/>';
				echo '</td></tr>
		</table>
		<input type="submit" value="Valider" name="valider"/>
		<input type="hidden" name="id_match" value="' . $id_match . '">';
			}
			else
			{
				$id_match = $_POST ['id_match'];
				$equipe_locale = $_POST ['listeEquipes'];
				$domicile_exterieur = $_POST ['listeDomicileOuExterieur'];
				$equipe_adverse = $_POST ['equipe_adverse'];
				$date = $_POST['date'];
				$heure = $_POST ['heure'];
				$date_heure = $date . " " . $heure;
				$num_semaine = $_POST ['num_semaine'];
				$gymnase = $_POST ['listeGymnases'];
				CRUDMatch::listeMatches ( $monForm );
				Rencontre::update ( $id_match, $equipe_locale, $domicile_exterieur, $equipe_adverse, $date_heure, $num_semaine, $gymnase );
			}
		}
		else
		{
			CRUDMatch::listeMatches ( $monForm );
		}
	}

    static function supMatch($monForm)
    {		
        if (isset ( $_POST ['id_match'] ) && isset ( $_POST ['valider'] )) {
            $id_match = $_POST ['id_match'];
            Rencontre::delete ( $id_match );
            CRUDMatch::listeMatches ( $monForm );
        } else if (isset ( $_POST ['listeMatches'] )) {
            $id_match = $_POST ['listeMatches'];
            $bd = MaBD::getInstance ();
            $stmt = $bd->prepare ( "select * from Matches where id_match = '" . $id_match . "';" );
            $stmt->execute ();
            $match = $stmt->fetch(PDO::FETCH_ASSOC);

            echo ' <h2>Match : ';
            echo $match['equipe_locale'] . " / " . $match['equipe_adverse'];
            echo '<h2>';
            $dt = explode(' ', $match['date_heure']);
            echo '<p>Le ' . $dt[0] . ' à ' . $dt[1] . '</p>';
            echo '<p>Match à ';
            echo ($match['domicile_exterieur'])?'domicile':"l'extérieur";
            echo ' (' . $match['gymnase'] . ')</p>';
            echo '<p><input type="submit" value="Supprimer le match ?" name="valider"/>
                <input type="hidden" name="id_match" value="' . $id_match . '"></p>';
        }
        else
        {
            CRUDMatch::listeMatches ( $monForm );
        }
    }

	static function afficherMatch($monForm)
	{
		CRUDMatch::listeMatches ( $monForm );
		
		if (isset ( $_POST ['listeMatches'] ))
		{
			$id_match = $_POST ['listeMatches'];
			
			$bd = MaBD::getInstance ();
			$stmt = $bd->prepare ( "select * from Matches where id_match = '" . $id_match . "';" );
			$stmt->execute ();
			$table = $stmt->fetchAll ();
			
			echo '
		<table class="match">
		<tr><td>Equipe locale : ';
			if ($table [0] [1])
			{
				echo '<input type="text" readonly="readonly" name="domicile_exterieur" value="domicile">';
			}
			else
			{
				echo '<input type="text" readonly="readonly" name="domicile_exterieur" value="exterieur">';
			}
			echo '</td>
		<td>Domicile ou extérieur : <input type="text" readonly="readonly" name="domicile_exterieur" value="' . $table [0] [2] . '"></td></tr>
		<tr><td>Equipe adverse : <input type="text" readonly="readonly" name="equipe_adverse" value="' . $table [0] [3] . '"></td>
		<td>Date et heure : <input type="text" readonly="readonly" name="date_heure" value="' . $table [0] [4] . '"></td></tr>
		<tr><td>Numéro de la semaine : <input type="text" readonly="readonly" name="num_semaine" value="' . $table [0] [5] . '"></td>
		<td>Gymnase : <input type="text" readonly="readonly" name="gymnase" value="' . $table [0] [6] . '"></td></tr>
		</table>';
		}
	}
}
