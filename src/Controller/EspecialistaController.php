<?php

namespace App\Controller;

use App\Entity\Especialista;
use App\Form\EspecialistaType;
use App\Repository\EspecialistaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/especialista")
 */
class EspecialistaController extends AbstractController
{

    /**
     * @Route("/dataTable", name="especialista_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT e FROM App:Especialista e';

        $columns = [
            0 => 'e.id',
            1 => 'e.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('especialista_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="especialista_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('especialista/index.html.twig');
    }

    /**
     * @Route("/new", name="especialista_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EspecialistaRepository $especialistaRepository, ValidatorInterface $validator): Response
    {
        $especialista = new Especialista();
        $form = $this->createForm(EspecialistaType::class, $especialista);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $especialistaRepository->add($especialista);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('especialista_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($especialista);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('especialista/new.html.twig', [
            'especialista' => $especialista,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="especialista_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Especialista $especialista, EspecialistaRepository $especialistaRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(EspecialistaType::class, $especialista);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $especialistaRepository->add($especialista);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('especialista_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($especialista);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('especialista/new.html.twig', [
            'especialista' => $especialista,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="especialista_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Especialista $especialista, EspecialistaRepository $especialistaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$especialista->getId(), $request->request->get('_token'))) {
            
            try{
                $especialistaRepository->remove($especialista);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']);
    }
}
