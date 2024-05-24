<?php

namespace App\Controller;

use App\Entity\Parteaevaluar;
use App\Form\ParteaevaluarType;
use App\Repository\ParteaevaluarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/parteaevaluar")
 */
class ParteaevaluarController extends AbstractController
{
    /**
     * @Route("/dataTable", name="parteaevaluar_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT p FROM App:Parteaevaluar p';

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
                        <a class="dropdown-item" href="'.$this->generateUrl('parteaevaluar_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="parteaevaluar_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('parteaevaluar/index.html.twig');
    }

    /**
     * @Route("/new", name="parteaevaluar_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ParteaevaluarRepository $parteaevaluarRepository, ValidatorInterface $validator): Response
    {
        $parteaevaluar = new Parteaevaluar();
        $form = $this->createForm(ParteaevaluarType::class, $parteaevaluar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parteaevaluarRepository->add($parteaevaluar);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('parteaevaluar_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($parteaevaluar);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('parteaevaluar/new.html.twig', [
            'parteaevaluar' => $parteaevaluar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parteaevaluar_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Parteaevaluar $parteaevaluar, ParteaevaluarRepository $parteaevaluarRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ParteaevaluarType::class, $parteaevaluar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parteaevaluarRepository->add($parteaevaluar);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('parteaevaluar_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($parteaevaluar);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('parteaevaluar/new.html.twig', [
            'parteaevaluar' => $parteaevaluar,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="parteaevaluar_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Parteaevaluar $parteaevaluar, ParteaevaluarRepository $parteaevaluarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parteaevaluar->getId(), $request->request->get('_token'))) {
            
            try{
                $parteaevaluarRepository->remove($parteaevaluar);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
