<?php

namespace App\Form;

use App\Entity\Coiffeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Entity\Region;
use App\Entity\Departement;
use App\Entity\Ville;

class CoiffeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', EntityType::class, [
                'label' => 'Région',
                'class' => Region::class,
                'placeholder' => 'Sélectionnez la région',
                'mapped' => false,
                'required' => false
            ])
        ;
        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                // $region = $event->getForm()->getData();
                $form = $event->getForm();
                $this->addDepartementField($form->getParent(), $form->getData());
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $data = $event->getData();
                $ville = $data->getVille();

                if($ville) {
                    $departement = $ville->getDepartement();
                    $region = $departement->getRegion();

                    $form = $event->getForm();

                    $this->addDepartementField($form, $region);
                    $this->addVilleField($form, $departement);
                    $form->get('region')->setData($region);
                    $form->get('departement')->setData($departement);
                }
            }
        );
    }

    /*
    * @param FormInterface $form
    * @param Region $region
    */
    private function addDepartementField(FormInterface $form, ?Region $region) {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'departement',
            EntityType::class,
            null,
            [
                'class' => Departement::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => $region ? 'Sélectionnez un département' : 'Sélectionnez votre région',
                'choices' => $region ? $region->getDepartements() : [],
                'auto_initialize' => false
            ]
        );
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addVilleField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

    /*
    * @param FormInterface $form
    * @param Departement $departement
    */
    private function addVilleField(FormInterface $form, ?Departement $departement) {
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'placeholder' => $departement ? 'Sélectionnez la ville' : 'Sélectionnez votre département',
            'choices' => $departement ? $departement->getVilles() : [],
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Coiffeur::class,
        ]);
    }
}
