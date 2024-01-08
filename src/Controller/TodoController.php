<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoFilterType;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        $orderby = $request->query->get('orderby') ?? 'id';
        $order = $request->query->get('order') ?? 'ASC';
        if ($order == 'ASC') {
            $orderAD = 'DESC';
            
        } else {
            $orderAD = 'ASC';
        }
        
        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findAllOrdered($orderby, $order, $criteria),
            'order' => $orderAD,
            'filterForm' => $filterForm->createView(),
            'stillTodo' => $stillTodo,
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
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $todoRepository->remove($todo, true);
        }

        return $this->redirectToRoute('app_todo_index', [], Response::HTTP_SEE_OTHER);
    }

    // Filtrer si la tâche à était faite ou non mais l'affiche dans une page annexe

    /**
     * @Route("/filtre/done", name="app_todo_done")
     */
    public function done(ManagerRegistry $doctrine): Response
    {
        $done = $doctrine->getRepository(Todo::class)->findBy(array('done' => 0));

        return $this->render('todo/done.html.twig', [
            'done' => $done,
        ]);
    }
}
