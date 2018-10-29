<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 28/10/2018
 * Time: 1:26 PM
 */

namespace App\FormType\Form;

use App\Entity\Base\Asset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssetForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add("path", FileType::class, [
            "label" => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "data_class" => Asset::class,
        ]);
    }


}