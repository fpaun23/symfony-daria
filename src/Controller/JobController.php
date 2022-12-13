<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jobs;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\JobRepository;
use App\Validators\JobValidator;
use App\Repository\CompanyRepository;
use DateTimeImmutable;

/**
 * JobController
 */
class JobController extends AbstractController
{
    private JobRepository $jobRepository;
    private CompanyRepository $companyRepository;
    private JobValidator $jobValidator;

    /**
     * __construct
     *
     * @param  mixed $jobRepository
     * @param  mixed $companyRepository
     * @param  mixed $jobValidator
     * @return void
     */
    public function __construct(JobRepository $jobRepository, CompanyRepository $companyRepository, JobValidator $jobValidator)
    {
        $this->jobRepository = $jobRepository;
        $this->jobValidator = $jobValidator;
        $this->companyRepository = $companyRepository;
    }
    /**
     * addJob
     *
     * @param  mixed $request
     * @return Response
     */
    public function addJob(Request $request): Response
    {
        $active = 0;
        $priority = 0;
        try {
            $name = $request->get('name');
            // $description = $request->get('description');
            // $active = (int) $request->get('active');
            // $priority = (int) $request->get('priority');
            $this->jobValidator->nameIsValid($name);
            $companyId = (int) $request->get('company_id');
            $company = $this->companyRepository->find($companyId);
            if (is_null($company)) {
                throw new \InvalidArgumentException('could not find company with id:' . $companyId);
            }
            $newJob = new Jobs();
            $newJob -> setCompanyId($company);
            $newJob -> setName($request->get('name'));
            $newJob -> setDescription($request->get('description'));
            $newJob -> setCreatedAt(new DateTimeImmutable());
            $newJob -> setActive($active);
            $newJob -> setPriority($priority);
            $jobSaved = $this->jobRepository->save($newJob);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'saved' => $jobSaved,
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage(),
                    ]
                ]
            );
        }
    }
    /**
     * updateJob
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return Response
     */
    public function updateJob(Request $request, int $id): Response
    {
        try {
            $updateData = $request->query->all();
            $this->jobValidator->validToArray($updateData);
            $updateResult = $this->jobRepository->update($id, $updateData);

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows_updated' => $updateResult
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
    /**
     * deleteJob
     *
     * @param  mixed $id
     * @return Response
     */
    public function deleteJob(int $id): Response
    {
        try {
            $this->jobValidator->idIsValid($id);
            $jobToDelete = $this->jobRepository->find($id);
            $jobDeleted = $this->jobRepository->remove($jobToDelete);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows_deleted' => $jobDeleted
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
    /**
     * listJob
     *
     * @return Response
     */
    public function listJob(): Response
    {
            $job = $this->jobRepository->listjob();

            return new JsonResponse(
                [
                    'rows' => $job
                ]
            );
    }
    /**
     * getJobById
     *
     * @param  mixed $id
     * @return Response
     */
    public function getJobById(int $id): Response
    {
        try {
            $this->jobValidator->idIsValid($id);
            $job = $this->jobRepository->getJobId($id);

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $job
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
    /**
     * getJobByName
     *
     * @param  mixed $name
     * @return Response
     */
    public function getJobByName(string $name): Response
    {
        try {
            $this->jobValidator->nameIsValid($name);
            $job = $this->jobRepository->getJobName($name);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $job
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
    /**
     * getJobNameLike
     *
     * @param  mixed $name
     * @return Response
     */
    public function getJobNameLike(string $name): Response
    {
        try {
            $this->jobValidator->nameIsValid($name);
            $job = $this->jobRepository->getJobLike($name);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $job
                    ]
                ]
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'results' => [
                        'error' => true,
                        'message' => $e->getMessage()
                    ]
                ]
            );
        }
    }
}
