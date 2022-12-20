<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Jobs;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\JobRepository;
use App\Validators\Job\JobDataValidator;
use App\Repository\CompanyRepository;
use DateTimeImmutable;
use App\Service\FileReaderServiceInterface;

/**
 * JobController
 */
class JobController extends AbstractController
{
    private JobRepository $jobRepository;
    private CompanyRepository $companyRepository;
    private JobDataValidator $jobValidator;
    private FileReaderServiceInterface $reader;

    /**
     * __construct
     *
     * @param  mixed $jobRepository
     * @param  mixed $companyRepository
     * @param  mixed $jobValidator
     * @return void
     */
    public function __construct(JobRepository $jobRepository, CompanyRepository $companyRepository, FileReaderServiceInterface $reader, JobDataValidator $jobValidator)
    {
        $this->jobRepository = $jobRepository;
        $this->companyRepository = $companyRepository;
        $this->jobValidator = $jobValidator;
        $this->reader = $reader;
    }
    /**
     * add
     *
     * @param  mixed $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $active = 0;
        $priority = 0;
        try {
            $name = $request->get('name');
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
                        'jobs_added' => $jobSaved,
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
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $updateData = $request->query->all();
            $this->jobValidator->validToArray($updateData);
            $updateResult = $this->jobRepository->update($id, $updateData);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs_updated' => $updateResult
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
     * delete
     *
     * @param  mixed $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        try {
            $this->jobValidator->idIsValid($id);
            $jobToDelete = $this->jobRepository->find($id);
            $jobDeletedId = $jobToDelete->getId();
            if (is_null($jobToDelete)) {
                throw new \InvalidArgumentException('Could not find job with id: ' . $id);
            }
            $this->jobRepository->remove($jobToDelete);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'deleted_job_id' => !empty($jobDeletedId)
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
     * list
     *
     * @return Response
     */
    public function list(): Response
    {
        $job = $this->jobRepository->listjob();
        $jobArr = [];
        foreach ($job as $foundjob) {
                $jobArr[] = [
                    'id' => $foundjob->getId(),
                    'name' => $foundjob->getName(),
                    'description' => $foundjob->getDescription(),
                    'active' => $foundjob->getActive(),
                    'priority' => $foundjob->getPriority(),
                    'company' => [
                        'id' => $foundjob->getCompany() ->getId(),
                        'name' => $foundjob->getCompany()->getName(),
                    ]
                ];
        }
        return new JsonResponse(
            [
                'results' => [
                    'error' => false,
                    'jobs' => $jobArr
                ]
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
            $jobArr = [];
            $job = $this->jobRepository->getJobId($id);
            foreach ($job as $foundjob) {
                $jobArr[] = [
                    'id' => $foundjob->getId(),
                    'name' => $foundjob->getName(),
                    'description' => $foundjob->getDescription(),
                    'active' => $foundjob->getActive(),
                    'priority' => $foundjob->getPriority(),
                    'company' => [
                        'id' => $foundjob->getCompany()->getId(),
                        'name' => $foundjob->getCompany()->getName(),
                    ]
                ];
            }

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs' => $jobArr
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
            $jobArr = [];
            $job = $this->jobRepository->getJobName($name);
            foreach ($job as $foundjob) {
                $jobArr[] = [
                    'id' => $foundjob->getId(),
                    'name' => $foundjob->getName(),
                    'description' => $foundjob->getDescription(),
                    'active' => $foundjob->getActive(),
                    'priority' => $foundjob->getPriority(),
                    'company' => [
                        'id' => $foundjob->getCompany()->getId(),
                        'name' => $foundjob->getCompany()->getName(),
                    ]
                ];
            }
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs' => $jobArr
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
            $jobArr = [];
            $job = $this->jobRepository->getJobLike($name);
            foreach ($job as $foundjob) {
                $jobArr[] = [
                    'id' => $foundjob->getId(),
                    'name' => $foundjob->getName(),
                    'description' => $foundjob->getDescription(),
                    'active' => $foundjob->getActive(),
                    'priority' => $foundjob->getPriority(),
                    'company' => [
                        'id' => $foundjob->getCompany() ->getId(),
                        'name' => $foundjob->getCompany()->getName(),
                    ]
                ];
            }
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'jobs' => $jobArr
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
    public function bulk(): Response
    {
        try {
            $jobArr = [];
            $jobArr = $this->reader->getData();
            return new JsonResponse(
                [
                'results' => [
                    'error' => false,
                    'jobs' => $jobArr
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
