<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/configuracion/categoria")
 */
class CategoriaController extends AbstractController
{

    /**
     * @Route("/dataTable", name="categoria_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT c FROM App:Categoria c';

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
                        <a class="dropdown-item" href="'.$this->generateUrl('categoria_edit', ['id' => $res->getId()]).'">Editar</a>
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
     * @Route("/", name="categoria_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('categoria/index.html.twig');
    }

    /**
     * @Route("/new", name="categoria_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoriaRepository $categoriaRepository, ValidatorInterface $validator): Response
    {
        $categoria = new Categoria();
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categoria);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('categoria_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($categoria);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('categoria/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categoria_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categoria $categoria, CategoriaRepository $categoriaRepository, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriaRepository->add($categoria);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('categoria_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($categoria);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('categoria/new.html.twig', [
            'categoria' => $categoria,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="categoria_delete", methods={"POST"}, options={"expose" = true})
     */
    public function delete(Request $request, Categoria $categoria, CategoriaRepository $categoriaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoria->getId(), $request->request->get('_token'))) {
            
            try{
                $categoriaRepository->remove($categoria);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
