<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\Matches;
use App\Form\EquipesType;
use App\Form\MatchesType;
use App\Repository\EquipesRepository;
use App\Repository\MatchesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjouterController extends AbstractController
{
    #[Route('/ajouter/team', name: 'add_team', methods:['GET','POST'])]
    public function addTeam(Request $request, EquipesRepository $equipesRepository): Response{

        $equipe = new Equipes();
        $form = $this->createForm(EquipesType::class, $equipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $equipesRepository->save($equipe, true);
            return $this->redirectToRoute('show_TeamList');
        }
        return $this->render('ajouter/addTeam.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/ajouter/match', name: 'add_match', methods:['GET','POST'])]
    public function addMatch(Request $request, MatchesRepository $matchesRepository): Response{

        $match = new Matches();
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $matchesRepository->save($match, true);
            return $this->redirectToRoute('show_MatchList');
        }
        return $this->render('ajouter/addMatch.html.twig', [
            'form' => $form,
        ]);
    }
}
