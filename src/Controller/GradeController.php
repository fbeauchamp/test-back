<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class GradeController extends AbstractController
{
    /**
     * add a grade to an exiting student
     * @Route("/api/student/{studentId}/grade", name="add_grade",  methods={"POST"})
     * @SWG\Response(
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
     * @SWG\Response(
     *     response=500,
     *     description="missing subject, grade or grade not between 0 and 20"
     * )
     */
    public function addGrade(string $studentId, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $grade = new Grade();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        if (!$student) {
            throw new NotFoundHttpException('The student does not exist');
        }
        $newGrade = intval($request->get('grade'), 10);
        if ($newGrade > 20 || $newGrade < 0) {
            throw new BadRequestHttpException('acceptable values for grade are integer between 0 and 20');
        }
        $grade->setGrade($newGrade);
        $grade->setSubject($request->get('subject'));
        $student->addGrade($grade);
        $entityManager->persist($grade);
        $entityManager->flush();

        return $this->json($grade);
    }

    /**
     * compute the average grade of a student
     * @Route("/api/student/{studentId}/average", name="student_average",  methods={"GET"})
     */
    public function studentAverage(string $studentId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $grade = new Grade();

        $student = $this->getDoctrine()->getRepository(Student::class)->find($studentId);
        if (!$student) {
            throw new NotFoundHttpException('The student does not exist');
        }
        $average = $this->getDoctrine()->getRepository(Grade::class)->getAverage($student);
        $count = $average[0]['count'];
        $sum = $average[0]['sum'];
        if (0 == $count) {
            return $this->json(['average' => null]);
        }

        return $this->json(['average' => $sum / $count]);
    }

    /**
     * compute the average grade of all students
     * @Route("/api/average", name="average",  methods={"GET"})
     */
    public function average(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $grade = new Grade();

        $average = $this->getDoctrine()->getRepository(Grade::class)->getAverage();
        $count = $average[0]['count'];
        $sum = $average[0]['sum'];
        if (0 == $count) {
            return $this->json(['average' => null]);
        }

        return $this->json(['average' => floatval($sum) / floatval($count)]);
    }
}
