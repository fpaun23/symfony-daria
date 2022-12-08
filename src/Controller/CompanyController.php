<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CompanyRepository;
use App\Validators\CompanyValidator;

/**
 * class for company controller
 */
class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;
    private CompanyValidator $companyValidator;

    /**
     * __construct
     *
     * @param  mixed $companyRepository
     * @param  mixed $companyValidator
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository, CompanyValidator $companyValidator)
    {
        $this->companyRepository = $companyRepository;
        $this->companyValidator = $companyValidator;
    }
    /**
     * addCompany
     *
     * @param  mixed $request
     * @return Response
     */
    public function addCompany(Request $request): Response
    {
        try {
            $name = $request->get('name');
            $companies = $request->query->all();
            $this->companyValidator->validToArray($companies);
            $company = new Company();
            $company->setName($name);
            $companySaved = $this->companyRepository->save($company);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'saved' => $companySaved,
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
     * updateCompany
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return Response
     */
    public function updateCompany(Request $request, int $id): Response
    {
        try {
            $updateData = $request->query->all();
            $this->companyValidator->validToArray($updateData);
            $updateResult = $this->companyRepository->update($id, $updateData);

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
     * deleteCompany
     *
     * @param  mixed $id
     * @return Response
     */
    public function deleteCompany(int $id): Response
    {
        try {
            $this->companyValidator->idIsValid($id);
            $companyToDelete = $this->companyRepository->find($id);
            $companyDeleted = $this->companyRepository->remove($companyToDelete);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows_deleted' => $companyDeleted
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
     * listCompany
     *
     * @return Response
     */
    public function listCompany(): Response
    {
            $company = $this->companyRepository->listCompany();

            return new JsonResponse(
                [
                    'rows' => $company
                ]
            );
    }
    /**
     * getCompanyById
     *
     * @param  mixed $id
     * @return Response
     */
    public function getCompanyById(int $id): Response
    {
        try {
            $this->companyValidator->idIsValid($id);
            $company = $this->companyRepository->getCompanyId($id);

            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $company
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
     * getCompanyByName
     *
     * @param  mixed $name
     * @return Response
     */
    public function getCompanyByName(string $name): Response
    {
        try {
            $this->companyValidator->nameIsValid($name);
            $company = $this->companyRepository->getCompanyName($name);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $company
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
     * getCompanyNameLike
     *
     * @param  mixed $name
     * @return Response
     */
    public function getCompanyNameLike(string $name): Response
    {
        try {
            $this->companyValidator->nameIsValid($name);
            $company = $this->companyRepository->getCompanyLike($name);
            return new JsonResponse(
                [
                    'results' => [
                        'error' => false,
                        'rows' => $company
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
