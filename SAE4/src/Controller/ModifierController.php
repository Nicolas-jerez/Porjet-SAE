<?php

namespace App\Controller;

use App\Entity\Equipes;
use App\Entity\Matches;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModifierController extends AbstractController
{
    #[Route('/modify/team/{libelle}', name: 'modify_team')]
    public function teamModification(Request $request, ManagerRegistry $doctrine, String $libelle): Response{
        $repo = $doctrine->getRepository(Equipes::class);
        $equipe = $repo->find($libelle);
        $form = $this->createFormBuilder($equipe)
            ->add('libelle', TextType::class)
            ->add('entraineur', TextType::class)
            ->add('creneaux', TextType::class, ['label' => 'crénaux','required' => false ])
            ->add('url_result_calendrier', TextType::class, [
                'required' => false,
            ])
            ->add('commentaire', TextType::class, ['required' => false])
            ->add('enregistrer', SubmitType::class)
            ->add('supprimer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'enregistrer') {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($equipe);
            $entityManager->flush();
            return $this->redirectToRoute('show_TeamList');
        }

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'supprimer') {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($equipe);
            $entityManager->flush();
            return $this->redirectToRoute('show_TeamList');
        }

        return $this->render('modify/modifyTeam.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/modify/match/{id}', name: 'modify_match')]
    public function matchModification(Request $request, ManagerRegistry $doctrine, int $id): Response{
            $repo = $doctrine->getRepository(Matches::class);
            $match = $repo->find($id);
            $form = $this->createFormBuilder($match)
                ->add('equipe_locale', TextType::class)
                ->add('domicile_exterieur', ChoiceType::class,[
                    'choices' => [
                        "Oui" => 1,
                        "Non" => 0
                    ]
                ])
                ->add('equipe_adverse', TextType::class)
                ->add('hote', TextType::class)
                ->add('date_heure', DateTimeType::class,[
                    'placeholder' => 'Select a value',
                ])
                ->add('num_semaine', TextType::class)
                ->add('num_journee', TextType::class)
                ->add('gymnase', TextType::class)
                ->add('score', TextType::class, ['required' => false])
                ->add('modifier', SubmitType::class)
                ->add('supprimer', SubmitType::class)
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'enregistrer') {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($match);
            $entityManager->flush();
            return $this->redirectToRoute('show_TeamList');
        }

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'supprimer') {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($match);
            $entityManager->flush();
            return $this->redirectToRoute('show_TeamList');
        }

        return $this->render('modify/modifyMatch.html.twig', [
            'form' => $form,
        ]);
    }
}
