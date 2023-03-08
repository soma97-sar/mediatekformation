<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PlaylistType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateTimeType::class,[
                'widget'=>'single_text',
                'data'=> isset($options['data'])&&
                    $options['data']->getPublishedAt() !=null ? $options['data']->getPublishedAt() :new dateTime('now'),
            ])
            ->add('title')
            ->add('description',null)
            ->add('videoId')
            ->add('playlist', FormType::class,[
                'action'=> Playlist::class
            ])
            ->add('categories',null)
            ->add('submit', SubmitType::class, [
                'label'=>'Enregistrer'
            ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
