<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;

use Beelab\Recaptcha2Bundle\Form\Type\RecaptchaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('Titre')
            ->add('Description')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (PNG ou JPG)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télécharger',
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => 'carre',
                'asset_helper' => true,
            ])
            ->add('captcha', RecaptchaType::class, [
                // You can use RecaptchaSubmitType
                // "groups" option is not mandatory
                'constraints' => new Recaptcha2(['groups' => ['create']]),
                'label' => 'Confirmez que vous n\' est pas un robot.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
