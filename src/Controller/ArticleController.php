<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/add", name="add_new_article_form")
     * @param Request $request
     *
     * @return Response
     */
    public function getAddArticleForm(Request $request): Response
    {
        return $this->render('add_article.html.twig');
    }

    /**
     * @Route("/add-article", name="add_new_article_submit",methods={"POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function addArticleSubmit(Request $request): Response
    {
        if($this->isInRequestSetAllNeedleFields($request,['text','title','name']) === false) {
            $this->addFlash(
                'warning',
                'Не заполнены все нужные поля'
            );
            return $this->redirect('/add');
        }

        $em = $this->getDoctrine()->getManager();

        $newArticle = new Article();
        $newArticle->setName($request->get('name'));
        $newArticle->setText($request->get('text'));
        $newArticle->setTitle($request->get('title'));

        $em->persist($newArticle);
        $em->flush();

        $this->addFlash(
            'success',
            'Страница: ' . $newArticle->getName() . ' успешно добавлена'
        );

        return $this->redirect('/'.$newArticle->getName());
    }

    /**
     * @Route("/{articleName}", name="get_article_by_name",methods={"GET"})
     * @param string $articleName
     */
    public function getArticle(string $articleName)
    {
        $em = $this->getDoctrine()->getManager();
        $needleArticle = $em->getRepository(Article::class)
            ->findOneBy(['name' => $articleName]);

        if($needleArticle === null) {
            return new Response('Страница отсутвует',404);
        }

        return $this->render('article_index.html.twig',['article'=>$needleArticle]);
    }

    /**
     * @Route("/", name="get_all_articles")
     * @param Request $request
     *
     * @return Response
     */
    public function getAllArticles(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Article::class);
        $allArticles = $repository->findAll();

        return $this->render(
            'main_page.html.twig',
            [
                'allArticles' => $allArticles
            ]
        );
    }

    /**
     * @Route("/{articleName}/edit", name="edit_article_by_name_form")
     * @param string $articleName
     *
     * @return Response
     */
    public function getEditArticleForm(string $articleName)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Article::class);

        $needleArticle = $repository->findOneBy(['name' => $articleName]);

        if($needleArticle === null) {
            return new Response('Страница отсутвует',404);
        }

        return $this->render(
            'edit_article.html.twig',
            [
                'article' => $needleArticle
            ]
        );
    }

    /**
     * @Route("/{name}/edit-submit", name="edit_article_by_name_submit")
     * @param Request $request
     *
     * @return Response
     */
    public function editArticle(Request $request): Response
    {
        if($this->isInRequestSetAllNeedleFields($request,['id','text','title','name']) === false) {
            $this->addFlash(
                'warning',
                'Не заполнены все нужные поля'
            );
            return $this->redirect('/');
        }

        $articleId = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Article::class);

        /** @var Article $needleArticle */
        $needleArticle = $repository->find($articleId);
        if($needleArticle === null) {
            return new Response('Страница отсутвует',404);
        }
        $needleArticle->setTitle($request->get('title'))
            ->setText($request->get('text'))
            ->setName($request->get('name'));

        $em->persist($needleArticle);
        $em->flush();

        $this->addFlash(
            'success',
            'Страница: ' . $needleArticle->getName() . ' успешно отредактирована'
        );

        return $this->redirect('/'.$needleArticle->getName());
    }

    /**
     * @Route("/{articleName}/delete", name="delete_article_by_name")
     * @param string $articleName
     *
     * @return Response
     */
    public function deleteArticle(string $articleName): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Article::class);

        $needleArticle = $repository->findOneBy(['name' => $articleName]);

        if($needleArticle === null) {
            return new Response('Страница отсутвует',404);
        }

        $em->remove($needleArticle);
        $em->flush();

        $this->addFlash(
            'success',
            'Страница: ' . $needleArticle->getName() . ' успешно удалена'
        );

        return $this->redirect('/');
    }

    /**
     * @param Request $request
     * @param array $fieldsToCheck
     * @return bool
     */
    private function isInRequestSetAllNeedleFields(Request $request, array $fieldsToCheck): bool
    {
        if(empty($fieldsToCheck)) {
            return true;
        }

        foreach ($fieldsToCheck as $fieldToCheck) {
            $fieldValue = $request->get($fieldToCheck);
            if($fieldValue === null || empty($fieldValue)) {
                return false;
            }
        }
        return true;
    }
}