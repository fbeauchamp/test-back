<?php

namespace App\Controller;

use App\Entity\Student;
use Datetime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StudentController extends AbstractController
{
    /**
     * @Route("/api/student/{id?}", name="create_student",  methods={"POST"})
     */
    public function createStudent(?string $id, Request $request, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student = $id ? $this->getDoctrine()->getRepository(Student::class)->find($id) : new Student();
        if ($id && !$student) {
            throw $this->createNotFoundException('The student does not exist');
        }
        $student->setName($request->get('name', $student->getName()));
        $student->setSurname($request->get('surname', $student->getSurname()));
        $d = DateTime::createFromFormat('Y-m-d', $request->get('birthdate'));
        $student->setBirthDate($d ? $d : $student->getBirthDate());
        $entityManager->persist($student);
        $entityManager->flush();

        return $this->json($student);
    }

    /**
     * @Route("/api/student/{id}", name="get_student",  methods={"GET"})
     */
    public function getStudent(string $id, SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if (!$student) {
            throw $this->createNotFoundException('The student does not exist');
        }

        return $this->json($student);
    }

    /**
     * @Route("/api/student/{id}", name="delete_student",  methods={"DELETE"})
     */
    public function deleteStudent(string $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if (!$student) {
            throw new NotFoundHttpException('The student does not exist');
        }
        $entityManager->remove($student);

        return $this->json(['success' => true]);
    }
}
