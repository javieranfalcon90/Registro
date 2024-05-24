<?php

namespace App\Controller;

use App\Entity\Solicitante;
use App\Form\SolicitanteType;
use App\Repository\SolicitanteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/solicitante")
 */
class SolicitanteController extends AbstractController
{

    /**
     * @Route("/dataTable", name="solicitante_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT s FROM App:Solicitante s';

        $columns = [
            0 => 's.id',
            1 => 's.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('solicitante_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="solicitante_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('solicitante/index.html.twig');
    }

    /**
     * @Route("/new", name="solicitante_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SolicitanteRepository $solicitanteRepository, ValidatorInterface $validator): Response
    {
        $solicitante = new Solicitante();
        $form = $this->createForm(SolicitanteType::class, $solicitante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitanteRepository->add($solicitante);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('solicitante_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($solicitante);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('solicitante/new.html.twig', [
            'solicitante' => $solicitante,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="solicitante_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Solicitante $solicitante, SolicitanteRepository $solicitanteRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(SolicitanteType::class, $solicitante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitanteRepository->add($solicitante);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('solicitante_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($solicitante);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('solicitante/new.html.twig', [
            'solicitante' => $solicitante,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="solicitante_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Solicitante $solicitante, SolicitanteRepository $solicitanteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitante->getId(), $request->request->get('_token'))) {
            
            try{
                $solicitanteRepository->remove($solicitante);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
