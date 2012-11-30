<?php

namespace Artesys\PracticeToolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Artesys\PracticeToolBundle\Form\Type\ExamType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Artesys\PracticeToolBundle\Form\Model\SelectQuestion;
use Artesys\PracticeToolBundle\Form\Type\SelectQuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/test/", name="select")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new SelectQuestionType($em));

        $request = $this->getRequest();
        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if($form->isValid())
            {
                /* @var $selectQuestion SelectQuestion */
                $selectQuestion = $form->getData();
                $course = $selectQuestion->getCourse();
                $question = $selectQuestion->getQuestionNo();

                //
//                $repository = $this->getDoctrine()->getRepository('ArtesysPracticeToolBundle:Question')
                //バリデーションして、formに表示する方法をためす必要あり
                //カスタムバリデーションも。
//                $aaa = new FormError('aaaaaa');
//                $form['questionNo']->addError($aaa);

                //
                /* @var $repository \Doctrine\ORM\EntityRepository */
                $repository = $this->getDoctrine()->getManager()->getRepository('ArtesysPracticeToolBundle:Question');
                $question = $repository->findOneBy(array('sort'=>$question, 'course'=>$course));

                $response = new RedirectResponse($this->generateUrl('question'));
                $response->headers->setCookie(new Cookie('questionid',  $question->getId(), time() + (3600 * 48)));
                return $response;
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/test/question", name="question")
     * @Template()
     */
    public function questionAction()
    {
        $request = $this->getRequest();
        $examform = $this->createForm(new ExamType());

        //cookie取得
        $questionId = $request->cookies->get('questionid');

        if($request->isMethod('POST'))
        {
            $questionId = 1;
        }

//        $courseId = 1;
//        $questionId = 1;

        /* @var $repository \Doctrine\ORM\EntityRepository */
        $repository = $this->getDoctrine()->getManager()->getRepository('ArtesysPracticeToolBundle:Question');
        $question = $repository->findOneBy(array('id' => $questionId));

        return array('form'=> $examform->createView(), 'question' => $question);
    }

    /**
     * @Route("/test/answer", name="answer")
     * @Template()
     */
    public function answerAction()
    {
        $request = $this->getRequest();
        $examform = $this->createForm(new ExamType());

        if($request->isMethod('POST'))
        {
            $examform->bind($request);
            if($examform->isValid())
            {

            }
        }



        $courseId = 1;
        $questionId = 1;

        /* @var $repository \Doctrine\ORM\EntityRepository */
        $repository = $this->getDoctrine()->getManager()->getRepository('ArtesysPracticeToolBundle:Question');
        $query = $repository->createQueryBuilder('p')
            ->where('p.course.id == :courseId')
            ->setParameter('courseId', $courseId)
            ->getQuery();

        /* @var $question \Artesys\PracticeToolBundle\Entity\Question */
        $question = $query->getResult();

        return array('sentence'=>$question->getSentence());
    }


}
