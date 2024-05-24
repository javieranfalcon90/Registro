<?php

namespace App\Controller;

use App\Entity\Tipo;
use App\Form\TipoType;
use App\Repository\TipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/tipo")
 */
class TipoController extends AbstractController
{

    /**
     * @Route("/dataTable", name="tipo_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT t FROM App:Tipo t';

        $columns = [
            0 => 't.id',
            1 => 't.nombre',
            2 => 't.codigo',
        ];

        $resultados = $dataTableServicio->datatableResult($request, $dql, $columns);
        $count = $dataTableServicio->count($request, $dql, $columns);
        $countAll = $dataTableServicio->countAll($dql);

        $array = [];
        foreach ($resultados as $res) {
            $array[] = [
                '',
                $res->getNombre(),
                $res->getCodigo(),
                '<div class="text-center">

                    <span class="dropdown">
                      <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Acciones</button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="'.$this->generateUrl('tipo_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="tipo_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(TipoRepository $tipoRepository): Response
    {
        return $this->render('tipo/index.html.twig', [
            'tipos' => $tipoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TipoRepository $tipoRepository, ValidatorInterface $validator): Response
    {
        $tipo = new Tipo();
        $form = $this->createForm(TipoType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipoRepository->add($tipo);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('tipo_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($tipo);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('tipo/new.html.twig', [
            'tipo' => $tipo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tipo $tipo, TipoRepository $tipoRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(TipoType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipoRepository->add($tipo);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('tipo_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($tipo);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('tipo/new.html.twig', [
            'tipo' => $tipo,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Tipo $tipo, TipoRepository $tipoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipo->getId(), $request->request->get('_token'))) {
            
            try{
                $productoRepository->remove($tipo);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
