<?php

namespace App\Controller;

use App\Entity\Ls;
use App\Form\LsType;
use App\Repository\LsRepository;

use App\Repository\SolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/ls")
 */
class LsController extends AbstractController
{

    /**
     * @Route("/dataTable", name="ls_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT l FROM App:Ls l LEFT JOIN l.solicitudes s';

        $columns = [
            0 => 'l.id',
            1 => 'l.numero',
            2 => 'l.fecha',
            3 => 's.codigo'
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
                $res->getNumero(),
                $res->getFecha()->format('d-m-Y'),
                $solicitudes,
                '<div class="text-center">
                    <a class="btn confirmacion eliminar" href="#" id="'.$res->getId().'" token="'.$token->getToken('delete'.$res->getId()).'">Eliminar</a>
                    <a target="_blank" class="btn btn-danger" href="'. $this->generateUrl('ls_export_pdf', ['id' => $res->getId()]) .'">PDF</a>
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
     * @Route("/", name="ls_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('ls/index.html.twig');
    }

    /**
     * @Route("/new", name="ls_new", methods={"GET", "POST"})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function new(Request $request, LsRepository $lsRepository, ValidatorInterface $validator, SolicitudRepository $solicitudRepository): Response
    {

        $l = new Ls();
        $form = $this->createForm(LsType::class, $l);
        $form->handleRequest($request);

        /* INICIO FUNCIONALIDAD PARA GENERAR AUTOMATICAMENTE EL NUMERO DE LNC */
            
            $startdate = new \DateTime('01-01-'.date('Y'));
            $enddate = new \DateTime('31-12-'.date('Y'));
            $result = $lsRepository->createQueryBuilder('s')
                  ->select('DISTINCT s.numero')
                  ->where('s.fecha BETWEEN :startdate AND :enddate')
                  ->setParameter('startdate', $startdate)
                  ->setParameter('enddate', $enddate)
                  ->getQuery()->getResult();
            $auto = sprintf("%'04d", count($result)+1);       

        /* FIN */


        if ($form->isSubmitted() && $form->isValid()) {
            $lsRepository->add($l);

            /*Cada solicitud asociada se pone en estado En Evaluación*/
            foreach($l->getSolicitudes() as $s){
                $s->setEstado('En Evaluación');
                $solicitudRepository->add($s);
            }

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('ls_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($l);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('ls/new.html.twig', [
            'l' => $l,
            'form' => $form,
            'auto' => $auto,
        ]);
    }

    /**
     * @Route("/{id}", name="ls_delete", methods={"POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function delete(Request $request, Ls $l, LsRepository $lsRepository, SolicitudRepository $solicitudRepository): Response
    {

        if ($this->isCsrfTokenValid('delete'.$l->getId(), $request->request->get('_token'))) {
            
            $solicitudes = $l->getSolicitudes();

            $flag = 0;
            foreach($solicitudes as $s){

                if($s->getEstado() == 'Concluido' || $s->getEstado() == 'CD'){
                    $flag = 1;
                    break;
                }
            }

            if($flag == 0){
                
                /*Al eliminar el ls las solicitudes asociadas pasan a estar aprobadas*/
                foreach($l->getSolicitudes() as $s){
                    $s->setEstado('En Archivo');
                    $solicitudRepository->add($s);
                }

                $lsRepository->remove($l);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');

            }else{

                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }

        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }


    /**
     * @Route("/{id}/pdf", name="ls_export_pdf")
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function pdf(Request $request, Ls $l): Response
    {
        
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $pdfOptions->set('defaultPaperOrientation', 'landscape' );
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        if($l->getSolicitudes()->last()->getTipoproducto()->getId() == '1'){
            $template = 'pdfMedicamento.html.twig';
        }elseif($l->getSolicitudes()->last()->getTipoproducto()->getId() == '2'){
            $template = 'pdfBiologico.html.twig';
        }elseif($l->getSolicitudes()->last()->getTipoproducto()->getId() == '3'){
            $template = 'pdfEquipoMedico.html.twig';
        }if($l->getSolicitudes()->last()->getTipoproducto()->getId() == '4'){
            $template = 'pdfDiagnosticador.html.twig';
        }

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/'.$template, [
            'ls' => $l
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // Render the HTML as PDF
        $dompdf->render();

        $name = $l->getNumero().'.pdf';
        // Output the generated PDF to Browser (force download)
        $dompdf->stream($name, [
            "Attachment" => true
        ]);
    }

}
