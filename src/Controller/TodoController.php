<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFilterType;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="app_todo_index", methods={"GET", "POST"})
     */
    public function index(Request $request, TodoRepository $todoRepository): Response
    {
        // Filtrer si la tâche à était faite ou non et l'affiche sur la même page
        $filterForm = $this->createForm(TodoFilterType::class);
        $filterForm->handleRequest($request);

        $stillTodo = $filterForm->get('stillTodo')->getData() ?? $request->query->get('stillTodo');
        $criteria = (true === $stillTodo) ? ['done' => false] : [];

        $search = $filterForm->get('search')->getData();

        $orderby = $request->query->get('orderby') ?? 'id';
        $order = $request->query->get('order') ?? 'ASC';
        if ($order == 'ASC') {
            $orderAD = 'DESC';
        } else {
            $orderAD = 'ASC';
        }

        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findAllOrdered($orderby, $order, $criteria, $search),
            'order' => $orderAD,
            'filterForm' => $filterForm->createView(),
            'stillTodo' => $stillTodo,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/new", name="app_todo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TodoRepository $todoRepository): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/filter", name="app_todo_filter", methods={"POST", "GET"})
     */
    public function filter(SerializerInterface $serializer, EntityManagerInterface $em, Request $request): Response
    {      
        $terms = ($request->toArray())['terms'];

        $query = $em->createQuery('SELECT t FROM App\Entity\Todo t WHERE t.name LIKE :terms')
        ->setParameter('terms', '%'.$terms.'%');
        $search = $query->getResult();
        $serializerData = $serializer->serialize($search, 'json', ['groups' => 'list_todo']);
        return new Response($serializerData);
    }

    /**
     * @Route("/search", name="app_todo_search", methods={"POST", "GET"})
     */
    public function search(SerializerInterface $serializer, EntityManagerInterface $em, Request $request): Response
    {      
        $terms = ($request->toArray())['terms'];

        $query = $em->createQuery('SELECT t FROM App\Entity\Todo t WHERE t.name LIKE :terms')
        ->setParameter('terms', '%'.$terms.'%');
        $search = $query->getResult();
        $serializerData = $serializer->serialize($search, 'json', ['groups' => 'list_todo']);
        return new Response($serializerData);
    }

    /**
     * @Route("/{id}", name="app_todo_show", methods={"GET"})
     */
    public function show(Todo $todo): Response
    {
        dump($todo);
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_todo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todoRepository->add($todo, true);

            return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_todo_delete", methods={"POST"})
     */
    public function delete(Request $request, Todo $todo, TodoRepository $todoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/updateDone", name="app_todo_updtaDone", methods={"POST", "GET"})
     */
    public function updateDone(ManagerRegistry $doctrine, EntityManagerInterface $manager, $id): Response
    {
        $done = $doctrine->getRepository(Todo::class)->findOneBy(['id' => $id]);
        if ($done->isDone() == 1) {
            $done->setDone(0);
        } else {
            $done->setDone(1);
        }
        $manager->persist($done);
        $manager->flush();
        return  $this->json(["message" => "Valeur modifier !"]);
    }

}
