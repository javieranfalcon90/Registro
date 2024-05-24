<?php

namespace App\Controller;

use App\Entity\Ifa;
use App\Form\IfaType;
use App\Repository\IfaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/ifa")
 */
class IfaController extends AbstractController
{

    /**
     * @Route("/dataTable", name="ifa_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT i FROM App:Ifa i ';

        $columns = [
            0 => 'i.id',
            1 => 'i.nombre',
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
                        <a class="dropdown-item" href="'.$this->generateUrl('ifa_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="ifa_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('ifa/index.html.twig');
    }

    /**
     * @Route("/new", name="ifa_new", methods={"GET", "POST"})
     */
    public function new(Request $request, IfaRepository $ifaRepository, ValidatorInterface $validator): Response
    {
        $ifa = new Ifa();
        $form = $this->createForm(IfaType::class, $ifa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ifaRepository->add($ifa);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('ifa_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($ifa);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('ifa/new.html.twig', [
            'ifa' => $ifa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ifa_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ifa $ifa, IfaRepository $ifaRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(IfaType::class, $ifa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ifaRepository->add($ifa);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('ifa_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($ifa);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('ifa/new.html.twig', [
            'ifa' => $ifa,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ifa_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Ifa $ifa, IfaRepository $ifaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ifa->getId(), $request->request->get('_token'))) {
            
            try{
                $ifaRepository->remove($ifa);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
