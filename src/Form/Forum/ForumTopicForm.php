<?php


namespace App\Form\Forum;


use App\Type\EditorType;
use App\Entity\Forum\Tag;
use App\Entity\Forum\Topic;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumTopicForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('tags', EntityType::class, [
                'required' => false,
                //'multiple' => true,
                'class' => Tag::class,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('t')
                                ->addSelect('p')
                                ->leftJoin('t.parent','p')
                                //->where('t.parent IS NULL')
                                ->orderBy('t.position', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('content', EditorType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }

}