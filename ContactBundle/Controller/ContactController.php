<?php

namespace ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ContactBundle\Form\ContactType;
use ContactBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * Ajouter
     */
    public function ajouterClientAction(Request $request)
    {

        $contact = new Contact;
        $form = $this->get('form.factory')->create(ContactType::class, $contact, array('langue' => $request->getLocale()));

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            /* Notification */
            $emails = explode(';',$contact->getObjet()->getEmail());

            $message = \Swift_Message::newInstance()
                ->setSubject('Formulaire de contact')
                ->setFrom('contact@colocarts.com')
                ->setTo($emails)
                ->setBody(
                    $this->renderView('GlobalBundle:Mail:simple.html.twig', array(
                            'titre' => 'Formulaire de contact',
                            'contenu' => '<strong>Nom: </strong>'.$contact->getNom().'<br><strong>Prénom: </strong>'.$contact->getPrenom().'<br><strong>Email: </strong>'.$contact->getEmail().'<br><strong>Objet: </strong>'.$contact->getObjet()->getNom().'<br><strong>Message: </strong><br>'.$contact->getMessage()
                        )
                    ),
                    'text/html'
                );

            /* Envoyer le message */
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('succes', $this->get('translator')->trans('contact.client.validators.succes'));
            return $this->redirect($this->generateUrl('client_page_index'));
        }

        return $this->render( 'ContactBundle:Client:ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );

    }

    /**
     * Gestion
     */
    public function managerAdminAction(Request $request)
    {
        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('conact_manager', array(
                'objet',
                'langue'
            )
        );

        /* La liste des objets */
        $objets = $this->getDoctrine()
                       ->getRepository('ContactBundle:Objet')
                       ->findBy(array(),array('id' => 'DESC'));

        /* La liste des contact */
        $contacts = $this->getDoctrine()
                         ->getRepository('ContactBundle:Contact')
                         ->getAllContacts($recherches['objet'], $recherches['langue']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $contacts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        /* La liste des langues */
        $langues = $this->getDoctrine()->getRepository('GlobalBundle:Langue')->findAll();

        return $this->render('ContactBundle:Admin:manager.html.twig',array(
                'pagination' => $pagination,
                'objets' => $objets,
                'recherches' => $recherches,
                'langues' => $langues
            )
        );
    }

    /*
     * View
     */
    public function viewAdminAction(Contact $contact)
    {
        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des contacts' => $this->generateUrl('admin_contact_manager'),
            'Afficher un contact' => ''
        );

        return $this->render( 'ContactBundle:Admin:view.html.twig',array(
                'contact' => $contact,
                'breadcrumb' => $breadcrumb
            )
        );
    }
}
