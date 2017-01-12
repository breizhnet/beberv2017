<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Beber;
use AppBundle\Form\BeberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Location');
        $locations = $repository->findAll();
        return $this->render('front/home/index.html.twig',['locations' => $locations]);
    }

    /**
     * @param Request $request
     * @Route("/create", name="beber_create_location")
     * @Template()
     * @Method("POST")
     */
    public function createBeberAction(Request $request){
        $beber = new Beber();

        $form = $this->createForm(
            BeberType::class,
            $beber,
            ['action' => $this->generateUrl('beber_create_submit')]
        );
        $request = $this->getRequest();

        return array('beber'=>$beber, 'form' => $form->createView());
    }



}
