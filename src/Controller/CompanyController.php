<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyRepository;
use App\Validator\CompanyValidator;

/**
 * class for company controller
 */
class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;
    /**
     * __construct
     *
     * @param  mixed $companyRepository
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    /**
     * add
     *
     * @param  mixed $request
     * @return Response
     */
    public function addCompany(Request $request): Response
    {
        $name = $request->get('name');
        $company = new Company();
        $company->setName($name);
        $companySaved = $this->companyRepository->save($company);
        return new JsonResponse(
            [
             'saved' => $companySaved
            ]
        );
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
        $requestParams = $request->query->all();
        $updateResult = $this->companyRepository->updateCompany($id, $requestParams);
        return new JsonResponse(
            [
                'rows_updated' => $updateResult
            ]
        );
    }
    /**
     * deleteCompany
     *
     * @param  mixed $id
     * @return Response
     */
    public function deleteCompany(int $id): Response
    {
            $companyForDelete = $this->companyRepository->find($id);
            $companyDeleted = $this->companyRepository->remove($companyForDelete);
            return new JsonResponse(
                [
                    'rows_deleted' => $companyDeleted
                ]
            );
    }
    /**
     * listCompany
     *
     * @return Response
     */
    public function listCompany(): Response
    {
            $companyList = $this->companyRepository->listCompany();

            return new JsonResponse(
                [
                    'rows' => $companyList
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
        $companybyId = $this->companyRepository->getCompanyId($id);
        return new JsonResponse(
            [
                'rows' => $companybyId
            ]
        );
    }
    /**
     * getCompanyByName
     *
     * @param  mixed $name
     * @return Response
     */
    public function getCompanyByName(string $name): Response
    {
        $companyByName = $this->companyRepository->getCompanyName($name);
        return new JsonResponse($companyByName);
    }
    /**
     * getCompanyNameLike
     *
     * @param  mixed $name
     * @return Response
     */
    public function getCompanyNameLike(string $name): Response
    {
            $companyLike = $this->companyRepository->getCompanyLike($name);
            return new JsonResponse(
                [
                    'rows' => $companyLike
                ]
            );
    }
}
