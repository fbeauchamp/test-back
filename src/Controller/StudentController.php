<?php

namespace App\Controller;

use App\Entity\Student;
use Datetime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class StudentController extends AbstractController
{
    /**
     * Create a new student or update an existing one by id .
     *
     * @Route("/api/student/{id?}", name="create_student",  methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the created user on success",
     *     @SWG\Schema(
     *         type="@Model(type=Student::class)"
     *     )
     * )
     * @SWG\Response(
     *     response=500,
     *     description="when missing a field during student creation"
     * )
     */
    public function createStudent(?string $id, Request $request): Response
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
     * get a student from api
     * @Route("/api/student/{id}", name="get_student",  methods={"GET"})
     *  * @SWG\Response(
     *     response=200,
     *     description="when student exists",
     *     @SWG\Schema(
     *         type="@Model(type=Student::class)"
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="student not found"
     * )
     */
    public function getStudent(string $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        if (!$student) {
            throw $this->createNotFoundException('The student does not exist');
        }
        return $this->json($student);
    }

    /**
     * Delete a student
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
