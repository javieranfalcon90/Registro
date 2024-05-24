<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }



    /**
     * @Route("/cantidad_total", name="cantidad_total", methods={"GET"}, options={"expose" : "true"})
     */
    public function cantidad_total(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        if($year = $request->get('year')){

            $fecha_inicio = new \DateTime('1-1-'.$year);
            $fecha_fin = new \DateTime('31-12-'.$year);
            
        }else{
            $fecha_inicio = new \DateTime('1-1-'.date("Y"));
            $fecha_fin = new \DateTime('31-12-'.date("Y"));
        }

        $solicitudes = $em->getRepository('App:Solicitud')->createQueryBuilder('s')
            ->where('s.fechaentrada BETWEEN :fecha_inicio AND :fecha_fin')
            ->setParameter('fecha_inicio', $fecha_inicio)
            ->setParameter('fecha_fin', $fecha_fin)
            ->getQuery()->getResult();

        $facturas = $em->getRepository('App:Vale')->createQueryBuilder('v')
            ->where('v.fechafactura BETWEEN :fecha_inicio AND :fecha_fin')
            ->setParameter('fecha_inicio', $fecha_inicio)
            ->setParameter('fecha_fin', $fecha_fin)
            ->getQuery()->getResult();

        $ls = $em->getRepository('App:Ls')->createQueryBuilder('l')
            ->where('l.fecha BETWEEN :fecha_inicio AND :fecha_fin')
            ->setParameter('fecha_inicio', $fecha_inicio)
            ->setParameter('fecha_fin', $fecha_fin)
            ->getQuery()->getResult();


            $array_cant = [
                'solicitudes' => count($solicitudes),
                'facturas' => count($facturas),
                'ls' => count($ls)
            ];


        $data1 = json_encode($array_cant);

        return new Response($data1, 200, ['Content-Type' => 'application/json']);
    }



    /**
     * @Route("/cantidad_anno", name="cantidad_anno", methods={"GET"}, options={"expose" : "true"})
     */
    public function cantidad_anno(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        if($year = $request->get('year')){

            $fecha_inicio = new \DateTime('1-1-'.$year);
            $fecha_fin = new \DateTime('31-12-'.$year);
            
        }else{
            $fecha_inicio = new \DateTime('1-1-'.date("Y"));
            $fecha_fin = new \DateTime('31-12-'.date("Y"));
        }

        $entitys = $em->getRepository('App:Solicitud')->createQueryBuilder('s')
            ->where('s.fechaentrada BETWEEN :fecha_inicio AND :fecha_fin')
            ->setParameter('fecha_inicio', $fecha_inicio)
            ->setParameter('fecha_fin', $fecha_fin)
            ->getQuery()->getResult();

        $array_cant = [0,0,0,0,0,0,0,0,0,0,0,0];

        for($i=1; $i<=12; $i++){
            $cont = 0;

            foreach ($entitys as $e) {

                if($e->getFechaentrada()->format('n') == $i){
                    $cont++;
                }
                $array_cant[$i-1] = $cont;
            }

        }

        $data1 = json_encode($array_cant);

        return new Response($data1, 200, ['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/excel", name="export_excel")
     * 
     * @Security("is_granted('ROLE_ADMINISTRADOR') or is_granted('ROLE_ESPECIALISTA')")
     */
    public function excel()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('User List');

        $sheet->getCell('A1')->setValue('Código');
        $sheet->getCell('B1')->setValue('Fecha Entrada');
        $sheet->getCell('C1')->setValue('Producto');
        $sheet->getCell('D1')->setValue('IFA');
        $sheet->getCell('E1')->setValue('Fortaleza');
        $sheet->getCell('F1')->setValue('Forma Farmacéutica');
        $sheet->getCell('G1')->setValue('Categoría/Clase de Riesgo');
        $sheet->getCell('H1')->setValue('Solicitante');
        $sheet->getCell('I1')->setValue('País');
        $sheet->getCell('J1')->setValue('Fabricante');
        $sheet->getCell('K1')->setValue('País');
        $sheet->getCell('L1')->setValue('Evaluación');
        $sheet->getCell('M1')->setValue('Partes a Evaluar');
        $sheet->getCell('N1')->setValue('Muestras');
        $sheet->getCell('O1')->setValue('Persona de Contacto');
        $sheet->getCell('P1')->setValue('No.Vale');
        $sheet->getCell('Q1')->setValue('Fecha');
        $sheet->getCell('R1')->setValue('No.Factura');
        $sheet->getCell('S1')->setValue('Fecha');
        $sheet->getCell('T1')->setValue('LS');
        $sheet->getCell('U1')->setValue('Fecha Cierre');
        $sheet->getCell('V1')->setValue('Observaciones');
        $sheet->getCell('W1')->setValue('Conclusiones');


        $list = [];
        $em = $this->getDoctrine()->getManager();
        $solicitudes = $em->getRepository('App:Solicitud')->findAll();


        foreach ($solicitudes as $s) {

            $pp = '';
            foreach($s->getParteaevaluar() as $p){
                $pp = $pp . $p->getNombre() .' ';
            }

            $conc = '';
            foreach($s->getConclusiones() as $c){
                $conc = $conc . $c->getNombre() .' ';
            }


            $list[] = [
                $s->getCodigo(),
                $s->getFechaentrada()->format('d-m-Y'),
                $s->getProducto()->getNombre(),
                ($s->getIfa()) ? $s->getIfa()->getNombre() : '-',
                ($s->getFortaleza()) ? $s->getFortaleza() : '-',
                ($s->getFf()) ? $s->getFf()->getNombre() : '-',
                ($s->getCategoria()) ? $s->getCategoria()->getNombre() : $s->getClasederiesgo()->getNombre(),
                $s->getSolicitante()->getNombre(),
                $s->getPaissolicitante()->getNombre(),
                $s->getFabricante()->getNombre(),
                $s->getPaisfabricante()->getNombre(),


                                
                ($s->getEstado() != 'Nuevo') ? 'Aprobado' : (($s->getEstado() == 'Rechazado') ? 'Rechazado' : '-'),
                //(a ? b : c) ? d : e

                //a ? b : (c ? d : e)

                $pp,
                ($s->getMuestra() == true) ? 'Si' : 'No',
                $s->getPersonacontacto(),
                ($s->getVale()) ? $s->getVale()->getVale() : '-',
                ($s->getVale()) ? $s->getVale()->getFechavale()->format('d-m-Y') : '-',
                ($s->getVale() && $s->getVale()->getFactura()) ? $s->getVale()->getFactura() : '-',
                ($s->getVale() && $s->getVale()->getFactura()) ? $s->getVale()->getFechafactura()->format('d-m-Y') : '-',
                ($s->getLs()) ? $s->getLs()->getNumero() : '-',
                ($s->getFechaentrada()) ? $s->getFechaentrada()->format('d-m-Y') : '-',
                $s->getObservaciones(),
                $conc
                
            ];
        }

        // Increase row cursor after header write
            $sheet->fromArray($list,null, 'A2', true);
        

        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'solicitudes_excel.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
