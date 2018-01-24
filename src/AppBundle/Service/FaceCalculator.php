<?php
declare(strict_types=1);
namespace AppBundle\Service;
use Phpml\Classification\MLPClassifier;
use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\ModelManager;
use Phpml\Tokenization\WordTokenizer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Metric\Accuracy;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
/**
 * Created by PhpStorm.
 * User: Tamulis
 * Date: 1/21/2018
 * Time: 2:53 PM
 */
class FaceCalculator
{
    /**
     * Calculates face emotion index.
     *
     * @param array $data
     *
     * @return int
     */
    public function calculateFace(array $data)
    {

//        $dataset = new CsvDataset('data/languages.csv', 1);
        $vectorizer = new TokenCountVectorizer(new WordTokenizer());
        $tfIdfTransformer = new TfIdfTransformer();
        $samples = [];
        foreach ($data as $sample) {
            $samples[] = $sample;
        }
//        $vectorizer->fit($samples);
//        $vectorizer->transform($samples);
//        $tfIdfTransformer->fit($samples);
//        $tfIdfTransformer->transform($samples);
//        $dataset = new ArrayDataset($samples, $data);
//        $randomSplit = new StratifiedRandomSplit($dataset, 0.1);
//        $classifier = new SVC(Kernel::RBF, 10000);
//        $classifier->train($randomSplit->getTrainSamples(), $randomSplit->getTrainLabels());
//        $predictedLabels = $classifier->predict($randomSplit->getTestSamples());
//        echo 'Accuracy: '.Accuracy::score($randomSplit->getTestLabels(), $predictedLabels);

        $mlp = new MLPClassifier(1, [2], ['happy', 'disgust', 'sadness', 'fear', 'surprise']);

        $filepath = '/home/vagrant/mashina_lurning';
        $modelManager = new ModelManager();

        $mlp = $modelManager->restoreFromFile($filepath);
        $data = array_map(function ($value) {
            return (float) $value;
        }, $data);
        $data = array_filter($data, function ($value) {
            return $value >= 10;
        });
//        $mlp->train([array_values($data)], ['sadness']);
//        $mlp->predict([3, 2]);
//        $mlp->setLearningRate(0.1);
//        $modelManager->saveToFile($mlp, $filepath);

        $predicted = $mlp->predict([[1, 1, 1, 1]]);
//
        return $predicted[0];
    }
}