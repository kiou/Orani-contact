<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ContactBundle\Form\ObjetType;
use ContactBundle\Entity\Objet;

class ObjetController extends Controller
{
    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request)
    {

        $objet = new Objet;
        $form = $this->get('form.factory')->create(ObjetType::class, $objet);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Objet enregistré avec succès');
            return $this->redirect($this->generateUrl('admin_contactobjet_manager'));
        }

        return $this->render( 'ContactBundle:Admin/Objet:ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );

    }

    /**
     * Gestion
     */
    public function managerAdminAction()
    {
        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('contactobjet_manager', array(
                'langue'
            )
        );

        $objets = $this->getDoctrine()
                       ->getRepository('ContactBundle:Objet')
                       ->getAllObjets($recherches['langue']);

        /* La liste des langues */
        $langues = $this->getDoctrine()->getRepository('GlobalBundle:Langue')->findAll();

        return $this->render('ContactBundle:Admin/Objet:manager.html.twig',array(
                'objets' => $objets,
                'recherches' => $recherches,
                'langues' => $langues
            )
        );
    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Objet $objet)
    {
        if(count($objet->getContacts()) != 0)  throw new NotFoundHttpException('Cette page n\'est pas disponible');

        $em = $this->getDoctrine()->getManager();
        $em->remove($objet);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Objet supprimé avec succès');
        return $this->redirect($this->generateUrl('admin_contactobjet_manager'));
    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Objet $objet)
    {
        $form = $this->get('form.factory')->create(ObjetType::class, $objet);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Objet enregistré avec succès');
            return $this->redirect($this->generateUrl('admin_contactobjet_manager'));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des objets' => $this->generateUrl('admin_contactobjet_manager'),
            'Modifier un objet' => ''
        );

        return $this->render('ContactBundle:Admin/Objet:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'objet' => $objet,
                'form' => $form->createView()
            )
        );

    }

}
