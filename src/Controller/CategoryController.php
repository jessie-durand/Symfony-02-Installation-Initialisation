<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
     * Show all rows from Category’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categories' => $categories]
        );
    }


    /**
     * Getting a category by
     *
     * @Route("/{categoryName}/", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $checkCategory = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(
                ['name' => $categoryName],
            );

        if (empty($checkCategory)) {
            throw $this->createNotFoundException(
                'No category with id : ' . $categoryName . ' found.'
            );
        }

        $categoryId = $checkCategory[0]->getId();

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $categoryId],
                ['id' => 'DESC'],
                3
            );

        return $this->render('category/show.html.twig', [
            'categoryName' => $categoryName, 'programs' => $programs
        ]);
    }
}
