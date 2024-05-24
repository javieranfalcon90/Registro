<?php

namespace App\Controller;

use App\Entity\Solicitud;
use App\Form\SolicitudType;
use App\Form\ConclusionSolicitudType;
use App\Repository\SolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Services\DataTableServicio;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/solicitud")
 */
class SolicitudController extends AbstractController
{

    /**
     * @Route("/dataTable", name="solicitud_dataTable", methods={"GET"}, options={"expose" = true})
     */
    public function dataTableAction(Request $request, DataTableServicio $dataTableServicio)
    {

        $token = $this->get('security.csrf.token_manager');

        $dql = 'SELECT s FROM App:Solicitud s LEFT JOIN s.tipotramite tt LEFT JOIN s.tipoproducto tp LEFT JOIN s.producto p LEFT JOIN s.solicitante so LEFT JOIN s.paissolicitante ps WHERE (s.fechaentrada BETWEEN :fecha_inicio AND :fecha_fin)';

        $parameter = [];

        if($daterange = $request->get('daterange')){
            $range = explode(" / ", $daterange);

            $fecha_inicio = new \DateTime($range[0]);
            $fecha_fin = new \DateTime($range[1]);
            
        }else{
            $fecha_inicio = new \DateTime('1-1-'.date("Y"));
            $fecha_fin = new \DateTime('31-12-'.date("Y"));
        }


        $parameter = array_merge($parameter,
            ['fecha_inicio' => $fecha_inicio],
            ['fecha_fin' => $fecha_fin]
        );



        $columns = [
            0 => 's.id',
            1 => 's.iscd',
            2 => 's.codigo',
            3 => 'tt.nombre',
            4 => 'p.nombre',

            5 => 'so.nombre',


            6 => 's.preevaluacion',
            7 => 's.pagado',

            8 => 's.fechaentrada',
            9 => 's.estado',
            
        ];

        $resultados = $dataTableServicio->datatableResult($request, $dql, $columns, $parameter);
        $count = $dataTableServicio->count($request, $dql, $columns, $parameter);
        $countAll = $dataTableServicio->countAll($dql, $parameter);

        $array = [];
        foreach ($resultados as $res) {

            if($res->getEstado() == 'Nuevo'){
                $estado = '<span class="badge bg-info me-1"></span> '.$res->getEstado();
            }elseif ($res->getEstado() == 'En Archivo') {
                $estado = '<span class="badge bg-primary me-1"></span> '.$res->getEstado();
            }elseif ($res->getEstado() == 'Rechazado') {
                $estado = '<span class="badge bg-danger me-1"></span> '.$res->getEstado();
            }elseif ($res->getEstado() == 'En Evaluación') {
                $estado = '<span class="badge bg-warning me-1"></span> '.$res->getEstado();
            }elseif ($res->getEstado() == 'Concluido') {
                $estado = '<span class="badge bg-success me-1"></span> '.$res->getEstado();
            }elseif ($res->getEstado() == 'CD') {
                $estado = '<span class="badge bg-secondary me-1"></span> '.$res->getEstado();
            }

            $array[] = [
                '',
                ($res->getIscd() == true) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>',
                '<a class="btn btn-link" href="'.$this->generateUrl('solicitud_show', ['id' => $res->getId()]).'">'.$res->getCodigo().'</a>',
                $res->getTipotramite()->getNombre(),
                $res->getProducto()->getNombre(),

                $res->getSolicitante()->getNombre(),

                ($res->getPreevaluacion()) ? $res->getPreevaluacion() : '-',
                ($res->getPagado() == true) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>',
                $res->getFechaentrada()->format('d-m-Y'),
                $estado
                

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
     * @Route("/", name="solicitud_index", methods={"GET"}, options={"expose" = true})
     */
    public function index(): Response
    {
        return $this->render('solicitud/index.html.twig');
    }

    /**
     * @Route("/new", name="solicitud_new", methods={"GET", "POST"})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function new(Request $request, SolicitudRepository $solicitudRepository, ValidatorInterface $validator): Response
    {

        $solicitud = new Solicitud();
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        /* INICIO FUNCIONALIDAD PARA GENERAR AUTOMATICAMENTE EL NUMERO DE LNC */
            
            $startdate = new \DateTime('01-01-'.date('Y'));
            $enddate = new \DateTime('31-12-'.date('Y'));

            $result = $solicitudRepository->createQueryBuilder('s')
                  ->select('s.codigo')
                  ->where('s.fechaentrada BETWEEN :startdate AND :enddate')

                  ->andWhere('s.iscd = :cd or s.iscd IS NULL')

                  ->setParameter('startdate', $startdate)
                  ->setParameter('enddate', $enddate)
                  ->setParameter('cd', false)
                  ->getQuery()->getResult();

            $auto = sprintf("%'04d", count($result)+1);       

        /* FIN */

        if ($form->isSubmitted() && $form->isValid()) {

            /* En caso de que sea un CD, clona la solicitud y resetea los valores cambiantes */
            if($request->get('cod')){
                $old = $solicitudRepository->find($request->get('cod'));
                $solicitud = clone $old;

                $old->setPorcd(false);
                $solicitudRepository->add($old);

                $solicitud->setPreevaluacion(null);
                $solicitud->setFechapreevaluacion(null);
                $solicitud->setVale(null);
                $solicitud->setLs(null);
                $solicitud->setFechaCierre(null);
		$solicitud->setIscd(true);
            }        

            if($this->getUser()->getUsername() == 'SuperAdministrador'){
                $especialista = null;             
            }else{
                $especialista = $this->getUser()->getEspecialista();   
            }

            $solicitud->setFechaEntrada($form->getData()->getFechaentrada());
            $solicitud->setEstado('Nuevo');
            $solicitud->setPagado(false);
            $solicitud->setEspecialista($especialista);
            $solicitud->setPorcd(false);

            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha insertado corréctamente');

            return $this->redirectToRoute('solicitud_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($solicitud);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $solicitudes_cd = $solicitudRepository->createQueryBuilder('s')
            ->where('s.porcd =:porcd' )
            ->setParameter('porcd', true)
            ->getQuery()->getResult();

        return $this->renderForm('solicitud/new.html.twig', [
            'auto' => $auto,
            'solicitud' => $solicitud,
            'form' => $form,
            'solicitudes_cd' => $solicitudes_cd,
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_show", methods={"GET"})
     */
    public function show(Request $request, Solicitud $solicitud): Response
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ConclusionSolicitudType::class, $solicitud);
        $form->handleRequest($request);

        $conclusiones = $em->getRepository('App:Conclusion')->findAll();

        return $this->renderForm('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
            'conclusiones' => $conclusiones
        ]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_edit", methods={"GET", "POST"})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     * 
     */
    public function edit(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository, ValidatorInterface $validator): Response
    {

        if($solicitud->getEstado() != 'Nuevo'){
            throw $this->createAccessDeniedException('No access for you!');
        }

        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* En caso de que sea un CD, clona la solicitud y resetea los valores cambiantes */
            if($request->get('cod')){
                $old = $solicitudRepository->find($request->get('cod'));
                $solicitud = clone $old;
                $old->setPorcd(false);
                $solicitudRepository->add($old);

                $solicitud->setPreevaluacion(null);
                $solicitud->setFechapreevaluacion(null);
                $solicitud->setVale(null);
                $solicitud->setLs(null);
                $solicitud->setFechaCierre(null);
            }

            $solicitud->setPorcd(false);

            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('solicitud_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $errors = $validator->validate($solicitud);
            foreach ($errors as $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $solicitudes_cd = $solicitudRepository->createQueryBuilder('s')
            ->where('s.porcd =:porcd' )
            ->setParameter('porcd', true)
            ->getQuery()->getResult();

        return $this->renderForm('solicitud/new.html.twig', [
            'auto' => null,
            'solicitud' => $solicitud,
            'form' => $form,
            'solicitudes_cd' => $solicitudes_cd,
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_delete", methods={"POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function delete(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository): Response
    {

        if($solicitud->getEstado() != 'Nuevo'){
            throw $this->createAccessDeniedException('No access for you!');
        }

        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            
            try{
                $solicitudRepository->remove($solicitud);

                $this->addFlash('success', 'El elemento se ha eliminado corréctamente');
            } catch (\Exception $e) {
                $this->addFlash('error', 'No se pudo eliminar elemento seleccionado, ya que puede estar siendo usado');
            }
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']); 
    }

    /**
     * @Route("/{id}/preevaluar", name="solicitud_preevaluar", methods={"POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function preevaluar(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository): Response
    {

        if($solicitud->getEstado() != 'Nuevo'){
            throw $this->createAccessDeniedException('No access for you!');
        }

        $eval = $request->get('eval');
        $fecha = $request->get('fechapreevaluacion');

        try{

            $solicitud->setPreevaluacion($eval);
            if($eval && $eval == "Aprobado") { $solicitud->setEstado('En Archivo'); }else{ $solicitud->setEstado('Rechazado'); } ;
            
            $solicitud->setFechapreevaluacion(new \DateTime($fecha));
            //$solicitud->setFechapreevaluacion(new \DateTime());
            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha modificado corréctamente');
        } catch (\Exception $e) {
            $this->addFlash('error', 'No se pudo modificar el elemento');
        }

        return new Response(null, '200', ['Content-Type' => 'application/json']);

    }

    /**
     * @Route("/{id}/revertirpreevaluacion", name="solicitud_revertirpreevaluacion", methods={"GET","POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function revertirpreevaluacion(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository): Response
    {

        if($solicitud->getEstado() != 'En Archivo' && $solicitud->getVale() != null){
            throw $this->createAccessDeniedException('No access for you!');
        }

        try{
            
            $solicitud->setEstado('Nuevo');
            $solicitud->setPreevaluacion(null);
            $solicitud->setFechapreevaluacion(null);
                
            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha modificado corréctamente');
        } catch (\Exception $e) {
            $this->addFlash('error', 'No se pudo modificar el elemento');
        }

        return $this->redirectToRoute('solicitud_show', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/concluir", name="solicitud_concluir", methods={"POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function concluir(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository): Response
    {

        $form = $this->createForm(ConclusionSolicitudType::class, $solicitud);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if($form["estado"]->getData() == 'CD') {
                $solicitud->setPorcd(true);
            }

            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha editado corréctamente');

            return $this->redirectToRoute('solicitud_show', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
        }

    }


    /**
     * @Route("/{id}/revertirconclusion", name="solicitud_revertirconclusion", methods={"GET","POST"}, options={"expose" = true})
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function revertirconclusion(Request $request, Solicitud $solicitud, SolicitudRepository $solicitudRepository): Response
    {

        if($solicitud->getEstado() != 'Concluido' && $solicitud->getEstado() != 'CD'){
            throw $this->createAccessDeniedException('No access for you!');
        }

        try{
            
            $solicitud->setEstado('En Evaluación');
            $solicitud->setFechacierre(null);
            $solicitud->setPorcd(false);
                
            foreach($solicitud->getConclusiones() as $c){
                $solicitud->removeConclusione($c);
            }

            $solicitudRepository->add($solicitud);

            $this->addFlash('success', 'El elemento se ha modificado corréctamente');
        } catch (\Exception $e) {
            $this->addFlash('error', 'No se pudo modificar el elemento');
        }

        return $this->redirectToRoute('solicitud_show', ['id' => $solicitud->getId()], Response::HTTP_SEE_OTHER);
    }

    
}

