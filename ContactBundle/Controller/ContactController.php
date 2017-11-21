<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ContactBundle\Form\ContactType;
use ContactBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * Ajouter client
     */
    public function ajouterClientAction(Request $request)
    {

        $contact = new Contact;
        $form = $this->get('form.factory')->create(ContactType::class, $contact);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Votre demande de contact à bien été prise en compte');
            return $this->redirect($this->generateUrl('client_page_index'));
        }

        return $this->render( 'ContactBundle:Client:ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );

    }
}
