<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/program", name="program_")
 */

class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * The controller for the program add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Program Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to program list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/{id<^[0-9]+$>}", name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"id":"id"}})
     * @return Response
     */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program
        ]);
    }

    /**
     * Getting all seasons by program id
     *
     * @Route("/{program_id}/season/{season_id}", methods={"GET"}, name="show_season")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id":"id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "number"}})
     * @return Response
     */

    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/show_season.html.twig', [
            'season' => $season,
            'program' => $program
        ]);
    }

    /**
     * Getting all episodes by season id
     *
     * @Route("/{program_id}/season/{season_id}/episode/{episode_id}", methods={"GET"}, name="show_episode")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id":"id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "number"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_id":"id"}})
     * @return Response
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/show_episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }
}
