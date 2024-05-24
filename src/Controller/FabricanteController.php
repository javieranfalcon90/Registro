<?php

namespace App\Controller;

use App\Entity\Fabricante;
use App\Form\FabricanteType;
use App\Repository\FabricanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/fabricante")
 */
class FabricanteController extends AbstractController
{

    /**
     * @Route("/dataTable", name="fabricante_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT f FROM App:Fabricante f ';

        $columns = [
            0 => 'f.id',
            1 => 'f.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('fabricante_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="fabricante_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(FabricanteRepository $fabricanteRepository): Response
    {
        return $this->render('fabricante/index.html.twig');
    }

    /**
     * @Route("/new", name="fabricante_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FabricanteRepository $fabricanteRepository, ValidatorInterface $validator): Response
    {
        $fabricante = new Fabricante();
        $form = $this->createForm(FabricanteType::class, $fabricante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fabricanteRepository->add($fabricante);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('fabricante_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($fabricante);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('fabricante/new.html.twig', [
            'fabricante' => $fabricante,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fabricante_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Fabricante $fabricante, FabricanteRepository $fabricanteRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(FabricanteType::class, $fabricante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fabricanteRepository->add($fabricante);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('fabricante_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($fabricante);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('fabricante/new.html.twig', [
            'fabricante' => $fabricante,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fabricante_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Fabricante $fabricante, FabricanteRepository $fabricanteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fabricante->getId(), $request->request->get('_token'))) {
            
            try{
                $fabricanteRepository->remove($fabricante);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
