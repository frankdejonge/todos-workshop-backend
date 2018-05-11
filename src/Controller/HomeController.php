<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\ApplicationRepository;
use App\Entity\Todo;
use App\Entity\TodoRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function registerForm()
    {
        return $this->render('register.html.twig');
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function registerAction()
    {
        $applications = $this->applications();
        $application = new Application(Uuid::uuid4());
        $applications->persist($application);

        return JsonResponse::create($application);
    }

    /**
     * @Route("/todo", methods={"GET"})
     */
    public function todoList(Request $request)
    {
        $applicationId = Uuid::fromString($request->headers->get('x-application-id'));

        return JsonResponse::create($this->todos()->forApplication($applicationId));
    }

    /**
     * @Route("/todo", methods={"POST"})
     */
    public function createTodo(Request $request)
    {
        $payload = \json_decode($request->getContent(), true);
        $applicationId = Uuid::fromString($request->headers->get('x-application-id'));
        $todo = new Todo(
            Uuid::fromString($payload['id']),
            $applicationId,
            $payload['task'],
            $payload['completed']
        );
        $this->todos()->persist($todo);

        return JsonResponse::create($todo);
    }

    /**
     * @Route("/todo/{id}", methods={"POST", "PUT"})
     */
    public function updateTodo(Request $request, string $id)
    {
        $payload = \json_decode($request->getContent(), true);
        $id = Uuid::fromString($id);
        $applicationId = Uuid::fromString($request->headers->get('x-application-id'));

        /** @var Todo $todo */
        $todo = $this->todos()->findOneBy(\compact('id', 'applicationId'));

        if ( ! $todo instanceof Todo) {
            return JsonResponse::create(\compact('id'), 404);
        }

        $todo->update($payload['task'], $payload['completed']);
        $this->todos()->persist($todo);

        return JsonResponse::create($todo);
    }

    /**
     * @Route("/todo/{id}", methods={"DELETE"})
     */
    public function deleteTodo(Request $request, string $id)
    {
        $id = Uuid::fromString($id);
        $applicationId = Uuid::fromString($request->headers->get('x-application-id'));
        $this->todos()->delete($applicationId, $id);


        return JsonResponse::create([], 204);
    }

    protected function applications(): ApplicationRepository
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Application::class);
    }

    protected function todos(): TodoRepository
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Todo::class);
    }
}