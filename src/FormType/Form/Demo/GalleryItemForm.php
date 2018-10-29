<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 28/10/2018
 * Time: 12:52 PM
 */

namespace App\FormType\Form\Demo;

use App\Entity\Demo\GalleryItem;
use App\FormType\Component\CompositeCollectionType;
use App\FormType\Form\AssetForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryItemForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("header", TextType::class)
            ->add("content", TextareaType::class)
            ->add("base64_file", TextareaType::class)
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
    }


}