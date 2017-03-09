<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use CalendarioBundle\Service\AulaFacade;
use CursoBundle\Service\DisciplinaOfertadaFacade;

class FrequenciaReportController extends Controller {
    
    function getAulaFacade() {
        return $this->get('facade.calendario.aulas');
    }
    
    function getDisciplinaOfertadaFacade() {
        return $this->get('facade.curso.disciplinas_ofertadas');
    }
    
    /**
    * @Route("/diario-frequencia", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "default/stylesheet.xml")
    */
    function diarioFrequenciaAction(Request $request) {
        try {
            $mes = $request->query->getInt('mes', 1);
            $disciplina = $this->getDisciplinaOfertadaFacade()->find(
                $request->query->getInt('disciplina')
            );
            $aulas = $this->getAulaFacade()->findAll(['mes' => $mes, 'disciplina' => $disciplina->getId()]);
            $enturmacoes = $disciplina->getTurma()->getEnturmacoes(); 
            return $this->render('frequencia/diarioFrequencia.pdf.twig', [
                'disciplina' => $disciplina,
                'aulas' => $aulas, 
                'enturmacoes' => $enturmacoes
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
