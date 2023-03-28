<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\Matches;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficherController extends AbstractController
{
    #[Route('/afficher/Liste_equipe', name: 'show_TeamList')]
    public function showTeamList(ManagerRegistry $doctrine): Response{
        $repo = $doctrine->getRepository(Equipes::class);
        $lesEquipes = $repo->findAll();
        return $this->render('afficher/listEquipe.html.twig', [
            'equipes' => $lesEquipes,
        ]);
    }
    #[Route('/afficher/equipe/{libelle}', name: 'show_team')]
    public function showTeam(ManagerRegistry $doctrine, string $libelle): Response{
        $repo = $doctrine->getRepository(Equipes::class);
        $equipe = $repo->find($libelle);

        $repo_match=$doctrine->getRepository(Matches::class);
        $match = $repo_match->getMatchesFromLibelle($equipe->getLibelle());
        return $this->render('afficher/showOneTeam.html.twig', [
            'equipe' => $equipe,
            'match' => $match
        ]);
    }

    #[Route('/show/MatchList', name: 'show_MatchList')]
    public function showMatchList(ManagerRegistry $doctrine_match): Response{
        $repo_match = $doctrine_match->getRepository(Matches::class);
        $Matches = $repo_match->findAll();
        return $this->render('afficher/listMatch.html.twig', [
            'match' =>$Matches,
        ]);
    }

    #[Route('/show/oneMatch/{id_match}', name: 'show_oneMatch')]
    public function showOneMatch(ManagerRegistry $doctrine_match, int $id_match): Response{
        $repo_match = $doctrine_match->getRepository(Matches::class);
        $matche = $repo_match->find($id_match);

        $repo_equipe = $doctrine_match->getRepository(Equipes::class);
        $equipe = $repo_equipe->find($matche->getEquipeLocale());
        return $this->render('afficher/showOneMatch.html.twig', [
            'equipe' => $equipe,
            'match' =>$matche
        ]);
    }

    #[Route('/', name: 'app_init')]
    public function init(ManagerRegistry $doctrine_match): Response{
        $repo_match = $doctrine_match->getRepository(Matches::class);
        $matches_joues = $repo_match->get3LastMatch();
        $match_next = $repo_match->get3NextMatch();
        return $this->render('afficher/init.html.twig', [
            'matches_joues' => $matches_joues,
            'matches_a_venir' => $match_next
        ]);
    }

    #[Route('/show/planning', name: 'show_planning')]
    public function planning(ManagerRegistry $doctrine_match): Response{
        $repo_match = $doctrine_match->getRepository(Matches::class);
        $matches = $repo_match->findAll();
        $liste_match = [];
        foreach ($matches as $match){
            $temps_debut = new \DateTime($match->getDateHeure()->format('Y-M-d H:i:s'));
            $temps_fin = $match->getDateHeure()->modify('+1 hour');
            $liste_match[] = [
              'id' => $match->getId(),
              'start' => $temps_debut->format('Y-M-d H:i:s'),
                'end' => $temps_fin->format('Y-M-d H:i:s'),
                'title' => $match->getEquipeLocale(), $match->getEquipeAdverse()
            ];
        }

        $data = json_encode($liste_match);
        return $this->render('afficher/planning.html.twig', compact('data'));
    }

    #[Route('/contact', name: 'show_contact')]
    public function contact(): Response{
        return $this->render('/contact.html.twig');
    }
}
