<?php

namespace App\Controller;

use App\Entity\Vale;
use App\Form\ValeType;
use App\Repository\ValeRepository;
use App\Entity\Solicitud;
use App\Repository\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/vale")
 */
class ValeController extends AbstractController
{

    /**
     * @Route("/dataTable", name="vale_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT v FROM App:Vale v LEFT JOIN v.solicitudes s ';

        $columns = [
            0 => 'v.id',
            1 => 'v.vale',
            2 => 'v.fechavale',
            3 => 's.codigo',
            4 => 'v.observaciones',
            5 => 'v.factura',
            6 => 'v.fechafactura'
        ];

        $resultados = $dataTableServicio->datatableResult($request, $dql, $columns);
        $count = $dataTableServicio->count($request, $dql, $columns);
        $countAll = $dataTableServicio->countAll($dql);

        $array = [];
        foreach ($resultados as $res) {

            $solicitudes = [];
            foreach($res->getSolicitudes() as $key => $s){
                $str = '';
                if($key != 0){
                    $str = '<br>'; 
                }
                $str = $str . '<a class="" href="'.$this->generateUrl('solicitud_show', ['id' => $s->getId()]).'">'.$s->getCodigo().'</a>';
	            $solicitudes[] = $str;
            }

            $array[] = [
                '',
                $res->getVale(),
                $res->getFechavale()->format('d-m-Y'),
                $solicitudes,
                ($res->getObservaciones()) ? $res->getObservaciones() : '-',
                ($res->getFactura()) ? $res->getFactura() : '-',
                ($res->getFechafactura()) ? $res->getFechafactura()->format('d-m-Y') : '-',
                (!$res->getFactura()) ? '
                <a class="btn confirmacion eliminar" href="#" id="'.$res->getId().'" token="'.$token->getToken('delete'.$res->getId()).'">Eliminar</a>
                <a class="btn btn-info" href="'.$this->generateUrl('vale_edit', ['id' => $res->getId()]).'">Facturar</a>' : 
                '<a class="btn confirmacion eliminar" href="#" id="'.$res->getId().'" token="'.$token->getToken('delete'.$res->getId()).'">Eliminar</a>'

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
     * @Route("/", name="vale_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('vale/index.html.twig');
    }

    /**
     * @Route("/new", name="vale_new", methods={"GET", "POST"})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {

        $vale = new Vale();
        $form = $this->createForm(ValeType::class, $vale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vale);
            $entityManager->flush();

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('vale_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($vale);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('vale/new.html.twig', [
            'vale' => $vale,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="vale_edit", methods={"GET", "POST"})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function edit(Request $request, Vale $vale, EntityManagerInterface $entityManager, ValidatorInterface $validator, SolicitudRepository $solicitudRepository): Response
    {

        $form = $this->createForm(ValeType::class, $vale, ['vale' => $vale]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if($vale->getFactura()){
                foreach($vale->getSolicitudes() as $s){
                    $s->setPagado(true);
                    $solicitudRepository->add($s);
                }
            }

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('vale_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($vale);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }


        return $this->renderForm('vale/facturar.html.twig', [
            'vale' => $vale,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="vale_delete", methods={"POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function delete(Request $request, Vale $vale, ValeRepository $valeRepository, SolicitudRepository $solicitudRepository): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$vale->getId(), $request->request->get('_token'))) {
            
            $solicitudes = $vale->getSolicitudes();

            $flag = 0;
            foreach($solicitudes as $s){
                if($s->getLs()){
                    $flag = 1;
                    break;
                }
            }

            if($flag == 0){
                
                /* En caso de que se elimine el vale y factura las solicitudes asociadas se pondran como no pagadas */
                foreach($solicitudes as $s){
                    $s->setPagado(0);
                    $solicitudRepository->add($s);
                }

                $valeRepository->remove($vale);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            }else{
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
            
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }
}
