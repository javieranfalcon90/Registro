<?php

namespace App\Controller;

use App\Entity\Ff;
use App\Form\FfType;
use App\Repository\FfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/ff")
 */
class FfController extends AbstractController
{

    /**
     * @Route("/dataTable", name="ff_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT f FROM App:Ff f ';

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
                        <a class="dropdown-item" href="'.$this->generateUrl('ff_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="ff_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('ff/index.html.twig');
    }

    /**
     * @Route("/new", name="ff_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FfRepository $ffRepository, ValidatorInterface $validator): Response
    {
        $ff = new Ff();
        $form = $this->createForm(FfType::class, $ff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ffRepository->add($ff);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('ff_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($ff);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('ff/new.html.twig', [
            'ff' => $ff,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ff_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ff $ff, FfRepository $ffRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(FfType::class, $ff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ffRepository->add($ff);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('ff_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($ff);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('ff/new.html.twig', [
            'ff' => $ff,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ff_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Ff $ff, FfRepository $ffRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ff->getId(), $request->request->get('_token'))) {
            
            try{
                $ffRepository->remove($ff);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
