<?php
namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Step;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConfirmController extends Controller
{


    /**
     * @Route("{_locale}/confirm/{recetteId}/{stepId}", name="confirm_controller_remove_step")
     */
    public function removeStepAction($recetteId, $stepId)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         *
         * @var Step
         */
        $step = $em->getRepository(Step::class)->find($stepId);
        if ($step != null) {
            $em->remove($step);
            $em->flush();
        }
        /**
         *
         * @var Recette $recette
         */
        $recette = $em->getRepository(Recette::class)->find($recetteId);
        return $this->redirectToRoute('configure_recette_controller_configure_recette', array(
            'page' => 'configure_recette',
            'id' => $recetteId
        ));
    }

}
