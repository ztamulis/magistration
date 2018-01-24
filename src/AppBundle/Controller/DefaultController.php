<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserEmotion;
use AppBundle\Service\FaceCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('Main.html.twig');
    }

    /**
     * @Route("/emotion", name="emotion")
     */
    public function emotion(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('emotion.html.twig');
    }

    /**
     * @Route("/emotion_list")
     */
    public function getEmotionList()
    {
        return $this->render('emotion_list.html.twig', ['emotions' => $this->getUser()->getEmotions()]);

    }

    /**
     * @Route("/calculate_emotion", name="calculate_emotion")
     * @Method({"GET", "POST"})
     */
    public function postEmgData(Request $request)
    {
        if($request->request->get('emg')){
            $arrData = array_filter($request->request->get('emg'));
            //make something curious, get some unbelieveable data

            /** @var FaceCalculator $faceCalculator */
            $faceCalculator = $this->get('face_calculator');

            $emotionIndex = $faceCalculator->calculateFace($arrData);

            $emotion = new UserEmotion();
            $emotion->setUser($this->getUser());
            $emotion->setEmotion($emotionIndex);
            $emotion->setDate(new \DateTime());

            $this->getDoctrine()->getManager()->persist($emotion);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($emotionIndex);
        }

        throw new BadRequestHttpException();
    }
}

