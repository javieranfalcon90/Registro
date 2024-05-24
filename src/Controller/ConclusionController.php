<?php

namespace App\Controller;

use App\Entity\Conclusion;
use App\Form\ConclusionType;
use App\Repository\ConclusionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/conclusion")
 */
class ConclusionController extends AbstractController
{

        /**
     * @Route("/dataTable", name="conclusion_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT c FROM App:Conclusion c';

        $columns = [
            0 => 'c.id',
            1 => 'c.nombre',
        ];

        $resultados = $dataTableServicio->datatableResult($request, $dql, $columns);
        $count = $dataTableServicio->count($request, $dql, $columns);
        $countAll = $dataTableServicio->countAll($dql);

        $array = [];
        foreach ($resultados as $res) {
            $array[] = [
                '',
                $res->getNombre(),
                '<div class="text-center">

                    <span class="dropdown">
                      <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Acciones</button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="'.$this->generateUrl('conclusion_edit', ['id' => $res->getId()]).'">Editar</a>
                        <a class="dropdown-item confirmacion eliminar" href="#" id="'.$res->getId().'" token="'.$token->getToken('delete'.$res->getId()).'">Eliminar</a>
                      </div>
                    </span>

                </div>'
            ];
        }

        $data = [
            'iTotalRecords' => $countAll, //consulta para el total de elementos
            'iTotalDisplayRecords' => $count, //consulta para el filtro de elementos
            'data' => $array,
        ];

        $data1 = json_encode($data);

        return new Response($data1, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/", name="conclusion_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('conclusion/index.html.twig');
    }

    /**
     * @Route("/new", name="conclusion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ConclusionRepository $conclusionRepository, ValidatorInterface $validator): Response
    {
        $conclusion = new Conclusion();
        $form = $this->createForm(ConclusionType::class, $conclusion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conclusionRepository->add($conclusion);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('conclusion_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($conclusion);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('conclusion/new.html.twig', [
            'conclusion' => $conclusion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="conclusion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Conclusion $conclusion, ConclusionRepository $conclusionRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ConclusionType::class, $conclusion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conclusionRepository->add($conclusion);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('conclusion_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($conclusion);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('conclusion/new.html.twig', [
            'conclusion' => $conclusion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="conclusion_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Conclusion $conclusion, ConclusionRepository $conclusionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conclusion->getId(), $request->request->get('_token'))) {
            
            try{
                $conclusionRepository->remove($conclusion);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
