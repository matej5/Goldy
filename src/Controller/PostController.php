<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /**
     * @Symfony\Component\Routing\Annotation\Route("/", name="post_index")
     * @param      Request $request
     * @param      EntityManagerInterface $entityManager
     * @param      PostRepository $postRepository
     * @return       \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $form = $this->createForm(PostFormType::class);
        $form->handleRequest($request);

        if (($this->isGranted('ROLE_ADMIN')
                || $this->isGranted('ROLE_BOSS'))
            && ($form->isSubmitted() && $form->isValid())) {
            /**
             * @var Post $post
             */
            $post = new Post();

            if (empty($form->get('image')->getData())) {
                $fileName = 'post.jpg';
            } else {
                $file = $form->get('image')->getData();
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('post_directory'),
                    $fileName
                );
            }

            $post->setImage($fileName);
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $post->setUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'New post created!');
            return $this->redirectToRoute('post_index');
        }

        $posts = $postRepository->getAllInLastWeek();

        return $this->render(
            'post/index.html.twig',
            [
                'form' => $form->createView(),
                'posts' => $posts
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/post/{id}", name="post_view")
     * @param               Post $post
     * @param               Request $request
     * @param               EntityManagerInterface $entityManager
     * @return                \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function show(
        Post $post,
        Request $request,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    ) {
        $postForm = $this->createForm(PostFormType::class, $post);
        $postForm->handleRequest($request);

        if ($this->isGranted('ROLE_ADMIN') && $postForm->isSubmitted() && $postForm->isValid()) {
            $post = $postForm->getData();

            $file = $postForm->get('image')->getData();
            if ($file != null) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('post_directory'),
                    $fileName
                );

                $post->setImage($fileName);
            }

            $post->setUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
        }

        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);
        if ($this->isGranted('ROLE_USER') && $form->isSubmitted() && $form->isValid()) {
            /**
             * @var Comment $comment
             */
            $comment = $form->getData();
            $comment->setUser($this->getUser());
            $post->addComment($comment);
            $date = new \DateTime("now");
            $comment->setCreatedAt($date);
            $entityManager->flush();
            return $this->redirectToRoute(
                'post_view',
                [
                    'id' => $post->getId()
                ]
            );
        }

        return $this->render(
            'post/view.html.twig',
            [
                'post' => $post,
                'commentForm' => $form->createView(),
                'form' => $postForm->createView()
            ]
        );
    }

    /**
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("user             == post.getUser()")
     * @Symfony\Component\Routing\Annotation\Route("/post/{id}/delete", name="post_delete")
     * @param                      Post $post
     * @param                      EntityManagerInterface $entityManager
     * @return                     \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePost(Post $post, EntityManagerInterface $entityManager)
    {
        if ($this->isGranted('ROLE_ADMIN') ||
            ($this->isGranted('ROLE_BOSS') &&
                $post->getUser() == $this->getUser())
        ) {
            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully deleted!');
            return $this->redirectToRoute('post_index');
        } else {
            return $this->redirectToRoute('post_index');
        }
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
