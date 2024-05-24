<?php

namespace App\Controller;

use App\Entity\Pais;
use App\Form\PaisType;
use App\Repository\PaisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/pais")
 */
class PaisController extends AbstractController
{

    /**
     * @Route("/dataTable", name="pais_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT p FROM App:Pais p';

        $columns = [
            0 => 'p.id',
            1 => 'p.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('pais_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="pais_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('pais/index.html.twig');
    }

    /**
     * @Route("/new", name="pais_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PaisRepository $paisRepository, ValidatorInterface $validator): Response
    {
        $pais = new Pais();
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paisRepository->add($pais);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('pais_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($pais);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('pais/new.html.twig', [
            'pais' => $pais,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pais_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pais $pais, PaisRepository $paisRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(PaisType::class, $pais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paisRepository->add($pais);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('pais_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($pais);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('pais/new.html.twig', [
            'pais' => $pais,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pais_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Pais $pais, PaisRepository $paisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pais->getId(), $request->request->get('_token'))) {
            
            try{
                $paisRepository->remove($pais);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
