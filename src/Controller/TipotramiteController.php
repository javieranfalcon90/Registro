<?php

namespace App\Controller;

use App\Entity\Tipotramite;
use App\Form\TipotramiteType;
use App\Repository\TipotramiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/tipotramite")
 */
class TipotramiteController extends AbstractController
{

    /**
     * @Route("/dataTable", name="tipotramite_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT t FROM App:Tipotramite t';

        $columns = [
            0 => 't.id',
            1 => 't.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('tipotramite_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="tipotramite_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('tipotramite/index.html.twig');
    }

    /**
     * @Route("/new", name="tipotramite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TipotramiteRepository $tipotramiteRepository, ValidatorInterface $validator): Response
    {
        $tipotramite = new Tipotramite();
        $form = $this->createForm(TipotramiteType::class, $tipotramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipotramiteRepository->add($tipotramite);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('tipotramite_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($tipotramite);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('tipotramite/new.html.twig', [
            'tipotramite' => $tipotramite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipotramite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tipotramite $tipotramite, TipotramiteRepository $tipotramiteRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(TipotramiteType::class, $tipotramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipotramiteRepository->add($tipotramite);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('tipotramite_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($tipotramite);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('tipotramite/new.html.twig', [
            'tipotramite' => $tipotramite,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipotramite_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Tipotramite $tipotramite, TipotramiteRepository $tipotramiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipotramite->getId(), $request->request->get('_token'))) {
            
            try{
                $tipotramiteRepository->remove($tipotramite);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
