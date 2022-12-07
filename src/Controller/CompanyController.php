<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompanyRepository;

/**
 * class for company controller
 */
class CompanyController extends AbstractController
{
    private CompanyRepository $companyRepository;

    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    /**
     * adds Company to the db
     *
     * @param  mixed $doctrine
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
             'saved' => $companySaved,
            ]
        );
    }
    /**
     * updates company name with id
     *
     * @param  mixed $doctrine
     * @param  mixed $id
     * @return Response
     */
    public function updateCompany(Request $request, int $id): Response
    {
        $requestParams = $request->query->all();
        $updateResult = $this->companyRepository->update($id, $requestParams);

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
        $company = $this->companyRepository->listCompany();

        return new JsonResponse(
            [
                'rows' => $company
            ]
        );
    }
    /**
     * companyId
     *
     * @param  mixed $id
     * @return Response
     */
    public function companyId(int $id): Response
    {
        $company = $this->companyRepository->getCompanyId($id);

        return new JsonResponse(
            [
                'rows' => $company
            ]
        );
    }
     /**
     * companyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function companyName(string $name): Response
    {
        $company = $this->companyRepository->getCompanyName($name);

        return new JsonResponse($company);
    }
    /**
     * likeCompanyName
     *
     * @param  mixed $name
     * @return Response
     */
    public function likeCompanyName(string $name): Response
    {
        $company = $this->companyRepository->getLikeCompanyName($name);

        return new JsonResponse($company);
    }
}
