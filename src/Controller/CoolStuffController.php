<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CoolStuffController extends AbstractController
{
    /**
     * @Route("/cool/stuff", name="app_cool_stuff")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CoolStuffController.php',
        ]);
    }

    /**
     * @Route("/blog/", name="app_blog")
     */
    public function blog(): JsonResponse
    {
        return $this->json([
            'message' => 'Page blog',
            'path' => 'src/Controller/CoolStuffController.php',
        ]);
    }

    /**
     * @Route("/blog/{page}", name="app_blog_page", requirements={"page"="\d+"})
     */
    public function blogPage($page): JsonResponse
    {
        return $this->json([
            'message' => 'Page '.$page,
            'path' => 'src/Controller/CoolStuffController.php',
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="app_blog_slug", requirements={"slug"="\S+"})
     */
    public function blogSlug($slug): JsonResponse
    {
        return $this->json([
            'message' => 'Page slug '.$slug,
            'path' => 'src/Controller/CoolStuffController.php',
        ]);
    }
}
