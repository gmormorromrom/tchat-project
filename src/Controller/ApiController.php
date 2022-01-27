<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{


    /**
     * @Route("/test", name="testcomment", methods={"GET","POST"})
     */
    public function indexTest(Request $request, SerializerInterface $serializer): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData, [

            'action' => $this->generateUrl('api_testcomment'),
            'attr' => [
                'id' => 'form_test',
                // 'novalidate' => 'novalidate',
            ]
        ])
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $request->getMethod() == "POST") {
            $data = $form->getData();
            if ($data['name'] === "bonjour") {
                return $this->json(['err' => 200, 'message' => "ceci n'est pas un nom correcte"], 200);
                //die();
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {

            return new Response('je suis dans la form submit et valid');
        }
        return $this->render('comment/test.html.twig', [
            'defaultData' => $defaultData,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment", name="comment", methods={"GET","POST"})
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [

            'action' => $this->generateUrl('api_comment'),
            'attr' => [
                'id' => 'form_comment_new',
                // 'novalidate' => 'novalidate',
            ]
        ]);

        $form->handleRequest($request);
        if ($request->getMethod() === "POST" && $request->isXmlHttpRequest() && $form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setCreatedBy($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Message added successfully!');
            return $this->json(['code' => 200, 'message' => "message added"], 201);
            // return $this->redirectToRoute('category_index');
        }
        $view = $this->renderView('comment/_form.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
        // $frm = $serializer->serialize($view, 'json');
        //  dd($view);
        return $this->json(['view' => $view], 200);
    }
}
